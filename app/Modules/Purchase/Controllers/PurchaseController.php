<?php

namespace App\Modules\Purchase\Controllers;

use App\Libraries\Encryption;
use App\Modules\Company\Models\Company;
use App\Modules\Product\Models\Product;
use App\Modules\Purchase\Models\Purchase;
use App\Modules\Purchase\Models\PurchaseDetail;
use App\Modules\Stock\Models\Stock;
use App\Modules\Supplier\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['purchaseInfos'] = Purchase::getPurchaseList()
            ->orderBy('purchases.id', 'desc')
            ->get([
                'purchases.*',
                'companies.name as company_name',
                'suppliers.name as supplier_name'
            ]);

        return view("Purchase::index",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['companies'] = Company::where('is_archive',false)->pluck('name','id');
        $data['suppliers'] = Supplier::where('is_archive',false)->pluck('name','id');
       return view("Purchase::create",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id'    => 'required',
            'supplier_id'   => 'required',
            'purchase_date' => 'required',
        ],[],[
            'company_id'    => 'company',
            'supplier_id'   => 'supplier',
            'purchase_date' => 'purchase date'
        ]);
        $purchaseProducts = Session::get("purchase.products");

        try {
            DB::beginTransaction();
            $purchase                 = new Purchase();
            $purchase->company_id     = $request->input('company_id');
            $purchase->supplier_id    = $request->input('supplier_id');
            $purchase->purchase_date  = $request->input('purchase_date');
            $purchase->save();

            $total = 0;
            foreach ($purchaseProducts as $key => $product) {
                $purchaseDetails              = new PurchaseDetail();
                $purchaseDetails->purchase_id = $purchase->id;
                $purchaseDetails->product_id  = $product['id'];
                $purchaseDetails->unit_price  = $product['unit_price'];
                $purchaseDetails->quantity    = $product['quantity'];
                $purchaseDetails->status      = 1;
                $purchaseDetails->save();

                $product = Product::find($purchaseDetails->product_id);
                $product->quantity = $product->quantity - $purchaseDetails->quantity;
                $product->save();

                $stock = Stock::firstOrNew(['product_id' => $purchaseDetails->product_id]);
                $stock->product_id = $purchaseDetails->product_id;
                $stock->quantity = $product->quantity;
                $stock->status = 1;
                $stock->save();

                $total += $purchaseDetails->unit_price;
            }

            /* Generating Purchase No*/

            $purchasePrefix = "P-" . date("Ymd");
            DB::statement("update purchases, purchases as table2  SET purchases.purchase_no=
            ( select concat('$purchasePrefix', LPAD( IFNULL(MAX(SUBSTR(table2.purchase_no,-6,6) )+1,0),6,'0')) as purchase_no
            from (select * from purchases ) as table2 where table2.id!='$purchase->id' and table2.purchase_no like '$purchasePrefix%')
            where purchases.id='$purchase->id' and table2.id='$purchase->id'");

            $purchase->total = $total;
            $purchase->save();

            Session::forget("purchase.products");
            DB::commit();

            Session::flash('flash_success', 'Products purchased successfully');

            return redirect('/purchases');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('flash_danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $purchaseProductId
     * @return \Illuminate\Http\Response
     */
    public function show($purchaseProductId)
    {
        $decodedPurchaseProductId = Encryption::decodeId($purchaseProductId);
        $data['purchase'] = Purchase::getPurchaseList()
            ->where('purchases.id', $decodedPurchaseProductId)
            ->orderBy('purchases.id', 'desc')
            ->first([
                'purchases.*',
                'companies.name as company_name',
                'suppliers.name as supplier_name'
            ]);

        $data['purchaseDetails'] = PurchaseDetail::getPurchaseDetailsList($decodedPurchaseProductId);

        return view("Purchase::details",$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productAutoSuggest(Request $request)
    {
        $activeProducts = Product::where(['status'=> 1,'is_archive'=> 0]);
        $term =  $request->input('term');
        if ($term == 'all'){
            $search_items = $activeProducts->orderBy('name', 'desc')->get();
        }else {
            $search_items = $activeProducts->where(function($join) use ($term){
                $join->where('name', 'like', '%' . $term . '%');
                $join->orWhere('code', 'like', '%' . $term . '%');
            })->orderBy('name', 'desc')->get(['id', 'name', 'code']);
        }

        return response()->json($search_items);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function productAddCart(Request $request){
        try {
            $productId = $request->get('productId');
            $data = Product::where('is_archive', 0)->where('id', $productId) ->first();

            if (!$data) {
                return redirect()->back()->with('flash_danger', "This product is not available");
            }

            $productInfo = [];
            $productInfo['id'] = $data->id;
            $productInfo['name'] = $data->name;
            $productInfo['code'] = $data->code;
            $productInfo['price'] = $data->price;
            $productInfo['stock'] = $data->quantity;
            $productInfo['quantity'] = 1;

            if (Session::get("purchase.products.$productId")) {
                $productInfo['quantity'] = Session::get("purchase.products.$productId.quantity") + 1;
            }

            $productInfo['unit_price'] = $productInfo['quantity'] * $data->price;
            Session::put("purchase.products.$productId", $productInfo);
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('flash_danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productCartEditDelete(Request $request){

        try {
            $getProductInfo = $request->all();
            $productId = $getProductInfo['product_id'];

            if ($getProductInfo['edit_delete'] == 'edit') {
                $productInfo = array();
                $productInfo['id'] = $getProductInfo['product_id'];
                $productInfo['name'] = Session::get("purchase.products.$productId.name");
                $productInfo['stock'] = Session::get("purchase.products.$productId.stock");
                $productInfo['code'] = Session::get("purchase.products.$productId.code");
                $productInfo['price'] = $getProductInfo['price'];
                $productInfo['quantity'] = $getProductInfo['quantity'];

                if ($getProductInfo['quantity'] == 0)
                    $productInfo['quantity'] = Session::get("purchase.products.$productId.quantity");

                if ( $productInfo['stock'] <  $productInfo['quantity']) {
                    return redirect()->back()->with('flash_danger', "Only {$productInfo['stock']} products available");
                }

                if ($getProductInfo['unit_price'] < 1)
                    $productInfo['unit_price'] = Session::get("purchase.products.$productId.unit_price");

                $productInfo['unit_price'] =  $productInfo['price'] * $productInfo['quantity'];

                Session::put("purchase.products.$productId", $productInfo);
            } else {
                Session::forget("purchase.products.$productId");
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('flash_danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanySupplier(Request $request)
    {
        $companyId = $request->input('company_id');
        $suppliers = Supplier::where('company_id', $companyId)
            ->where(['is_archive' => false, 'status' => true])
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id');

        $data = ['responseCode' => 1, 'data' => $suppliers];
        return response()->json($data);
    }

}
