<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    protected $fillable = [
        'id',
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
        //"created_at",
        //"updated_at",
    ];

    protected $dates =  ['created_at', 'updated_at'];

    public function getList() {
        // productsテーブルからデータを取得
        $products = DB::table('products')->get();

        return $products;
    }

    public function registProduct($data) {
        // アップロードされたファイル名を取得
        $file_name = $data->file('img_path')->getClientOriginalName();
        
        // 取得したファイル名で保存
        // storage/app/public/image/
        $data->file('img_path')->storeAs('public/image' ,$file_name);

        // 登録処理
        DB::table('products')->insert([
            'company_id' => $data->company_id,
            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $file_name,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function editProduct($data) {
         // アップロードされたファイル名を取得
         $file_name = $data->file('img_path')->getClientOriginalName();
        
         // 取得したファイル名で保存
         // storage/app/public/image/
         $data->file('img_path')->storeAs('public/image' ,$file_name);

        // 更新処理
        DB::table('products')->update([
            'company_id' => $data->company_id,
            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $file_name,
            'updated_at' => now()
        ]);
    }

}
