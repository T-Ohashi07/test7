<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>商品新規登録</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="css/origin.css">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/productlist') }}">戻る</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    商品新規登録
                </div>

                <div class="container">
                    <div class="row">
                        <h1>Post Form</h1>
                        <form action=" {{ route('submit') }}" method="post" enctype="multipart/form-data">
                        @csrf 

                        <div class="form-group">
                            <label for="product_name">商品名</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="商品名" value="{{ old('product_name') }}">
                            @if($errors->has('product_name'))
                            <p>{{ $errors->first('product_name') }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="company_id">メーカー名</label>
                            <select name="company_id" id="company_id">
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                            </select>
                            @if($errors->has('company_id'))
                            <p>{{ $errors->first('company_id') }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="price">価格</label>
                            <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}">
                            @if($errors->has('price'))
                            <p>{{ $errors->first('price') }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="stock">在庫数</label>
                            <input type="text" class="form-control" id="stock" name="stock" value="{{ old('stock') }}">
                            @if($errors->has('stock'))
                            <p>{{ $errors->first('stock') }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="comment">コメント</label>
                            <textarea class="form-control" id="comment" name="comment" placeholder="コメント">{{ old('comment') }}</textarea>
                            @if($errors->has('comment'))
                            <p>{{ $errors->first('comment') }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="img_path">商品画像</label>
                            <input type="file" class="form-control" id="img_path" name="img_path" value="{{ old('img_path') }}">
                            @if($errors->has('img_path'))
                            <p>{{ $errors->first('img_path') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-default">登録</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
