@foreach ($products as $product)
    @auth
        @if (auth()->user()->hasRole('Super Admin'))
            <a href="{{ url('admin/products', $product->id_product) }}">
            @else
                <a href="{{ route('product-detail', $product->id_product) }}">
        @endif
    @endauth
    @guest
        <a href="{{ route('product-detail', $product->id_product) }}">
        @endguest
        {{-- <a href="{{ route('product-detail', $product->id_product) }}"> --}}
        <div class="border border-gray-200 shadow-lg" style="width: 240px;">
            <img src="{{ asset('storage/' . $product->product_image) }}" alt="" class="w-full"
                style="width: 320px; height: 290px; object-fit: cover;">
            <div class="p-3">
                <h3 class="text-lg truncate inria-sans-regular">{{ $product->product_name }}</h3>
                @if ($product->product_discount > 0)
                    <h3 class="text-xl text-black inria-sans-light">
                        Rp{{ number_format($product->product_price - $product->product_discount, 0, ',', '.') }}</h3>
                    <div class="flex items-center space-x-2">
                        <span
                            class="text-sm text-gray-400 line-through">Rp{{ number_format($product->product_price, 0, ',', '.') }}</span>
                        <span
                            class="text-sm font-semibold text-[#fb8763] bg-red-100 px-1 py-0.5 rounded">{{ round(($product->product_discount / $product->product_price) * 100, 2) }}%</span>
                    </div>
                @else
                    <h3 class="text-2xl inria-sans-light mb-5">Rp{{ number_format($product->product_price, 0, ',', '.') }}
                    </h3>
                @endif
                @hasrole('User')
                    <div class="mx-2 mb-1">
                        <button
                            class="w-full px-2 mt-2 font-semibold text-white transition-colors duration-200 bg-black shadow hover:bg-black"
                            data-id="{{ $product->id_product }}">
                            Tambahkan ke Keranjang
                        </button>
                    </div>
                @endhasrole
            </div>
        </div>
    </a>
@endforeach
