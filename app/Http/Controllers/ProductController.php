<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    
    // ログインなしだとログイン画面へ返す
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function searchList(Request $request)
    {
        $model = new Company();
        $companies = $model->getList();
        
        //検索処理
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');

        $model = new Product();
        $products = $model->searchList($keyword, $company_id);

        return view('productlist', compact('products', 'keyword','companies'));
    }

    public function registSubmit(ProductRequest $request) 
    {

        // トランザクション開始
        DB::beginTransaction();
    
        try {
            // 登録処理呼び出し
            $model = new Product();
            $model->registProduct($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
    
        // 登録処理が完了したらregistにリダイレクト
        return redirect(route('regist'));
    }

    public function showDetail($id) 
    {
        $detail = Product::find($id);

        return view('detail', compact('detail'));
    }

    public function destroy($id)
    {
        // トランザクション開始
        DB::beginTransaction();

        try {
            // productsテーブルから指定のIDのレコード1件を取得
            $product = Product::find($id);
            // レコードを削除
            $product->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }
        // 削除したら一覧画面にリダイレクト
        return redirect(route('searchlist'));
    }
    
  
    // 商品情報の編集画面を表示する
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('edit', compact('product', 'companies'));
    }

    // 商品情報の編集画面からの更新処理を行う
    public function update(ProductRequest $request, $id)
    {
        // トランザクション開始
        DB::beginTransaction();
        
        try {
            $product = Product::findOrFail($id);
            $product->updateProduct($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }

        return redirect()->route('detail', $id)->with('success', config('messages.success_update'));
    }

}

