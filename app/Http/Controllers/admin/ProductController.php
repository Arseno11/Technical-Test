<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::latest();

        if (!empty(request()->get('keyword'))) {
            $product = $product->where('name', 'like', '%' . request()->get('keyword') . '%');
        }

        $product = $product->paginate(10);

        return view('admin.products.list', compact('product'));
    }

    public function create()
    {
        return view('admin.products.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        if ($validator->passes()) {
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = $request->status;
            $product->save();

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $product->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/product/' . $newImageName;
                File::copy($sPath, $dPath);

                //create thumbnail
                $dPath = public_path() . '/uploads/product/thumb/' . $newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);


                $product->image = $newImageName;
                $product->save();
            }

            $request->session()->flash('success', 'Product Created Successfully');

            return response()->json([
                'status' => true,
                'success' => 'Product Created Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($productId, Request $request)
    {
        $product = Product::find($productId);

        if (empty($product)) {
            return redirect()->route('product.index');
        }

        return view('admin.products.edit', compact('product'));
    }
    public function update($productId, Request $request)
    {
        $product = Product::find($productId);

        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');

            return response()->json([
                'status' => false,
                "notFound" => true,
                'message' => 'Product not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        if ($validator->passes()) {
            $product->name = $request->name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = $request->status;
            $product->save();

            $OldImage = $product->image;

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $product->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/product/' . $newImageName;
                File::copy($sPath, $dPath);

                //create thumbnail
                $dPath = public_path() . '/uploads/product/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);


                $product->image = $newImageName;
                $product->save();


                File::delete(public_path() . '/uploads/product/thumb/' . $OldImage);
                File::delete(public_path() . '/uploads/product/' . $OldImage);
            }

            $request->session()->flash('success', 'Product Update Successfully');

            return response()->json([
                'status' => true,
                'success' => 'Product Created Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function distroy($productId, Request $request)
    {
        $product = Product::find($productId);

        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => true,
                'message' => 'Product not found'
            ]);
        }

        File::delete(public_path() . '/uploads/product/thumb/' . $product->image);
        File::delete(public_path() . '/uploads/product/' . $product->image);

        $product->delete();

        $request->session()->flash('success', 'Product Deleted Successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product Deleted Successfully'
        ]);
    }
}
