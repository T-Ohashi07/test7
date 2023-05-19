<!-- 一覧表示用 -->
                            
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
            <button type="button" class="btn btn-danger delete-button" >削除</button>
            </form>
        </td>
    </tr>
@endforeach
