<?php

namespace App\Modules\Product\Controllers;

use App\Libraries\Encryption;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['products'] = Product::where('is_archive',false)->get();
        return view("Product::index",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Product::create");
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
                'name'  => ['required', Rule::unique('products')->where(function ($query) {
                    $query->where('is_archive', false); })],
                'price'  => 'required',
                'quantity'  => 'required|integer',
                'photo'  => 'required|image',
                'status' => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors()
                ]);
            }

            DB::beginTransaction();
            $product = new Product();
            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->quantity = $request->input('quantity');
            $product->status = $request->input('status');

            if($request->hasFile('photo')){
                $path = 'uploads/product-photos/';
                $_productPhoto = $request->file('photo');
                $mimeType = $_productPhoto->getClientMimeType();

                if(!in_array($mimeType,['image/jpg', 'image/jpeg', 'image/png']))
                    throw new \Exception('Only PNG or JPEG or JPG type images are allowed!', 123);

                if(!file_exists($path))
                    mkdir($path, 0777, true);

                $productPhoto = trim(sprintf('%s', uniqid('ProductPhoto_', true))) . '.' . $_productPhoto->getClientOriginalExtension();
                Image::make($_productPhoto->getRealPath())->resize(300,300)->save($path . '/' . $productPhoto);
                $product->photo = $productPhoto;
            }

            $product->save();

            /* Generating Product Code */
            $productPrefix = 'P-';
            DB::statement("update products, products as table2  SET products.code=(
            select concat('$productPrefix', LPAD( IFNULL(MAX(SUBSTR(table2.code,-4,4) )+1,0),4,'0')) as code
            from (select * from products ) as table2
            where table2.id!='$product->id' and table2.code like '$productPrefix%')
            where products.id='$product->id' and table2.id='$product->id'");

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Product created successfully.',
                'link' => route('products.index')
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
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function edit($productId)
    {
        $decodedProductId = Encryption::decodeId($productId);
        $data['product'] = Product::find($decodedProductId);

        return view("Product::edit",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productId)
    {
        try {
            $decodedProductId = Encryption::decodeId($productId);
            $validation = Validator::make($request->all(), [
                'name'    => ['required', Rule::unique('products')->ignore($decodedProductId)->where(function ($query) {
                    $query->where('is_archive', false); })],
                'price'   => 'required',
                'quantity'   => 'required|integer',
                'photo'   => 'required',
                'status'  => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors()
                ]);
            }

            $product = Product::find($decodedProductId);
            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->quantity = $request->input('quantity');
            $product->status = $request->input('status');
            $product->photo = $request->input('photo');

            if($request->hasFile('photo')){
                $path = 'uploads/product-photos/';
                $_productPhoto = $request->file('photo');
                $mimeType = $_productPhoto->getClientMimeType();

                if(!in_array($mimeType,['image/jpg', 'image/jpeg', 'image/png']))
                    throw new \Exception('Only PNG or JPEG or JPG type images are allowed!', 123);

                if(!file_exists($path))
                    mkdir($path, 0777, true);

                $previousExistingPhoto = $path.'/'.$product->photo; // get previous photo from folder
                if (File::exists($previousExistingPhoto)) // unlink or remove previous photo from folder
                    @unlink($previousExistingPhoto);

                $productPhoto = trim(sprintf('%s', uniqid('ProductPhoto_', true))) . '.' . $_productPhoto->getClientOriginalExtension();
                Image::make($_productPhoto->getRealPath())->resize(300,300)->save($path . '/' . $productPhoto);
                $product->photo = $productPhoto;
            }

            $product->save();

            return response()->json([
                'success' => true,
                'status' => 'Product updated successfully.',
                'link' => route('products.index')
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
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function delete($productId)
    {
        $decodedProductId = Encryption::decodeId($productId);
        $product = Product::find($decodedProductId);
        $product->is_archive  = 1;
        $product->deleted_by  = auth()->user()->id;
        $product->deleted_at  = Carbon::now();
        $product->save();
        session()->flash('flash_success','Product deleted successfully.');
    }
}
