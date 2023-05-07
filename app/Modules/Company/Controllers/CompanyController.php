<?php

namespace App\Modules\Company\Controllers;
use App\Libraries\Encryption;
use App\Modules\Company\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['companies'] = Company::where('is_archive',false)->get();
        return view("Company::index",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Company::create");
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
                'name'      => ['required', Rule::unique('companies')->where(function ($query) {
                    $query->where('is_archive', false); })],
                'status'    => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors()
                ]);
            }

            $company = new Company();
            $company->name = $request->input('name');
            $company->status = $request->input('status');
            $company->save();

            return response()->json([
                'success' => true,
                'status' => 'Company created successfully.',
                'link' => route('companies.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $companyId
     * @return \Illuminate\Http\Response
     */
    public function show($companyId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $companyId
     * @return \Illuminate\Http\Response
     */
    public function edit($companyId)
    {
        $decodedCompanyId = Encryption::decodeId($companyId);
        $data['company'] = Company::find($decodedCompanyId);

        return view("Company::edit",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId)
    {
        try {
            $decodedCompanyId = Encryption::decodeId($companyId);
            $validation = Validator::make($request->all(), [
                'name'    => ['required', Rule::unique('companies')->ignore($decodedCompanyId)->where(function ($query) {
                    $query->where('is_archive', false); })],
                'status'  => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors()
                ]);
            }

            $company = Company::find($decodedCompanyId);
            $company->name = $request->get('name');
            $company->status = $request->input('status');
            $company->save();

            return response()->json([
                'success' => true,
                'status' => 'Company updated successfully.',
                'link' => route('companies.index')
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
     * @param  int  $companyId
     * @return \Illuminate\Http\Response
     */
    public function delete($companyId)
    {
        $decodedCompanyId = Encryption::decodeId($companyId);
        $company = Company::find($decodedCompanyId);
        $company->is_archive  = 1;
        $company->deleted_by  = auth()->user()->id;
        $company->deleted_at  = Carbon::now();
        $company->save();
        session()->flash('flash_success','Company deleted successfully.');
    }
}
