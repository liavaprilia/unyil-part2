<x-app-layout>
    <div class="p-16 mx-auto max-w-7xl">
        <x-breadcrumbs></x-breadcrumbs>

        <div class="flex flex-col pt-10 space-y-8 md:flex-row md:space-x-20 md:space-y-0 animate-fade-in">
            <div class="relative flex-shrink-0 group">
                <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}"
                    class="aspect-[3/4] object-cover shadow-xl border-4 border-white group-hover:scale-105 transition-transform duration-300"
                    loading="lazy" width="300" height="400" />
            </div>

            <div class="pt-[2vh] flex-1">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 inria-sans-light drop-shadow-lg">
                    {{ $product->product_name }}</h1>
                <p class="p-4 mb-6 leading-relaxed text-gray-700 shadow-inner inria-sans-regular bg-white/60">
                    {{ nl2br($product->product_desc) }}
                </p>
                {{-- {{dd($product)}} --}}
                @if ($product->product_discount > 0)
                    <div class="flex items-center mb-4 space-x-2 product-price-container">
                        <h2 class="text-3xl font-bold text-black inria-sans-light">Rp.
                            {{ number_format($product->product_price - $product->product_discount, 0, ',', '.') }}</h2>
                        <span
                            class="text-lg text-gray-400 line-through">Rp. {{ number_format($product->product_price, 0, ',', '.') }}</span>
                        <span
                            class="text-sm font-semibold text-[#fb8763] bg-red-100 px-1 py-0.5 rounded">{{ round(($product->product_discount / $product->product_price) * 100, 2) }}%</span>
                    </div>
                @else
                    <h2
                        class="mb-6 text-2xl font-semibold text-gray-900 bg-clip-text inria-sans-regular product-price-container">
                        Rp.
                        {{ number_format($product->product_price, 0, ',', '.') }}</h2>
                @endif
                @hasrole('Admin')
                    <div class="flex items-center mb-6 space-x-3">
                        <span class="text-sm text-gray-500 inria-sans-regular">Tersedia: {{ $product->product_stock }} in
                            Stok</span>
                    </div>
                @endhasrole
                @hasrole('User')
                    <div class="flex items-center mb-6 space-x-3">
                        <span class="text-sm text-gray-500 inria-sans-regular">Tersedia: </span>
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 text-xl font-bold bg-gray-200 rounded hover:bg-gray-300"
                            onclick="decrementQty()">-</button>
                        <input id="quantity" name="quantity" type="number" min="1" value="1"
                            class="w-16 text-center border rounded focus:outline-none focus:ring-2 focus:ring-black" />
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 text-xl font-bold bg-gray-200 rounded hover:bg-gray-300"
                            onclick="incrementQty()">+</button>
                        <span class="text-sm text-gray-500 inria-sans-regular"> {{ $product->product_stock }} in
                            Stok</span>
                    </div>
                    <script>
                        function decrementQty() {
                            const qty = document.getElementById('quantity');
                            if (parseInt(qty.value) > 1) {
                                qty.value = parseInt(qty.value) - 1;
                                updatePriceDisplay();
                            } else {
                                Toast.fire({
                                    icon: 'warning',
                                    title: 'Jumlah pesanan tidak boleh kurang dari 1.'
                                });
                            }
                        }

                        function incrementQty() {
                            const qty = document.getElementById('quantity');
                            const maxQty = {{ $product->product_stock }};
                            if (parseInt(qty.value) < maxQty) {
                                qty.value = parseInt(qty.value) + 1;
                                updatePriceDisplay();
                            } else {
                                if ("{{ $product->product_stock }}" === "0") {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Stok habis. Silakan coba lagi nanti ketika produk tersedia kembali.'
                                    });
                                } else {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: `Stok hanya tersisa ${maxQty} item. Silakan ubah jumlah pesanan Anda.`
                                    });
                                }
                            }
                        }

                        function updatePriceDisplay() {
                            const qty = parseInt(document.getElementById('quantity').value) || 1;
                            const price = {{ $product->product_price }};
                            const discount = {{ $product->product_discount }};
                            const discountedPrice = price - discount;
                            const totalPrice = discountedPrice * qty;
                            const priceContainer = document.querySelector('.product-price-container');

                            if (discount > 0) {
                                const discountPercent = ((discount / price) * 100).toFixed(2);

                                priceContainer.innerHTML = `
            <h2 class="text-3xl font-bold text-black inria-sans-light">
                Rp. ${totalPrice.toLocaleString('id-ID')}
            </h2>
            <span class="text-lg text-gray-400 line-through">
                Rp. ${(price * qty).toLocaleString('id-ID')}
            </span>
            <span class="text-sm font-semibold text-[#fb8763] bg-red-100 px-1 py-0.5 rounded">
                -${Math.round(discountPercent)}%
            </span>
        `;
                            } else {
                                priceContainer.innerHTML = `
            <h2 class="text-2xl font-semibold text-gray-900 bg-clip-text inria-sans-regular">
                Rp. ${price.toLocaleString('id-ID')}
            </h2>
        `;
                            }
                        }


                        document.getElementById('quantity').addEventListener('change', function() {
                            if (parseInt(this.value) < 1 || isNaN(parseInt(this.value))) {
                                this.value = 1;
                            }
                        });
                    </script>
                    <button
                        class="flex items-center gap-2 px-6 py-3 text-lg font-bold text-black transition-all duration-300 shadow-lg btn-add-cart inria-sans-regular hover:bg-gray-900 hover:text-white">
                        <span class="text-2xl ti ti-brand-appgallery"></span>
                        Tambah ke Keranjang
                    </button>
                @endhasrole
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.btn-add-cart', function() {
                    let productId = `{{ $product->id_product }}`;
                    const quantity = $('#quantity').val();
                    $.ajax({
                        url: "{{ route('cart.update') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_product: productId,
                            quantity: quantity,
                            action: 'add'
                        },
                        success: function(res) {
                            updateCartBadge(res.data.total_items);
                            $('#quantity').val(1);
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message ||
                                    'Gagal menambah ke keranjang'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
