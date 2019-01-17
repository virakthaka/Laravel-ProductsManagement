<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProducts;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function showProducts(){
        $products = Product::with('getCategory','getUser')->where('insert_by',Auth::id())->get();
        return response()->json($products);
    }
    public function searchItems(Request $request){
        $products = Product::with('getCategory','getUser')->where('pro_name','LIKE', '%' . $request->key_search . '%')->where('insert_by',Auth::id())->get();
        return response()->json($products);
    }
    public function insertProduct(StoreProducts $request){

        $validated = $request->validated();
        $totalPath='';
        if ($request->hasFile('images')){
            foreach($request->file('images') as $index => $image){
                $imagePath = Storage::disk('public')->put('products', $image);
                $totalPath .= $index>0?','.$imagePath:$imagePath;
            }
        }

        $product = new Product();
        $product->pro_name = $validated['title'];
        $product->category = $validated['category'];
        $product->pro_price = $validated['price'];
        $product->pro_quantity = $validated['quantity'];
        $product->pro_images = $totalPath;
        $product->insert_by = Auth::user()->id;
        $product->save();
        return response()->json(true);
    }
    public function deleteProduct(Request $request){
        Product::where('id',$request->id)->where('insert_by',Auth::id())->delete();
        return response()->json(true);
    }

    public function getProduct($id){

        $product = Product::with('getCategory','getUser')->where('id',$id)->where('insert_by',Auth::id())->first();
        return response()->json($product);
    }
    public function getPageEditProduct($id){
        $product = Product::with('getCategory','getUser')->where('id',$id)->where('insert_by',Auth::id())->first();
        $categories = Category::all();

        return response()->json(["product"=>$product,"categories"=>$categories]);
    }
    public function editProduct(StoreProducts $request){
        $validated = $request->validated();
        $product = Product::where('id',$request->id)->where('insert_by',Auth::id())->first();
        $product->pro_name = $validated['title'];
        $product->category = $validated['category'];
        $product->pro_price = $validated['price'];
        $product->pro_quantity = $validated['quantity'];
        $product->insert_by = Auth::user()->id;
        $product->save();
        return response()->json(true);
    }

    public function addCategory(Request $request){

        $validatedData = $request->validate([
            'category' => 'required|max:30',
        ]);

        $category = new Category();
        $category->category = $validatedData["category"];
        $category->save();
        return response()->json($category);
    }
    public function getCategories(){
        $categories = Category::all();
        return response()->json($categories);
    }
}
