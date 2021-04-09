<div>
    <!-- AE nhớ thêm thông tin order vào cho đẹp nhé -->
    @foreach ($order->products as $product)
        <p>{{ $product->name }}</p>
    @endforeach
</div>
