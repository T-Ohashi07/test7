<?php

namespace App\Http\Controllers;

use App\Product;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // ログインなしだとログイン画面へ返す
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Product_idを取得
        $productId = $request->input('product_id');

        // Productテーブルで検索
        $product = Product::find($productId);

        if ($product) {
            // 'stock'の値が0であればエラーレスポンスを返す
            if ($product->stock === 0) {
                return response()->json(['error' => '在庫がありません。'], 400);
            }

            // トランザクション開始
            DB::beginTransaction();

            try {
                // Salesテーブルにレコードを追加
                $sale = new Sale();
                $sale->product_id = $productId;
                $sale->save();

                // Productテーブルの'stock'カラムの値を1減算
                $product->stock -= 1;
                $product->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => '処理に失敗しました。'], 500);
            }

            return response()->json(['message' => '処理が完了しました。']);
        }

        // Productが見つからなかった場合のエラーレスポンスを返す
        return response()->json(['error' => '商品が見つかりません。'], 404);
    }

}
