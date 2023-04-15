<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>商品一覧</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="css/origin.css">

        <script>
            function delete_alert(e){
            if(!window.confirm('本当に削除しますか？')){
                window.alert('キャンセルされました'); 
                return false;
                }
                document.deleteform.submit();
            };
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <p> {{ Auth::user()->name }}</p>
                        <a href="{{ url('/home') }}">ログアウト用仮ボタン（元HOME）</a>
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
                    商品一覧
                </div>

                <div class="links">
                <a href="{{ route('regist') }}">商品新規登録</a>
                    <div>
                    <form action="{{ route('searchlist') }}" method="GET">
                    <input type="text" name="keyword" value="{{ $keyword }}">
                    <label for="company_id">メーカー名</label>
                            <select name="company_id" id="company_id">
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                            </select>
                            @if($errors->has('company_id'))
                            <p>{{ $errors->first('company_id') }}</p>
                            @endif
                    <input type="submit" value="検索">
                    
                    </form>
                    </div>
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>COMPANY ID</th>
                        <th>PRODUCT NAME</th>
		                <th>PRICE</th>
		                <th>STOCK</th>
		                <th>COMMENT</th>
                        <th>IMAGE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->company_id }}</td>
                        <td>{{ $product->product_name }}</td>
		                <td>{{ $product->price }}</td>
		                <td>{{ $product->stock }}</td>
		                <td>{{ $product->comment }}</td>
                        <td><img src="{{ asset('storage/image/'.$product->img_path) }}" width="80" height="80"></td>
                        <td><a href="{{ route('detail', ['id'=>$product->id]) }}" class="btn btn-primary">詳細</a></td>
                        <td>
                            <form action="{{ route('destroy', ['id'=>$product->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" onClick="delete_alert(event);return false;">削除</button>
                            </form>
                         </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                </div>
            </div>
        </div>
    </body>
</html>
