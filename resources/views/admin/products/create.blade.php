<x-app-layout>
    <div class="p-16 mx-auto max-w-7xl">
        <x-breadcrumbs></x-breadcrumbs>

        <form action="{{ $routeForm }}" method="POST" enctype="multipart/form-data"
            data-function-callback="afterFormSubmit"
            class="flex flex-col pt-10 space-y-8 md:flex-row md:space-x-20 md:space-y-0 animate-fade-in base-form"
            onsubmit="event.preventDefault()">
            @csrf

            @isset($product)
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="{{ $product->id_product }}">
            @endisset

            {{-- Gambar Preview --}}
            <div class="relative flex-shrink-0 group w-[300px] form-group">
                <img id="preview-image"
                    src="{{ isset($product) ? Storage::url($product->product_image) : 'https://placehold.co/300x400' }}"
                    alt="Preview"
                    class="aspect-[3/4] object-cover shadow-xl border-4 border-white group-hover:scale-105 transition-transform duration-300"
                    loading="lazy" width="300" height="400" />

                <input type="file" name="product_image" id="product_image" accept="image/*"
                    class="block w-full mt-4 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-800" />
                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
            </div>

            {{-- Form --}}
            <div class="pt-[2vh] flex-1 space-y-6">
                <div class="form-group">
                    <label for="product_name" class="block text-sm font-semibold text-gray-800">Nama Produk</label>
                    <input type="text" name="product_name" id="product_name"
                        @isset($product)
                            value="{{ $product->product_name }}"
                        @endisset
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-black"
                        placeholder="Contoh: Baju Batik">
                    {{-- im using ajax this invalid feedback --}}
                    <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="product_desc" class="block text-sm font-semibold text-gray-800">Deskripsi</label>
                    <textarea name="product_desc" id="product_desc" rows="5"
                        class="w-full px-4 py-3 mt-1 border border-gray-300 rounded shadow-inner focus:outline-none focus:ring-2 focus:ring-black"
                        placeholder="Tulis deskripsi produk di sini...">
