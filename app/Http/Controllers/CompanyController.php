<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // ログインなしだとログイン画面へ返す
    public function __construct()
    {
        $this->middleware('auth');
    }
}
