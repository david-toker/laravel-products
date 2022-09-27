<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        $formFields = $request->validate(([
            'name'=>'required',
            'price'=>'required',
            'description'=>'required',
        ]));

        if($request->hasFile('file')) {
            $formFields['file_path'] = $request->file('file')->store('products');
        }

        return Product::create($formFields);

        // $product = new Product;
        // $product->name = $request->input('name');
        // $product->price = $request->input('price');
        // $product->description = $request->input('description');
        // $product->file_path = $request->file('file')->store('products');
        // $product->save();

        // return $product;

    }

    function list()
    {
        return Product::all();
    }

    function delete($id) {
        $result = Product::where('id', $id)->delete();
        if($result) {
            return ['result'=>'product has been deleted!'];
        } else {
            return ['result'=>'operation failed'];
        }
    }

    function deleteAll(Request $request)
    {
        $ids = $request->ids;
        $result = Product::whereIn('id',explode(",",$ids))->delete();
        if ($result) {
            return ['success'=>"Products Deleted successfully."];
        } else {
            return ['result'=>'operation failed'];
        }
    }

    function getProduct($id)
    {
        return Product::find($id);
    }

    function updateProduct($id, Request $request)
    {
        $product = Product::find($id);
        // $product->name = $request->input('name');
        // $product->price = $request->input('price');
        // $product->description = $request->input('description');
        $product->update($request->all());
        if ($request->file('file')) {
            $product->file_path = $request->file('file')->store('products');
        }
        $product->save();

        return $product;
    }

    function search($key)
    {
        return Product::where("name", "Like", "%$key%")->get();
    }
}
