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

    public function updateProduct($request)
    {
        $this->company_id = $request->input('company_id');
        $this->product_name = $request->input('product_name');
        $this->price = $request->input('price');
        $this->stock = $request->input('stock');
        $this->comment = $request->input('comment');

        if ($request->hasFile('img_path')) {
            $file = $request->file('img_path');
                    
            // 画像ファイルを保存するフォルダーのパスを指定する
            $path = storage_path('app/public/image/');
        
            // 画像ファイルのファイル名を生成する
            $filename = $file->getClientOriginalName();
        
            // 画像ファイルを指定のフォルダーに保存する
            $file->move($path, $filename);
        
            // ファイル名をDBに保存する
            $this->img_path = $filename;
        }

        $this->save();
    }

    public function searchList($keyword, $company_id)
    {
        $query = Product::query();

        if (!empty($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%")
                  ->where('company_id', '=', "{$company_id}");
        }

        $products = $query->get();

        return $products;
    }

}
