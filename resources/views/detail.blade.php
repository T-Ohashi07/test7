<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>商品詳細</title>

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
                    商品詳細
                </div>

                <div class="links">
                <h1>詳細</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>商品ID</th>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>メーカー</th>
                        <th>価格</th>
                        <th>在庫数</th>
                        <th>コメント</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>{{ $detail->id }}</td>
                        <td><img src="{{ asset('storage/image/'.$detail->img_path) }}" width="80" height="80"></td>
                        <td>{{ $detail->product_name }}</td>
                        <td>{{ $detail->company_name }}</td>
                        <td>{{ $detail->price }}</td>
                        <td>{{ $detail->stock }}</td>
                        <td>{{ $detail->comment }}</td>
                        <td><a href="{{ route('edit', ['id'=>$detail->id]) }}" class="btn btn-primary">詳細編集へ</a></td>
                        </tr>
                    </tbody>
                </table>
                
                </div>
            </div>
        </div>
    </body>
</html>