@isset($product)
{{ $product->product_desc }}
@endisset
</textarea>
                    <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                </div>

                <div class="flex items-end gap-4 md:gap-8">
                    <div class="flex flex-col form-group">
                        <label for="product_stock" class="mb-1 text-sm font-semibold text-gray-800">Stok:</label>
                        <div class="flex items-center space-x-2">
                            <button type="button"
                                class="flex items-center justify-center w-8 h-8 text-xl font-bold bg-gray-200 rounded hover:bg-gray-300"
                                onclick="decrementQty()">-</button>
                            <input id="product_stock" name="product_stock" type="number" min="0"
                                value="{{ isset($product) ? $product->product_stock : 1 }}"
                                class="w-16 text-center border rounded focus:outline-none focus:ring-2 focus:ring-black" />
                            <button type="button"
                                class="flex items-center justify-center w-8 h-8 text-xl font-bold bg-gray-200 rounded hover:bg-gray-300"
                                onclick="incrementQty()">+</button>
                        </div>
                        <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                    </div>
                    <div class="flex-1 form-group">
                        <label for="product_weight" class="block mb-1 text-sm font-semibold text-gray-800">Berat
                            (gram)</label>
                        <input type="number" name="product_weight" id="product_weight"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-black"
                            placeholder="250 gram" min="1" />
                        <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                    </div>
                    <div class="flex-1 form-group">
                        <label for="product_price" class="block mb-1 text-sm font-semibold text-gray-800">Harga
                            (Rp)</label>
                        <input type="text" name="product_price" id="product_price"
                            class="w-full px-4 py-2 border border-gray-300 rounded rupiah focus:outline-none focus:ring-2 focus:ring-black"
                            placeholder="1.000" autocomplete="off">
                        <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                    </div>
                    <div class="flex-1 form-group">
                        <label for="product_discount" class="block mb-1 text-sm font-semibold text-gray-800">Diskon
                            (Rp)</label>
                        <input type="text" name="product_discount" id="product_discount" data-min="0" value="{{ isset($product) ? number_format($product->product_discount, 0, ',', '.') : '0' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded rupiah focus:outline-none focus:ring-2 focus:ring-black"
                            placeholder="0" autocomplete="off">
                        <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 text-lg font-bold text-white transition-all duration-300 bg-black rounded shadow hover:bg-gray-900">
                        <span class="text-2xl ti ti-circle-plus"></span>
                        {{ $buttonText }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Script Preview Gambar --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                @isset($product)
                    // using jquery to set the initial image preview
                    $('#preview-image').attr('src', '{{ Storage::url($product->product_image) }}');
                    $('#product_name').val('{{ $product->product_name }}');
                    $('#product_desc').val('{{ $product->product_desc }}');
                    $('#product_stock').val('{{ $product->product_stock }}');
                    $('#product_price').val('{{ number_format($product->product_price, 0, ',', '.') }}');
                    $('#product_discount').val('{{ number_format($product->product_discount ?? 0, 0, ',', '.') }}');
                    $('#product_weight').val('{{ $product->product_weight }}');

                    $('.rupiah').each(function() {
                        if ($(this).val().startsWith('0')) {
                            $(this).val($(this).val().substring(1));
                        }
                        console.log('Applying mask to input:', this);
                        const input = $(this);
                        const min = input.data('min') || 1;
                        const maskOptions = {
                            mask: 'Rp num',
                            blocks: {
                                num: {
                                    mask: IMask.MaskedNumber,
                                    thousandsSeparator: '.',
                                    radix: ',',
                                    scale: 0,
                                    signed: false,
                                    padFractionalZeros: false,
                                    normalizeZeros: true,
                                    min: min, // <-- minimum 1 ensures no 0
                                    max: 999999999999,
                                    unmask: true,
                                    prepare: function(value) {
                                        // Block input if it's '0' at start
                                        if (value === '0') return '';
                                        return value;
                                    }
                                }
                            }
                        };
                        const maskedInput = IMask(input[0], maskOptions);
                        // maskedInput.on('accept', function() {
                        //     const value = maskedInput.unmaskedValue;
                        //     input.val(value);
                        // });
                    });


                @endisset
            });

            const imageInput = document.getElementById('product_image');
            const previewImage = document.getElementById('preview-image');

            imageInput.addEventListener('change', function() {
                const [file] = this.files;
                if (file) {
                    previewImage.src = URL.createObjectURL(file);
                }
            });

            function decrementQty() {
                const qty = document.getElementById('product_stock');
                if (parseInt(qty.value) > 1) qty.value = parseInt(qty.value) - 1;
            }

            function incrementQty() {
                const qty = document.getElementById('product_stock');
                qty.value = parseInt(qty.value) + 1;
            }
            document.getElementById('product_stock').addEventListener('change', function() {
                if (parseInt(this.value) < 1 || isNaN(parseInt(this.value))) {
                    this.value = 1;
                }
            });

            function afterFormSubmit(response) {
                if (response.data.redirect_url) {
                    setTimeout(() => {
                        window.location.href = response.data.redirect_url;
                    }, 2000);
                }
            }

            // const priceInput = document.getElementById('price');
            // priceInput.addEventListener('input', function(e) {
            //     let value = this.value.replace(/\D/g, '');
            //     if (value === '' || parseInt(value) < 1000) {
            //         value = '1000';
            //     }
            //     // Format with dot as thousand separator
            //     this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            // });
            // priceInput.addEventListener('blur', function() {
            //     // Prevent 0 or less than 1000
            //     let value = this.value.replace(/\D/g, '');
            //     if (parseInt(value) < 1000 || isNaN(parseInt(value))) {
            //         this.value = '1.000';
            //     }
            // });

            const weightInput = document.getElementById('product_weight');
            weightInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        </script>
    @endpush
</x-app-layout>
