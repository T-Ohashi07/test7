<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required | max:255',
            'company_id' => 'required',
            'price' => 'required | integer',
            'stock' => 'required | integer',
            'comment' => 'max:10000',
            //'img_path' => 'file',
        ];
    }

    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
    return [
        'product_name' => '商品名',
        'company_id' => '会社名',
        'price' => '価格',
        'stock' => '在庫数',
        'comment' => 'コメント',
        'img_path' => '商品画像',
    ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages() 
    {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'product_name.max' => ':attributeは:max字以内で入力してください。',
            'company_id.required' => ':attributeを選択してください。',
            'price.required' => ':attributeは必須項目です。',
            'price.integer' => ':attributeは整数を入力してください。',
            'stock.required' => ':attributeは必須項目です。',
            'stock.integer' => ':attributeは整数を入力してください。',
            'comment.max' => ':attributeは:max字以内で入力してください。',
            //'img_path.file' => ':attributeはファイルで選択してください。',
        ];
    }
}
