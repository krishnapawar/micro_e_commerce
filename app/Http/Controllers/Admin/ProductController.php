<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\{Product,ProductFile};

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::where('user_id',auth()->id())->paginate(10);
        return view('admin.product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // return $request->all();
        $request->validate([
            'product_name' => 'required',
            'product_price' => 'required',
            'product_quantity' => 'required',
            'description' => 'required',
        ]);
        
        try {
            //code...
            $product = new Product;
            $product->product_name = $request->product_name;
            $product->user_id = auth()->id();
            $product->product_price = $request->product_price;
            $product->product_quantity = $request->product_quantity;
            $product->description = $request->description;
            $product->save();
            $product_id = $product->id;
            $files = $request->file('images');
            foreach ($files as $file) {
                $filenameOrigal = $file->getClientOriginalName();
                $filename = uniqid()."_.".$file->getClientOriginalExtension();

                $file->move(public_path('uploads/product'), $filename);
                $product_file = new ProductFile;
                $product_file->product_id = $product_id;
                $product_file->file_name = $filename;
                $product_file->file_path = 'uploads/product/'.$filename;
                $product_file->save();
            }
            return redirect()->route('product.index')->with('success', 'Product Added Successfully');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $product = Product::where('id',$id)->with('productFiles')->first();
        return view('admin.product.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        try {
            $product = Product::where('id',$id)->first();
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->product_price = $request->product_price;
            $product->product_quantity = $request->product_quantity;
            $product->save();
            $product_id = $product->id;
            $files = $request->file('images');
            foreach ($files as $file) {
                $filenameOrigal = $file->getClientOriginalName();
                $filename = uniqid()."_.".$file->getClientOriginalExtension();

                $file->move(public_path('uploads/product'), $filename);
                $product_file = new ProductFile;
                $product_file->product_id = $product_id;
                $product_file->file_name = $filename;
                $product_file->file_path = 'uploads/product/'.$filename;
                $product_file->save();
            }
            return redirect()->route('product.index')->with('success', 'Product Updated Successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $product = Product::where('id',$id)->first();
            $product->delete();
            return redirect()->route('product.index')->with('success', 'Product Deleted Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroyfile(Request $request)
    {
        //
        try {
            $product = ProductFile::where('id', $request->id)->first();
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'File Deleted Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                ]);
        }
    }
}
