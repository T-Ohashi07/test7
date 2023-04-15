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

    public function showList() //未使用
    {
        // インスタンス生成
        $model = new Product();
        $products = $model->getList();
        $model = new Company();
        $companies = $model->getList();

        return view('productlist',compact('products','companies') );
    }

    public function searchList(Request $request)
    {
        $model = new Company();
        $companies = $model->getList();
        
        //検索処理
        $keyword = $request->input('keyword');
        $company_id = $request->input('company_id');

        $query = Product::query();

        if(!empty($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%")
            ->where('company_id', '=', "{$company_id}");
        }

        $products = $query->get();

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
        // productsテーブルから指定のIDのレコード1件を取得
        $product = Product::find($id);
        // レコードを削除
        $product->delete();
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
        $product = Product::findOrFail($id);

        $product->company_id = $request->input('company_id');
        $product->product_name = $request->input('product_name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        // 画像ファイルがアップロードされている場合
        if ($request->hasFile('img_path')) {
            $file = $request->file('img_path');
                    
            // 画像ファイルを保存するフォルダーのパスを指定する
            $path = storage_path('app/public/image/');
        
            // 画像ファイルのファイル名を生成する
            $filename = $file->getClientOriginalName();
        
            // 画像ファイルを指定のフォルダーに保存する
            $file->move($path, $filename);
        
            // ファイル名をDBに保存する
            $product->img_path = $filename;
        }

        $product->save();

        return redirect()->route('detail', $id)->with('success', '商品情報を更新しました。');
    }

}

