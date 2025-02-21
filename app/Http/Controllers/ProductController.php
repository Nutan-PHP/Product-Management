<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try{
                $products = Product::orderBy('created_at', 'desc')->get();
                return response()->json(['data' => $products, 'status' => true]);
            }catch(Exception $e){
                return response()->json(['error' => $e->getMessage(), 'status' => false]);
            }
        }
    
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
            ]);
            
            if ($validator->fails()) {//pass validator errors as errors object for ajax response
                return response()->json(['errors' => $validator->errors(), 'status' => false]);
            }

            $product = Product::create([
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
            return response()->json(['data' => $product, 'status' => true, 'message' => 'Product Updated successfully']);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, Product $product)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
            ]);
            
            if ($validator->fails()) {//pass validator errors as errors object for ajax response
                return response()->json(['errors' => $validator->errors(), 'status' => false]);
            }

            $product->update([
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
            return response()->json(['data' => $product, 'status' => true, 'message' => 'Product Updated successfully']);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
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
}
