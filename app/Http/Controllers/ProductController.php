<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;



class ProductController extends Controller
{
    
    // ログインなしだとログイン画面へ返す
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showList() 
    {
        // インスタンス生成
        $model = new Product();
        $products = $model->getList();

        return view('productlist', ['products' => $products]);
    }
}
