<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    // ログインなしだとログイン画面へ返す
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showRegist() {
         // インスタンス生成
         $model = new Company();
         $companies = $model->getList();
 
         return view('regist', ['companies' => $companies]);
    }
}
