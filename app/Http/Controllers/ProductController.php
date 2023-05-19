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
        $price_min = $request->input('price_min');
        $price_max = $request->input('price_max');
        $stock_min = $request->input('stock_min');
        $stock_max = $request->input('stock_max');
        $sort_key = $request->input('sort_key'); 
        $sort_order = $request->input('sort_order');

        $model = new Product();
        $products = $model->search($keyword, $company_id,$price_min,$price_max,$stock_min,$stock_max,$sort_key, $sort_order);
        
        if($request->ajax()) {
            return response()->json([
                'view' => view('tbody')->with(compact('products'))->render()
            ])->header('Content-Type', 'application/json; charset=utf-8');
        } else {
            return view('productlist', compact('products', 'keyword','companies'));
        }
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

    public function destroy(Request $request,$id)
    {
        if ($request->ajax()) {
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
                return response()->json(['message' => '削除に失敗しました。'], 500);
            }
    
            // 削除が成功したことを返す
            return response()->json(['message' => '削除が完了しました。']);
        } else {
            return back();
        }
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

