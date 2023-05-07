<?php

namespace App\Modules\Supplier\Controllers;

use App\Libraries\Encryption;
use App\Modules\Company\Models\Company;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['suppliers'] = Supplier::leftJoin('companies','companies.id','=','suppliers.company_id')
            ->where('suppliers.is_archive',0)
            ->orderBy('suppliers.id','desc')
            ->get([
                'suppliers.*',
                'companies.name as company_name'
            ]);

        return view("Supplier::index",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['companies'] = Company::where('is_archive',false)->pluck('name','id');
        return view("Supplier::create",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'company_id'    => 'required',
                'name'    => 'required',
                'email' => 'required|email|unique:suppliers',
                'mobile'    => 'required',
                'address'    => 'required',
                'status'    => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors()
                ]);
            }

            DB::beginTransaction();
            $supplier = new Supplier();
            $supplier->company_id = $request->input('company_id');
            $supplier->name = $request->input('name');
            $supplier->email = $request->input('email');
            $supplier->mobile = $request->input('mobile');
            $supplier->address = $request->input('address');
            $supplier->status = $request->input('status');
            $supplier->save();

            /* Generating Supplier ID No */

            $SupplierPrefix = 'S-';
            DB::statement("update suppliers, suppliers as table2  SET suppliers.supplier_id_no=(
            select concat('$SupplierPrefix', LPAD( IFNULL(MAX(SUBSTR(table2.supplier_id_no,-4,4) )+1,0),4,'0')) as supplier_id_no
            from (select * from suppliers ) as table2
            where table2.id!='$supplier->id' and table2.supplier_id_no like '$SupplierPrefix%')
            where suppliers.id='$supplier->id' and table2.id='$supplier->id'");

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Supplier created successfully.',
                'link' => route('suppliers.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'status' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $supplierId
     * @return \Illuminate\Http\Response
     */
    public function show($supplierId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $supplierId
     * @return \Illuminate\Http\Response
     */
    public function edit($supplierId)
    {
        $decodedSupplierId = Encryption::decodeId($supplierId);
        $data['supplier'] = Supplier::find($decodedSupplierId);
        $data['companies'] = Company::where('is_archive',false)->pluck('name','id');

        return view("Supplier::edit",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $supplierId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $supplierId)
    {
        try {

            $decodedSupplierId = Encryption::decodeId($supplierId);
            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'name'    => 'required',
                'email'   => ['required', 'email', Rule::unique('suppliers')->ignore($decodedSupplierId)],
                'mobile'  => 'required',
                'address' => 'required',
                'status'  => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors()
                ]);
            }

            $supplier = Supplier::find($decodedSupplierId);
            $supplier->company_id = $request->input('company_id');
            $supplier->name = $request->input('name');
            $supplier->email = $request->input('email');
            $supplier->mobile = $request->input('mobile');
            $supplier->address = $request->input('address');
            $supplier->status = $request->input('status');
            $supplier->save();

            return response()->json([
                'success' => true,
                'status' => 'Supplier updated successfully.',
                'link' => route('suppliers.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => $e->getMessage()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $supplierId
     * @return \Illuminate\Http\Response
     */
    public function delete($supplierId)
    {
        $decodedSupplierId = Encryption::decodeId($supplierId);
        $supplier = Supplier::find($decodedSupplierId);
        $supplier->is_archive  = 1;
        $supplier->deleted_by  = auth()->user()->id;
        $supplier->deleted_at  = Carbon::now();
        $supplier->save();
        session()->flash('flash_success','Supplier deleted successfully.');
    }
}
