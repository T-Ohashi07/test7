<!-- resources/views/productlist.blade.php -->
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

        <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function() {
            // 商品一覧を非同期で取得する関数
            function getProductList() {
                var url = "{{ route('searchlist') }}";
                var keyword = $('input[name="keyword"]').val();
                var company_id = $('select[name="company_id"]').val();
                var price_min = $('input[name="price_min"]').val();
                var price_max = $('input[name="price_max"]').val();
                var stock_min = $('input[name="stock_min"]').val();
                var stock_max = $('input[name="stock_max"]').val();

                // 検索条件が入力されている場合は、URLにパラメータを追加する
                if (keyword && company_id) {
                    url = "{{ route('searchlist') }}" + "?keyword=" + encodeURIComponent(keyword) + "&company_id=" + encodeURIComponent(company_id);
                }

                if (price_min) {
                url += (url.includes('?') ? '&' : '?') + 'price_min=' + encodeURIComponent(price_min);
                }
                if (price_max) {
                    url += (url.includes('?') ? '&' : '?') + 'price_max=' + encodeURIComponent(price_max);
                }

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $('#product_table').html(response.view);
                    },
                    error: function() {
                        alert("商品一覧の取得に失敗しました。");
                    }
                    });
                }

            // ページ読み込み時に商品一覧を取得する
            getProductList('','');

            // 検索フォームのsubmitイベントを非同期で送信する
            $('form#search_form').on('submit', function(e) {
                e.preventDefault();
                getProductList();
            });
            });

            //ソート処理
            $(function() {
            $('th a').click(function() {
                event.preventDefault();
                var url = "{{ route('searchlist') }}";
                var keyword = $('input[name="keyword"]').val();
                var company_id = $('select[name="company_id"]').val();
                var price_min = $('input[name="price_min"]').val();
                var price_max = $('input[name="price_max"]').val();
                var stock_min = $('input[name="stock_min"]').val();
                var stock_max = $('input[name="stock_max"]').val();
                // クリックされたヘッダーのカラム名とソート順を取得
                var sortKey = $(this).data('sort-key');
                var sortOrder = $(this).hasClass('asc') ? 'desc' : 'asc';

                // 検索条件が入力されている場合は、URLにパラメータを追加する
                if (sortKey) {
                    url += (url.includes('?') ? '&' : '?') + 'sort_key=' + encodeURIComponent(sortKey);
                }
                if (sortOrder) {
                    url += (url.includes('?') ? '&' : '?') + 'sort_order=' + encodeURIComponent(sortOrder);
                }

                // 矢印アイコンの色を変更
                $('th a').removeClass('asc desc');
                $(this).addClass(sortOrder);

                // Ajaxリクエストを送信し、並び替えたデータを取得
                $.ajax({
                type: "GET",
                url: url,
                
                success: function(response) {
                    $('#product_table').html(response.view);
                },
                error: function() {
                    alert("商品一覧の取得に失敗しました。");
                }
                });

                return false;
            });
            });

            // 削除ボタンのクリックイベントを設定
            $(document).on('click', '.delete-button', function() {
                var form = $(this).closest('form');

                if (confirm('本当に削除しますか？')) {
                    $.ajax({
                        type: 'POST',
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function(response) {
                            alert(response.message);
                            // 削除が成功した場合は該当の行を削除
                            form.closest('tr').remove();
                        },
                        error: function() {
                            alert('削除に失敗しました。');
                        }
                    });
                }
            });
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
                    <form method="GET" id="search_form">
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
                            <label for="price_min">価格（下限）</label>
                    <input type="number" name="price_min" id="price_min">
                    <label for="price_max">価格（上限）</label>
                    <input type="number" name="price_max" id="price_max">
                    <label for="stock_min">在庫数（下限）</label>
                    <input type="number" name="stock_min" id="stock_min">
                    <label for="stock_max">在庫数（上限）</label>
                    <input type="number" name="stock_max" id="stock_max">
                    <input type="submit" value="検索">
                    
                    </form>
                    </div>
                <table >
                    <thead>
                    <tr>
                        <th><a href="#" data-sort-key="id">ID</a> <i class="fas fa-sort"></i></th>
                        <th><a href="#" data-sort-key="company_id">COMPANY ID</a> <i class="fas fa-sort"></i></th>
                        <th><a href="#" data-sort-key="product_name">商品名</a> <i class="fas fa-sort"></i></th>
		                <th><a href="#" data-sort-key="price">価格</a> <i class="fas fa-sort"></i></th>
		                <th><a href="#" data-sort-key="stock">在庫数</a> <i class="fas fa-sort"></i></th>
		                <th>COMMENT</th>
                        <th>IMAGE</th>
                    </tr>
                    </thead>
                    <tbody id=product_table> 
                        <!-- ここにテーブルの内容が入る -->                  
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </body>
</html>
