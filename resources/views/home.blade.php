<x-app-layout>
    <div class="bg-[#F7F6F0]">
        <div class="flex justify-between p-6 mx-auto max-w-7xl">
            <div class="flex p-4">
                <img src="{{ asset('woman1.png') }}" class="w-full" style="width: 150px; height: 416px; object-fit: cover;">
                <img src="{{ asset('woman2.png') }}" class="w-full" style="width: 150px; height: 416px; object-fit: cover;">
            </div>
            <div class="flex flex-col items-center justify-center p-4 text-center">
                <h1 class="mb-6 text-4xl inria-sans-light">Selamat Datang di {{ config('app.name') }} </h1>
                <p class="mb-6 text-lg inria-sans-regular">Temukan berbagai produk berkualitas dengan harga terjangkau.</p>
                {{-- <a href=""
                    class="inline-flex items-center px-4 py-2 text-white transition-colors duration-200 bg-black rounded hover:bg-gray-800">
                    Lihat Produk
                </a> --}}
            </div>
            <div class="flex p-4">
                <img src="{{ asset('woman2.png') }}" class="w-full" style="width: 150px; height: 416px; object-fit: cover;">
                <img src="{{ asset('woman1.png') }}" class="w-full" style="width: 150px; height: 416px; object-fit: cover;">
            </div>
        </div>

    </div>
    <div class="p-6 mx-auto max-w-7xl">
        <h1 class="mb-6 text-4xl inria-sans-light">Produk</h1>

        <!-- Product Grid -->

        <div id="product-container"
            class="grid grid-cols-1 gap-4 product-grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        </div>


        <!-- Product Grid -->
    </div>

    <div class="flex justify-center mx-auto mt-8">
        <a id="view-more-btn" href="javascript:void(0)" style="display: none;"
            class="px-4 py-2 text-xl text-black transition-colors mb-5 duration-200 bg-white border border-black shadow inria-sans-light hover:bg-black hover:text-white">
            View More
        </a>
    </div>

    @push('scripts')
        <script>
            let page = 1;
            let loading = false;

            function showLoading() {
                const loadingHtml = `
                    <div id="loading-products" class="col-span-full flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black"></div>
                        <span class="ml-3 text-lg inria-sans-regular">Memuat produk...</span>
                    </div>
                `;
                $('#product-container').append(loadingHtml);
            }

            function hideLoading() {
                $('#loading-products').remove();
            }

            function showError(message) {
                const errorHtml = `
                    <div id="error-products" class="col-span-full flex justify-center items-center py-8 text-red-600">
                        <span class="text-lg inria-sans-regular">${message}</span>
                    </div>
                `;
                $('#product-container').append(errorHtml);
            }

            function hideError() {
                $('#error-products').remove();
            }

            function loadProducts(reset = false) {
                if (loading) return;
                loading = true;
                $('#view-more-btn').hide();
                hideError();
                showLoading();

                $.get("{{ route('home') }}", {
                    page
                }, function(res) {
                    if (reset) $('#product-container').html('');
                    hideLoading();
                    $('#product-container').append(res.html);
                    loading = false;
                    if (!res.hasMore) {
                        $('#view-more-btn').hide();
                    } else {
                        $('#view-more-btn').show();
                    }
                }).fail(function() {
                    hideLoading();
                    showError('Gagal memuat produk. Silakan coba lagi.');
                    loading = false;
                });
            }

            $(document).ready(function() {
                loadProducts(true);

                $('#view-more-btn').on('click', function() {
                    page++;
                    loadProducts();
                });

                // Add to cart AJAX
                $('#product-container').on('click', '.btn-add-cart', function() {
                    let productId = $(this).data('id');
                    const action = $(this).data('action') || 'add';
                    $.ajax({
                        url: "{{ route('cart.update') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_product: productId,
                            quantity: 1,
                            action: action
                        },
                        success: function(res) {
                            updateCartBadge(res.data.total_items);
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