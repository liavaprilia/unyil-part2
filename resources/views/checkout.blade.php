<x-app-layout>
    <style>
        .shipping-animate {
            opacity: 0;
            transform: translateX(-40px);
            animation: shippingFadeIn 0.5s cubic-bezier(.4, 2, .3, 1) forwards;
        }

        @keyframes shippingFadeIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .disabled-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.6);
            z-index: 10;
            pointer-events: all;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .address-disabled {
            pointer-events: none;
            opacity: 0.6;
            position: relative;
        }
    </style>
    <form class="p-5 base-form" action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data"
        data-function-callback="afterFormSubmit">
        @csrf
        <div class="flex gap-5">
            <div class="flex flex-col flex-1 gap-8">
                <div class="flex-1 bg-white border border-black">
                    <div class="px-4">
                        <h2 class="my-2 text-2xl font-bold tracking-tight">Alamat Pengiriman</h2>
                    </div>
                    <div class="border-b border-black"></div>
                    <div class="px-4 py-4 space-y-3 address-disabled" id="address-fields-wrapper">
                        <div class="disabled-overlay" id="address-disabled-overlay">
                            <svg class="w-5 h-5 text-gray-600 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="form-group flex-1">
                                <label for="recipient_name"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Nama
                                    Penerima</label>
                                <input type="text" id="recipient_name" name="recipient_name"
                                    placeholder="Nama penerima, contoh: Purna Widodo"
                                    value="{{ old('recipient_name', ($user->shipping_recipient_name ?? '')) }}"
                                    class="w-full px-2 py-1 text-sm transition bg-transparent border-b border-gray-400 focus:outline-none focus:border-black">
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                            <label class="ml-4 inline-flex items-center gap-2 text-sm select-none">
                                <input type="checkbox" class="accent-black" name="save_address" id="save_address" value="1" {{ ($user->shipping_recipient_name ?? false) ? 'checked' : 'checked' }}>
                                <span>Simpan alamat ini untuk pengiriman selanjutnya</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="phone"
                                class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Nomor
                                Telepon</label>
                            <input type="tel" id="phone" name="phone"
                                placeholder="Masukkan nomor telepon, contoh: 08123456789 | +628123456789"
                                value="{{ old('phone', ($user->shipping_phone ?? '')) }}"
                                class="w-full px-2 py-1 text-sm transition bg-transparent border-b border-gray-400 focus:outline-none focus:border-black">
                            <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="address"
                                class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Alamat</label>
                            <textarea id="address" name="address" rows="2"
                                placeholder="Masukkan alamat lengkap, contoh: Jl. Raya No. 123, RT 01/RW 02, Kelurahan Sukamaju"
                                class="w-full px-2 py-1 text-sm transition bg-transparent border-b border-gray-400 resize-none focus:outline-none focus:border-black">{{ old('address', ($user->shipping_address ?? '')) }}</textarea>
                            <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                        </div>
                        <div class="flex gap-3">
                            <div class="flex-1 form-group">
                                <label for="postal_code"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Kode
                                    Pos</label>
                                <input type="text" id="postal_code" name="postal_code"
                                    placeholder="Masukkan kode pos, contoh: 57462"
                                    value="{{ old('postal_code', ($user->shipping_postal_code ?? '')) }}"
                                    class="w-full px-2 py-1 text-sm transition bg-transparent border-b border-gray-400 focus:outline-none focus:border-black">
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                            <div class="flex-1 form-group">
                                <label for="province"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Provinsi</label>
                                <select id="province" name="province" class="w-full select2" data-saved="{{ $user->shipping_province ?? '' }}">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                            <div class="flex-1 form-group">
                                <label for="country"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Negara</label>
                                <input type="text" id="country" name="country" disabled readonly
                                    class="w-full px-2 py-1 text-sm transition bg-transparent border-b border-gray-400 focus:outline-none focus:border-black"
                                    value="Indonesia">
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <input id="from_city_id" name="from_city_id" type="hidden" value="190">
                            <div class="flex-1 form-group">
                                <label for="city"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Kota</label>
                                <select id="city" name="city" class="w-full select2" data-saved-text="{{ $user->shipping_city ?? '' }}">
                                    <option value="" disabled selected>Pilih Kota</option>
                                    <!-- Kota akan diisi via JS -->
                                </select>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                            <input id="to_city_id" name="to_city_id" type="hidden" value="">
                            <div class="flex-1 form-group">
                                <label for="district"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Kecamatan</label>
                                <select id="district" name="district" class="w-full select2" data-saved-text="{{ $user->shipping_district ?? '' }}">
                                    <option value="" disabled selected>Pilih Kecamatan
                                    </option>
                                    <!-- Kecamatan akan diisi via JS -->
                                </select>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                            <div class="flex-1 form-group">
                                <label for="sub_district"
                                    class="block mb-1 text-xs font-semibold tracking-wider text-gray-700 uppercase">Kelurahan</label>
                                <select id="sub_district" name="sub_district" class="w-full select2" data-saved-text="{{ $user->shipping_subdistrict ?? '' }}">
                                    <option value="" disabled selected>Pilih Kelurahan
                                    </option>
                                    <!-- Kecamatan akan diisi via JS -->
                                </select>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-white border border-black">
                    <div class="px-4">
                        <h2 class="my-2 text-2xl font-bold tracking-tight">Shipping Method</h2>
                    </div>
                    <div class="border-b border-black"></div>
                    <div id="shipping-methods" class="px-4 py-4 space-y-4 form-group">
                        {{-- <div class="flex items-center gap-3">
                        <input type="radio" id="jne" name="shipping_method" value="jne" class="accent-black" checked>
                        <img src="" alt="JNE" class="object-contain w-10 h-6">
                        <label for="jne" class="flex-1 cursor-pointer">
                            <span class="block font-semibold">JNE Regular</span>
                            <span class="block text-xs text-gray-500">2-3 days</span>
                        </label>
                        <span class="font-bold text-black whitespace-nowrap">Rp.10,000</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="radio" id="jnt" name="shipping_method" value="jnt" class="accent-black">
                        <img src="https://2.bp.blogspot.com/-VLat0qXitdo/W-gk_W0f-nI/AAAAAAAAE8c/847Tyk62QY8Y8oA-Xsx1B1ntXt0qWl0RgCLcBGAs/w250-h170-c/Logo%2BJ%2B%2526%2BT%2BVector%2BPNG%2BHD.png" alt="J&T" class="object-contain w-10 h-6">
                        <label for="jnt" class="flex-1 cursor-pointer">
                            <span class="block font-semibold">J&T Express</span>
                            <span class="block text-xs text-gray-500">1-2 days</span>
                        </label>
                        <span class="font-bold text-black whitespace-nowrap">Rp.12,000</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="radio" id="sicepat" name="shipping_method" value="sicepat" class="accent-black">
                        <img src="https://kiriminaja.com/assets/home/logo-sicepat.png" alt="SiCepat" class="object-contain w-10 h-6">
                        <label for="sicepat" class="flex-1 cursor-pointer">
                            <span class="block font-semibold">SiCepat</span>
                            <span class="block text-xs text-gray-500">1-3 days</span>
                        </label>
                        <span class="font-bold text-black whitespace-nowrap">Rp.11,000</span>
                    </div> --}}

                        {{-- Empty state tolong isi alamat pengiriman terlebih dahulu --}}
                        <div class="flex items-center gap-3 shipping-animate">
                            <input type="radio" id="no_shipping" name="shipping_method" value="no_shipping"
                                class="accent-black" checked>
                            <label for="no_shipping" class="flex-1 cursor-pointer">
                                <span class="block font-semibold">Tidak ada pengiriman</span>
                                <span class="block text-xs text-gray-500">Silakan isi alamat pengiriman terlebih
                                    dahulu</span>
                            </label>
                            <span class="font-bold text-black whitespace-nowrap">Rp.0</span>
                        </div>
                        <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                    </div>
                </div>
                <div class="flex-1 bg-white border border-black">
                    <div class="px-4">
                        <h2 class="my-2 text-2xl font-bold tracking-tight">Payment</h2>
                    </div>
                    <div class="border-b border-black"></div>
                    <div x-data="{ tab: 'bri' }" class="px-4 py-4">
                        <div class="flex mb-4 border-b border-gray-300">
                            <button
                                :class="tab === 'bri' ? 'border-black text-black' : 'border-transparent text-gray-500'"
                                @click="tab = 'bri'"
                                class="px-4 py-2 font-semibold transition-colors border-b-2 focus:outline-none"
                                type="button">
                                Bank BRI
                            </button>
                            <button
                                :class="tab === 'mandiri' ? 'border-black text-black' : 'border-transparent text-gray-500'"
                                @click="tab = 'mandiri'"
                                class="px-4 py-2 font-semibold transition-colors border-b-2 focus:outline-none"
                                type="button">
                                Bank Mandiri
                            </button>
                            <button
                                :class="tab === 'bca' ? 'border-black text-black' : 'border-transparent text-gray-500'"
                                @click="tab = 'bca'"
                                class="px-4 py-2 font-semibold transition-colors border-b-2 focus:outline-none"
                                type="button">
                                Bank BCA
                            </button>
                        </div>
                        <div class="space-y-4">
                            {{-- <div class="flex items-center gap-3 form-group" x-show="tab === 'bri'">
                                <input type="radio" id="bank_bri" name="payment_method" value="bank_bri"
                                    class="accent-black" :checked="tab === 'bri'">
                                <label for="bank_bri" class="flex-1 cursor-pointer">
                                    <span class="block font-semibold">Bank bri</span>
                                    <span class="block text-xs text-gray-500">Transfer ke rekening bri</span>
                                </label>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div> --}}
                            <x-BankCard tab="bri" BankName="Bank BRI" BankTag="Transfer ke rekening BRI"
                                BankNumber="061201000042560" BankRecipient="Purna Widodo" id="bank_bri" />
                            {{-- <div class="flex items-center gap-3 form-group" x-show="tab === 'mandiri'">
                                <input type="radio" id="bank_mandiri" name="payment_method" value="bank_mandiri"
                                    class="accent-black" :checked="tab === 'mandiri'">
                                <label for="bank_mandiri" class="flex-1 cursor-pointer">
                                    <span class="block font-semibold">Bank Mandiri</span>
                                    <span class="block text-xs text-gray-500">Transfer ke rekening Mandiri</span>
                                </label>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div> --}}
                            <x-BankCard tab="bca" BankName="Bank BCA" BankTag="Transfer ke rekening BCA"
                                BankNumber="0152516299" BankRecipient="Murdiyati" id="bank_bca" />
                            {{-- <div class="flex items-center gap-3" x-show="tab === 'bca'">
                                <input type="radio" id="bank_bca" name="payment_method" value="bank_bca"
                                    class="accent-black" :checked="tab === 'bca'">
                                <label for="bank_bca" class="flex-1 cursor-pointer">
                                    <span class="block font-semibold">Bank BCA</span>
                                    <span class="block text-xs text-gray-500">Transfer ke rekening BCA</span>
                                </label>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div> --}}
                            <x-BankCard tab="bni" BankName="Bank BNI" BankTag="Transfer ke rekening BNI"
                                BankNumber="0152516222" BankRecipient="Murdiyati" id="bank_bni" />
                            {{-- <div class="flex items-center gap-3" x-show="tab === 'bni'">
                                <input type="radio" id="bank_bca" name="payment_method" value="bank_bca"
                                    class="accent-black" :checked="tab === 'bca'">
                                <label for="bank_bca" class="flex-1 cursor-pointer">
                                    <span class="block font-semibold">Bank BCA</span>
                                    <span class="block text-xs text-gray-500">Transfer ke rekening BCA</span>
                                </label>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div> --}}
                            <x-BankCard tab="mandiri" BankName="Bank Mandiri" BankTag="Transfer ke rekening Mandiri"
                                BankNumber="1380004764382" BankRecipient="Murdiyati" id="bank_mandiri" />
                        </div>
                    </div>
                    {{-- Input Field Bukti Pembayaran --}}
                    <div class="px-4 py-4 mb-4">
                        <label for="payment_proof" class="block mb-2 text-sm font-semibold text-gray-700">Bukti
                            Pembayaran</label>
                        <div id="drop-area"
                            class="flex flex-col items-center justify-center w-full p-4 transition-all duration-200 border-2 border-dashed rounded-lg cursor-pointer form-group bg-gray-50 hover:bg-gray-100 border-zinc-400">
                            <div id="preview-container" class="flex justify-center mb-3"></div>
                            <svg class="w-10 h-10 mb-2 text-zinc-400" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"></path>
                            </svg>
                            <span class="mb-1 text-sm text-zinc-500">Drag & drop gambar di sini, atau klik untuk
                                memilih
                                file</span>
                            <span class="text-xs text-zinc-400">Format: JPG, PNG, max 2MB</span>
                            <input type="file" id="payment_proof" name="payment_proof" accept="image/*"
                                class="hidden" />
                            <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const dropArea = document.getElementById('drop-area');
                            const input = document.getElementById('payment_proof');
                            const preview = document.getElementById('preview-container');

                            function showPreview(file) {
                                preview.innerHTML = '';
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const imgWrapper = document.createElement('div');
                                    imgWrapper.className = 'bg-white p-2 rounded shadow border border-zinc-200';
                                    imgWrapper.style.display = 'inline-block';
                                    const img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.className = 'max-h-64 rounded';
                                    img.alt = 'Preview Bukti Pembayaran';
                                    imgWrapper.appendChild(img);
                                    preview.appendChild(imgWrapper);
                                };
                                reader.readAsDataURL(file);
                            }

                            dropArea.addEventListener('click', () => input.click());

                            dropArea.addEventListener('dragover', function(e) {
                                e.preventDefault();
                                dropArea.classList.add('bg-zinc-100', 'border-black');
                            });

                            dropArea.addEventListener('dragleave', function(e) {
                                e.preventDefault();
                                dropArea.classList.remove('bg-zinc-100', 'border-black');
                            });

                            dropArea.addEventListener('drop', function(e) {
                                e.preventDefault();
                                dropArea.classList.remove('bg-zinc-100', 'border-black');
                                const file = e.dataTransfer.files[0];
                                if (file && file.type.startsWith('image/')) {
                                    input.files = e.dataTransfer.files;
                                    showPreview(file);
                                }
                            });

                            input.addEventListener('change', function() {
                                const file = input.files[0];
                                if (file && file.type.startsWith('image/')) {
                                    showPreview(file);
                                }
                            });
                        });
                    </script>

                </div>
            </div>
            <div>
                <div class="bg-white border border-gray-300 rounded-lg">
                    {{-- <div class="bg-[#EBEBEB]"> --}}
                    {{-- Order Summary --}}
                    <div class="px-4">
                        <h2 class="my-2 text-2xl font-bold">Order Summary</h2>
                    </div>
                    <div class="border-b border-black"></div>
                    {{-- Order Item --}}
                    <div id="order-items-scroll" class="px-4 py-2 min-w-[320px] max-h-96 overflow-y-auto">
                        @foreach ($cartItems as $key => $item)
                            <div
                                class="flex items-center pb-0 my-4 transition-shadow border-b border-black hover:shadow-lg hover:shadow-gray-300">
                                {{-- Product Image --}}
                                <div class="flex-shrink-0 w-20 overflow-hidden bg-white border border-gray-300 h-28">
                                    <img src="{{ $item->product->product_image ? asset('storage/' . $item->product->product_image) : 'https://placehold.co/300x400' }}"
                                        alt="{{ $item->product->product_name ?? 'Produk' }}. Gambar produk dengan nuansa netral di dalam kotak ringkas. "
                                        class="object-cover w-full h-full" style="aspect-ratio:3/4;">
                                </div>
                                <div class="flex flex-col justify-between flex-1 py-1 ml-4 h-28 min-w-[180px]">
                                    <div>
                                        <div class="relative max-w-[180px] h-6">
                                            <div class="absolute inset-0 overflow-hidden">
                                                <div class="text-base font-semibold text-black marquee whitespace-nowrap"
                                                    title="Men's Classic Long-Sleeve Button-Down Oxford Shirt - Blue Striped">
                                                    {{ $item->product->product_name ?? 'Product Name' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-1 space-x-4 text-xs text-gray-600">
                                            <span>Qty: <span
                                                    class="font-medium text-black">{{ $item->cart_qty }}</span></span>
                                            <div class="flex items-center gap-1">
                                                @if ($item->product->product_discount > 0)
                                                <span>Price: <span class="text-xs font-medium text-black line-through">Rp.
                                                    {{ $item->product->product_price }}</span>
                                                </span>
                                                    <span class="text-xs font-medium text-[#fb8763]">Rp.
                                                        {{ $item->product->product_price - $item->product->product_discount }}
                                                    </span>
                                                @else
                                                    <span class="text-xs font-medium text-black">Rp.
                                                        {{ $item->product->product_price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-auto text-right">
                                        @if ($item->product->product_discount > 0)
                                            <div class="flex items-center justify-end gap-2">
                                                <span class="text-xs font-bold text-black line-through whitespace-nowrap">Rp.
                                                    {{ number_format(($item->product->product_price) * $item->cart_qty, 0, ',', '.') }}</span>
                                                <span class="text-lg font-bold text-[#fb8763] whitespace-nowrap">Rp.
                                                        {{ number_format(($item->product->product_price - $item->product->product_discount) * $item->cart_qty, 0, ',', '.') }}</span>
                                            </div>
                                        @else
                                            <span class="text-lg font-bold text-black whitespace-nowrap">Rp.
                                            {{ number_format($item->product->product_price * $item->cart_qty, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($key == 0)
                                <input type="hidden" name="seller_id"
                                    value="{{ $item->product->product_updated_by }}">
                            @endif
                            <input type="hidden" name="cart_items[{{ $key }}][product_name]"
                                value="{{ $item->product->product_name }}">
                            <input type="hidden" name="cart_items[{{ $key }}][product_desc]"
                                value="{{ $item->product->product_desc }}">
                            <input type="hidden" name="cart_items[{{ $key }}][product_image]"
                                value="{{ $item->product->product_image }}">
                            <input type="hidden" name="cart_items[{{ $key }}][product_id]"
                                value="{{ $item->product->id_product }}">
                            <input type="hidden" name="cart_items[{{ $key }}][cart_qty]"
                                value="{{ $item->cart_qty }}">
                            <input type="hidden" name="cart_items[{{ $key }}][product_weight]"
                                value="{{ $item->product->product_weight }}">
                            <input type="hidden" name="cart_items[{{ $key }}][total_product_weight]"
                                value="{{ $item->product->product_weight * $item->cart_qty }}">
                            <input type="hidden" name="cart_items[{{ $key }}][product_price]"
                                value="{{ $item->product->product_price }}">
                            <input type="hidden" name="cart_items[{{ $key }}][discount]"
                                value="{{ $item->product->product_discount ?? 0 }}">
                            <input type="hidden" name="cart_items[{{ $key }}][total_product_price]"
                                value="{{ $item->product->product_price * $item->cart_qty }}">
                        @endforeach
                    </div>
                    <div class="border-b border-black"></div>
                    {{-- Total Price --}}
                    <div class="px-4 py-4 rounded-b-lg">
                        @if ($totalDiscount > 0)
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-base font-medium text-gray-700">Diskon</span>
                                <span class="text-base font-semibold text-black">Rp.
                                    {{ number_format($totalDiscount, 0, ',', '.') }}</span>
                                <input type="hidden" id="total_discount" name="total_discount"
                                    value="{{ $totalDiscount }}">
                            </div>
                        @endif
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-base font-medium text-gray-700">Subtotal</span>
                            @if ($totalDiscount > 0)
                                <div class="flex items-center gap-3">
                                    {{-- <span class="px-2 py-1 text-xs font-bold text-white bg-green-600 rounded">
                                    {{ round(($totalDiscount / $subTotal) * 100, 2) }}%</span> --}}
                                    <div class="flex gap-3 leading-tight">
                                        <span class="text-sm text-gray-500 line-through">Rp {{ $subTotal }}</span>
                                        <span class="text-base font-semibold text-[#fb8763]">Rp
                                            {{ $subTotal - $totalDiscount }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-base font-semibold text-black">Rp.
                                    {{ number_format($subTotal, 0, ',', '.') }}</span>
                            @endif
                            <input type="hidden" id="sub_total" name="sub_total" value="{{ $subTotal }}">
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-base font-medium text-gray-700">Total Berat</span>
                            <span class="text-base font-semibold text-black">{{ $totalWeight }} GR</span>
                            <input type="hidden" id="total_weight" name="total_weight"
                                value="{{ $totalWeight }}">
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-base font-medium text-gray-700">Ongkos Kirim</span>
                            <span class="text-base font-semibold text-black shipping-price">Rp. 0</span>
                            <input type="hidden" id="shipping_price" name="shipping_price" value="0">
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-300">
                            <span class="text-lg font-bold text-black">Total</span>
                            @if ($totalDiscount > 0)
                                <div class="flex gap-3 leading-tight">
                                    <span class="text-sm text-gray-500 line-through total-price">Rp {{ number_format($subTotal, 0, ',', '.') }}</span>
                                    <span class="text-base font-semibold text-[#fb8763] total-price-after-discount">Rp
                                        {{ number_format($subTotal - $totalDiscount, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <span class="text-lg font-bold text-black total-price">Rp.
                                    {{ number_format($subTotal, 0, ',', '.') }}</span>
                            @endif
                            <input type="hidden" id="total_price" name="total_price" value="0">
                            <input type="hidden" id="total_discount" name="total_discount"
                                value="{{ $totalDiscount }}">
                        </div>
                        {{-- Total Weight --}}
                        <input type="hidden" id="total_weight" name="total_weight" value="{{ $totalWeight }}">
                        <div class="mt-6 form-group">
                            <label for="order_note" class="block mb-1 text-sm font-medium text-gray-700">
                                Note <span class="text-gray-400">(optional)</span>
                            </label>
                            <textarea id="order_note" name="order_note" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg resize-none focus:ring focus:ring-zinc-200 focus:border-zinc-500"
                                placeholder="Add a note for your order (optional)"></textarea>
                            <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 mt-4 bg-white border border-gray-300 rounded-lg">
                    <button type="submit"
                        class="flex items-center justify-center w-full gap-2 px-6 py-3 text-lg font-bold text-white transition-all duration-300 bg-black rounded shadow hover:bg-gray-900">
                        <span class="text-2xl ti ti-shopping-cart"></span>
                        Konfirmasi Pemesanan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <style>
        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }

            10% {
                transform: translateX(0%);
            }

            80% {
                transform: translateX(var(--marquee-end, -50%));
            }

            97% {
                transform: translateX(var(--marquee-end, -50%));
            }

            100% {
                transform: translateX(0%);
            }
        }

        .marquee {
            display: inline-block;
            min-width: 100%;
            animation: marquee var(--marquee-duration, 6s) linear infinite;
            animation-delay: 1s;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.marquee').forEach(function(el) {
                const parent = el.parentElement;
                const parentWidth = parent.offsetWidth;
                const scrollWidth = el.scrollWidth;
                if (scrollWidth > parentWidth) {
                    // Calculate how far to scroll (in px)
                    const distance = scrollWidth - parentWidth;
                    // Duration: 60px per second, plus 3s pause at end
                    const scrollDuration = distance / 60;
                    const totalDuration = scrollDuration + 3;
                    el.style.setProperty('--marquee-end', `-${distance}px`);
                    el.style.setProperty('--marquee-duration', `${totalDuration}s`);
                } else {
                    el.style.animation = 'none';
                }
            });
        });
    </script>
    <script>
        const totalDiscount = {{ $totalDiscount }};
        document.addEventListener('DOMContentLoaded', function() {
            const scrollEl = document.getElementById('order-items-scroll');
            if (!scrollEl) return;
            // Only animate if scrollable
            if (scrollEl.scrollHeight > scrollEl.clientHeight) {
                let direction = 1;
                let animationFrame;
                let start = null;
                const maxScroll = scrollEl.scrollHeight - scrollEl.clientHeight;
                const duration = 2000; // ms for one direction

                function animateScroll(timestamp) {
                    if (!start) start = timestamp;
                    const elapsed = timestamp - start;
                    // Ease in-out for smoothness
                    const progress = Math.min(elapsed / duration, 1);
                    const ease = 0.5 - 0.5 * Math.cos(Math.PI * progress);
                    scrollEl.scrollTop = direction === 1 ?
                        ease * maxScroll :
                        (1 - ease) * maxScroll;

                    if (progress < 1) {
                        animationFrame = requestAnimationFrame(animateScroll);
                    } else {
                        // Pause at end, then reverse
                        setTimeout(() => {
                            direction *= -1;
                            start = null;
                            animationFrame = requestAnimationFrame(animateScroll);
                        }, 900); // pause at end
                    }
                }

                animationFrame = requestAnimationFrame(animateScroll);

                // Optional: stop animation on mouse over
                scrollEl.addEventListener('mouseenter', () => {
                    if (animationFrame) cancelAnimationFrame(animationFrame);
                });
                scrollEl.addEventListener('mouseleave', () => {
                    start = null;
                    animationFrame = requestAnimationFrame(animateScroll);
                });
            }
        });
    </script>
    <script>
        function getShippingLogo(company_id) {
            if ([1, 2, 3].includes(company_id)) return 'https://ongkoskirim.id/assets/theme/img/jne.png';
            if ([4, 5, 37].includes(company_id)) return 'https://ongkoskirim.id/assets/theme/img/tiki.png';
            if ([8, 11].includes(company_id)) return 'https://ongkoskirim.id/assets/theme/img/pos-indonesia.png';
            if (company_id === 22) return 'https://ongkoskirim.id/assets/theme/img/wahana.png';
            if ([33, 34].includes(company_id)) return 'https://ongkoskirim.id/assets/theme/img/sicepat.png';
            if (company_id === 36) return 'https://ongkoskirim.id/images/logo-jt.png';
            return 'https://placehold.co/80x32?text=Kurir';
        }

        function getShippingEstimate(companyName = '') {
            const name = companyName.toLowerCase();

            // Cek berdasarkan nama ekspedisi dulu
            if (name.includes('wahana')) return 'Estimasi 2–5 hari';

            // Layanan spesifik
            if (name.includes('yes')) return 'Estimasi 1 hari';
            if (name.includes('best')) return 'Estimasi 1 hari';
            if (name.includes('same day')) return 'Estimasi 1 hari';

            if (name.includes('express')) return 'Estimasi 1–2 hari';
            if (name.includes('pos kilat')) return 'Estimasi 2–4 hari';

            if (name.includes('reg') || name.includes('regular') || name.includes('standard'))
                return 'Estimasi 2–3 hari';

            if (name.includes('oke') || name.includes('eco') || name.includes('economy'))
                return 'Estimasi 3–5 hari';

            return 'Estimasi tidak tersedia';
        }


        function renderShippingLoading() {
            // foreach 3
            const container = $('#shipping-methods');
            let html = ''
            for (let i = 0; i < 3; i++) {
                html += `<div class="flex items-center gap-3 animate-pulse shipping-animate">
            <div class="w-10 h-6 bg-gray-200 rounded"></div>
            <div class="flex-1">
                <span class="block w-32 h-4 mb-1 font-semibold bg-gray-200 rounded"></span>
                <span class="block w-24 h-3 text-xs bg-gray-100 rounded"></span>
            </div>
            <span class="w-16 h-4 font-bold text-gray-300 bg-gray-200 rounded"></span>
        </div>
    `
            }

            html += `<div class="hidden mt-2 text-sm text-red-500 invalid-feedback"></div>`;
            container.html(html);
        }

        function renderShippingMethodsFromApi(res) {
            const container = $('#shipping-methods');
            container.html('');
            if (!res || !res.length) {
                container.html(`
                    <div class="flex flex-col gap-4 shipping-animate">
            <div class="flex items-center gap-3">
                <input type="radio" id="no_shipping" name="shipping_method" value="no_shipping"
                    class="accent-black" onchange="onChangeShippingMethod(this)" checked>
                <label for="no_shipping" class="flex-1 cursor-pointer">
                    <span class="block font-semibold">Tidak ada pengiriman</span>
                    <span class="block text-xs text-gray-500">Pengiriman Belum tersedia pada alamat ini</span>
                </label>
                <span class="font-bold text-black whitespace-nowrap">Rp.0</span>
            </div>
            <a href="#" id="whatsapp-order-btn"
                class="inline-block w-full px-4 py-2 font-semibold text-center text-white bg-green-600 rounded hover:bg-green-700">
                <i class="inline-block mr-2 text-xl ti ti-brand-whatsapp"></i>
                Lanjutkan Pembelian via WhatsApp
            </a>
        </div>

        <div class="hidden mt-2 text-sm text-red-500 invalid-feedback"></div>
                `);

                $('#whatsapp-order-btn').on('click', function(e) {
                    e.preventDefault();
                    sendOrderViaWhatsApp();
                });
                return;
            }
            res.forEach(function(item, idx) {
                const delay = idx * 120;
                const shippingPrice = getShippingPrice(+item.price);
                const value = item.company_name;
                const shippingHtml = `
            <div class="flex items-center gap-3 shipping-animate" style="animation-delay:${delay}ms;">
                <input type="radio" id="shipping_${item.company_id}" name="shipping_method" value="${value}" onchange="onChangeShippingMethod(this)" data-price="${shippingPrice}" class="accent-black" ${idx === 0 ? 'checked' : ''}>
                <img src="${getShippingLogo(item.company_id)}" alt="${item.company_name}" class="object-contain w-10 h-6">
                <label for="shipping_${item.company_id}" class="cursor-pointer">
                    <span class="block font-semibold">${item.company_name}</span>
                    <span class="block text-xs text-gray-500">${item.service || ''}</span>
                    <span class="block text-xs text-gray-500">${getShippingEstimate(item.company_name)}</span>
                </label>
                <div class="flex-1 text-right">
                    <span class="font-bold text-black whitespace-nowrap">${'Rp. ' + Intl.NumberFormat('id-ID').format(shippingPrice)}</span>
                </div>
            </div>
        `;
                container.append(shippingHtml);
            });
        }

        function getShippingPrice(price) {
            if (price === 0) return 'Rp. 0';
            if (price < 1000) return 'Rp. ' + Intl.NumberFormat('id-ID').format(price);
            if (price > 1000) {
                // if 1000 make to 2000 then divide to be 2 and multiply price by weight
                let weight = {{ $totalWeight }};
                weight = Math.ceil(weight / 1000) * 1000;
                let shippingPrice = price * ((weight != 0 ? weight : 1) / 1000);
                console.log(weight, price, shippingPrice);
                return shippingPrice;
            }
        }

        function renderNoShipping() {
            $('#shipping-methods').html(`
                    <div class="flex items-center gap-3 shipping-animate">
                        <input type="radio" id="no_shipping" name="shipping_method" value="no_shipping" class="accent-black" onchange="onChangeShippingMethod(this)" checked>
                        <label for="no_shipping" class="flex-1 cursor-pointer">
                            <span class="block font-semibold">Tidak ada pengiriman</span>
                            <span class="block text-xs text-gray-500">Silakan isi alamat pengiriman terlebih dahulu</span>
                        </label>
                        <span class="font-bold text-black whitespace-nowrap">Rp.0</span>
                    </div>
                `);
        }

        function sendOrderViaWhatsApp() {
            const recipientName = $('#recipient_name').val() || '-';
            const recipientPhone = $('#phone').val() || '-';
            const recipientAddress = $('#address').val() || '-';
            const postalCode = $('#postal_code').val() || '-';
            const province = $('#province').find(':selected').text() || '-';
            const city = $('#city').find(':selected').text() || '-';
            const district = $('#district').find(':selected').text() || '-';
            const subDistrict = $('#sub_district').find(':selected').text() || '-';
            const orderNote = $('#order_note').val() || '-';
            const paymentMethod = $('input[name="payment_method"]:checked').attr('id') || '-';

            if (recipientName == '-' || recipientPhone == '-' || recipientAddress == '-' || postalCode == '-' || province == 'Pilih Provinsi' || city == 'Pilih Kota' || district == 'Pilih Kecamatan' || subDistrict == 'Pilih Kelurahan') {
                Toast.fire({
                    icon: 'warning',
                    title: 'Mohon lengkapi informasi penerima dan alamat pengiriman sebelum melanjutkan.'
                });
                return;
            }

            const bankNames = {
                'bank_bri': {
                    name: 'Bank BRI',
                    number: '061201000042560',
                    recipient: 'Purna Widodo'
                },
                'bank_mandiri': {
                    name: 'Bank Mandiri',
                    number: '1380004764382',
                    recipient: 'Murdiyati'
                },
                'bank_bca': {
                    name: 'Bank BCA',
                    number: '0152516299',
                    recipient: 'Murdiyati'
                }
            };

            const selectedBank = bankNames[paymentMethod] || {
                name: '-',
                number: '-',
                recipient: '-'
            };

            // Ambil daftar produk
            let productDetails = @json($cartItems);
            productDetails = productDetails.map((item, i) => {
                return `\n${i + 1}. ${item.product.product_name}` +
                    `\n    Jumlah: ${item.cart_qty}` +
                    `\n    Harga: ${(item.product.product_discount > 0 ? "Rp. ~" +  item.product.product_price + '~' : '')} Rp. ${(item.product.product_price - item.product.product_discount)}` +
                    `\n    Total: ${item.product.product_discount > 0 ? "Rp. ~" + ((item.product.product_price) * item.cart_qty) + "~" : ''} Rp. ${(item.product.product_price - item.product.product_discount) * item.cart_qty}\n`;
            }).join('');

            // Ambil detail total dari DOM
            const subTotal = totalDiscount > 0 ? `~Rp. ${Number($('#sub_total').val()).toLocaleString('id-ID')}~ Rp. ${Number($('#sub_total').val() - totalDiscount).toLocaleString('id-ID')}` :
                `Rp. ${Number($('#sub_total').val() - totalDiscount).toLocaleString('id-ID')}`;
            const totalWeight = $('#total_weight').val() || 0;
            const shippingPrice = $('#shipping_price').val() || 0;
            const totalPrice = totalDiscount > 0 ? `~Rp. ${Number($('#total_price').val()).toLocaleString('id-ID')}~ Rp. ${Number($('#total_price').val() - totalDiscount).toLocaleString('id-ID')}` : `Rp. ${Number($('#total_price').val() - totalDiscount).toLocaleString('id-ID')}`;

            const message = `Halo Toko Unyil, saya ingin memesan produk dengan detail berikut:\n\n` +
                `- Produk:\n${productDetails}` +
                `\n - Ringkasan Pesanan:\n` +
                (totalDiscount > 0 ? `Diskon: Rp. ${Number(totalDiscount).toLocaleString('id-ID')}\n` : '') +
                `Subtotal: ${subTotal}\n` +
                `Total Berat: ${totalWeight} GR\n` +
                // `Ongkos Kirim: Rp. ${Number(shippingPrice).toLocaleString('id-ID')}\n` +
                `Total Harga: ${totalPrice}\n` +
                `Catatan: ${orderNote}\n\n` +
                `- Informasi Penerima:\n` +
                `Nama: ${recipientName}\n` +
                `No. Telepon: ${recipientPhone}\n` +
                `Alamat: ${recipientAddress}\n` +
                `Kode Pos: ${postalCode}\n` +
                `Kelurahan: ${subDistrict}\n` +
                `Kecamatan: ${district}\n` +
                `Kota/Kabupaten: ${city}\n` +
                `Provinsi: ${province}\n` +
                `Negara: Indonesia\n\n` +
                `- Metode Pembayaran:\n` +
                `Bank: ${selectedBank.name}\n` +
                `No Rekening: ${selectedBank.number}\n` +
                `Atas Nama: ${selectedBank.recipient}`;

            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/6281353026665?text=${encodedMessage}`;

            window.open(whatsappUrl, '_blank');
        }

        function onChangeShippingMethod(e) {
            const shippingPrice = e ? parseInt($(e).data('price')) : 0;
            $('#shipping_price').val(shippingPrice);
            $('.shipping-price').text(shippingPrice > 0 ? 'Rp. ' + Intl.NumberFormat('id-ID').format(shippingPrice) :
                'Rp. 0');

            // Update total price
            const totalPrice = parseInt($('#sub_total').val()) + shippingPrice;
            $('#total_price').val(totalPrice);
            $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format(totalPrice));
            $('.total-price-after-discount').text('Rp. ' + Intl.NumberFormat('id-ID').format(totalPrice - totalDiscount));

            console.log('shippingPrice', shippingPrice, 'totalPrice', totalPrice);
        };

        function afterFormSubmit(response) {
            if (response.data && response.data.redirect_url) {
                setTimeout(() => {
                    window.location.href = response.data.redirect_url;
                }, 2000);
            }
        }

        $(document).ready(function() {
            // $('#city').select2({
            //     placeholder: 'Pilih Kota',
            //     allowClear: true,
            //     minimumInputLength: 3,
            //     ajax: {
            //         url: '{{ route('api.cities') }}',
            //         type: 'POST',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             let nama = params.term ? params.term.toLowerCase() : '';
            //             if (nama == 'jog' || nama == 'jogj' || nama == 'jogja' || nama ==
            //                 'jogjakarta') {
            //                 var comp = nama;
            //                 nama = comp.replace(/j/gi, 'y');
            //             }
            //             return {
            //                 _token: '{{ csrf_token() }}',
            //                 submit: 'from_city',
            //                 city: nama
            //             };
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: (data || []).map(function(item) {
            //                     var parts = item.split(';');
            //                     return {
            //                         id: parts[1],
            //                         text: parts[0]
            //                     };
            //                 })
            //             };
            //         }
            //     }
            // });

            $('#city').select2({
                placeholder: 'Pilih Kota',
                allowClear: true
            });

            $('#province').on('change', function() {
                // clear city and district and sub_district
                $('#total_price').val($('#sub_total').val()); // reset total price
                $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format($('#sub_total')
                    .val())); // reset total price display
                $('.total-price-after-discount').text('Rp. ' + Intl.NumberFormat('id-ID').format(
                    $('#sub_total').val() - totalDiscount)); // reset total price after discount display
                $('#city').val(null).trigger('change');
                $('#district').val(null).trigger('change');
                $('#district').find('option').not(':disabled').remove();
                $('#sub_district').val(null).trigger('change');
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');
                $('#shipping_price').val(0); // reset shipping price
                $('.shipping-price').text('Rp. 0'); // reset shipping price display
                $('#total_price').val($('#sub_total').val()); // reset total price
                $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format($('#sub_total')
                    .val())); // reset total price display
                $('#no_shipping').prop('checked', true); // set no shipping checked
                $('#to_city_id').val('');

                if ($(this).val() === '' || $(this).val() === null || $(this).val() === 'Pilih Provinsi') {
                    return;
                }

                // disabled city and district fields subdistrict
                // disabled the select
                $(`#city, #district, #sub_district`).prop('disabled', true);

                var provinceId = $(this).val();
                const provinceCode = $(this).find(':selected').data('code');
                $('#city').empty().append(
                        '<option value="memuat_data" disabled selected>Memuat kota...</option>')
                    .trigger('change');
                $('#to_city_id').val('memuat_data');

                $.ajax({
                    url: '{{ route('api.cities.wilayah') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        province_id: provinceCode,
                    },
                    success: function(data) {
                        $('#city').empty().append(
                            '<option value="" disabled selected>Pilih Kota</option>');
                        (data || []).forEach(function(item) {
                            const $option = new Option(item.name, item.id);
                            $option.setAttribute('data-code', item.code);
                            $('#city').append($option);
                        });
                        // Auto-select saved city by matching text
                        const savedCityText = $('#city').data('saved-text');
                        if (savedCityText) {
                            const match = $('#city option').filter(function(){ return $(this).text() === savedCityText; }).val();
                            if (match) {
                                $('#city').val(match).trigger('change.select2');
                                $('#city').trigger({ type: 'select2:select', params: { data: { id: match } } });
                            } else {
                                $('#city').trigger('change');
                            }
                        } else {
                            $('#city').trigger('change');
                        }
                    },
                    complete: function() {
                        $(`#city, #district, #sub_district`).prop('disabled', false).trigger(
                            'change.select2');
                        $('#city').find('option[value="memuat_data"]').remove();
                        if ($('#city option').not(':disabled')
                            .length === 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: "Gagal memuat kota, mohon pilih ulang provinsi"
                            });
                            // $('#province').val(null).trigger('change');
                        }
                    },
                    error: function() {
                        $('#city').empty().append(
                            '<option value="" disabled selected>Gagal memuat kota</option>');
                        $('#to_city_id').val('');
                    }
                });
            });

            $.ajax({
                url: '/api-provinces',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var $province = $('#province');
                    $province.empty().append(
                        '<option value="" disabled selected>Pilih Provinsi</option>');
                    data.forEach(function(item) {
                        const $option = new Option(item.name, item.name);
                        $option.setAttribute('data-code', item.code);
                        $province.append($option);
                    });
                    // Auto-select saved province if available
                    const savedProv = $province.data('saved');
                    if (savedProv) {
                        $province.val(savedProv).trigger('change');
                    }
                    // Enable address fields after province data loaded
                    $('#address-fields-wrapper').removeClass('address-disabled');
                    $('#address-disabled-overlay').remove();
                },
                error: function() {
                    // Show error message in overlay
                    $('#address-disabled-overlay').html(
                        '<div class="flex flex-col items-center justify-center w-full h-full"><span class="mb-2 text-sm font-semibold text-[#fb8763]">Gagal memuat data informasi alamat.</span><span class="text-xs text-gray-600">Mohon cek koneksi internet Anda dan refresh halaman.</span></div>'
                    );
                }
            });

            var $province = $('#province');
            $province.select2({
                placeholder: 'Pilih Provinsi',
                allowClear: true
            });

            $('#district').select2({
                placeholder: 'Pilih Kecamatan',
                allowClear: true
            });

            // clear provinces
            $('#province').on('select2:clear', function() {
                $('#district').val(null).trigger('change');
                $('#district').find('option').not(':disabled').remove();
                $('#sub_district').val(null).trigger('change');
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');
                $('#shipping_price').val(0); // reset shipping price
                $('.shipping-price').text('Rp. 0'); // reset shipping price display
                $('#total_price').val($('#sub_total').val()); // reset total price
                $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format($('#sub_total')
                    .val())); // reset total price display
                $('#no_shipping').prop('checked', true); // set no shipping checked
                renderShippingLoading(); // tampilkan loading
                setTimeout(renderNoShipping, 700); // opsional: ganti dengan empty state setelah loading
            });

            $('#city').on('select2:clear', function() {
                $('#total_price').val($('#sub_total').val()); // reset total price
                $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format($('#sub_total')
                    .val())); // reset total price display
                $('.total-price-after-discount').text('Rp. ' + Intl.NumberFormat('id-ID').format(
                    $('#sub_total').val() - totalDiscount)); // reset total price after discount display
                $('#to_city_id').val('');
                $('#district').val(null).trigger('change');
                $('#district').find('option').not(':disabled').remove();
                $('#sub_district').val(null).trigger('change');
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');
                $('#shipping_price').val(0); // reset shipping price
                $('.shipping-price').text('Rp. 0'); // reset shipping price display
                $('#total_price').val($('#sub_total').val()); // reset total price
                $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format($('#sub_total')
                    .val())); // reset total price display
                $('#no_shipping').prop('checked', true); // set no shipping checked
                renderShippingLoading(); // tampilkan loading
                setTimeout(renderNoShipping, 700); // opsional: ganti dengan empty state setelah loading
            });

            $('#district').on('select2:clear', function() {
                $('#sub_district').val(null).trigger('change');
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');
            });

            // Saat kota dipilih, lakukan AJAX manual untuk kecamatan
            $('#city').on('select2:select', function(e) {

                // disabled
                $(`#district, #sub_district`).prop('disabled', true).trigger('change.select2');
                $('#total_price').val($('#sub_total').val()); // reset total price
                $('.total-price').text('Rp. ' + Intl.NumberFormat('id-ID').format($('#sub_total')
                    .val())); // reset total price display
                $('.total-price-after-discount').text('Rp. ' + Intl.NumberFormat('id-ID').format(
                    $('#sub_total').val() - totalDiscount)); // reset total price after discount display
                // clear district and sub_district
                $('#district').val(null).trigger('change');
                $('#district').find('option').not(':disabled').remove();
                $('#sub_district').val(null).trigger('change');
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');

                var cityId = e.params.data.id;
                $('#to_city_id').val(cityId);

                if ($(this).val() === '' || $(this).val() === null || $(this).val() === 'Pilih Kota') {
                    return;
                }

                // Reset district
                $('#district').find('option').not(':disabled').remove();

                $('#district').append(new Option('Memuat...', 'memuat', true, true)).trigger('change');

                renderShippingLoading();
                // ajax to same teh kote with checkongkir
                const cityValue = $('#city').val();
                $.ajax({
                    url: '{{ route('api.cities') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        submit: 'to_city',
                        city: cityValue
                    },
                    success: function(res) {
                        // attach to to_city
                        $('#to_city_id').val(res.code);
                        $.ajax({
                            url: '{{ route('api.checkongkir') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                _token: '{{ csrf_token() }}',
                                from_city_id: $('#from_city_id').val(),
                                to_city_id: $('#to_city_id').val(),
                                submit: 'cekongkir',
                            },
                            success: function(res) {
                                // Render shipping method di sini
                                renderShippingMethodsFromApi(res);
                                // selected first shipping method
                                const firstShipping = res[0];
                                if (firstShipping) {
                                    const shippingPrice = getShippingPrice(
                                        firstShipping.price);
                                    $('#shipping_price').val(shippingPrice);
                                    $('.shipping-price').text(shippingPrice > 0 ?
                                        'Rp. ' + Intl
                                        .NumberFormat('id-ID').format(
                                            shippingPrice) : 'Rp. 0');
                                    // Update total price
                                    const totalPrice = parseInt($(
                                            '#sub_total').val()) +
                                        shippingPrice;
                                    $('#total_price').val(totalPrice);
                                    $('.total-price').text('Rp. ' + Intl
                                        .NumberFormat('id-ID').format(
                                            totalPrice));
                                    $('.total-price-after-discount').text('Rp. ' + Intl
                                        .NumberFormat('id-ID').format(
                                            totalPrice - totalDiscount));
                                } else {
                                    $('#shipping_price').val(0);
                                    $('.shipping-price').text('Rp. 0');
                                    $('#total_price').val($('#sub_total')
                                        .val());
                                    $('.total-price').text('Rp. ' + Intl
                                        .NumberFormat('id-ID').format($(
                                            '#sub_total').val()));
                                    $('#no_shipping').prop('checked', true);
                                    // renderNoShipping();
                                }
                            },
                            complete: function() {
                                $(`#district, #sub_district`).prop('disabled',
                                        false)
                                    .trigger('change.select2');

                                if ($('#district option').not(':disabled')
                                    .length === 0) {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: "Gagal memuat kecamatan, mohon pilih ulang kota"
                                    });
                                    $('#city').val(null).trigger('change');
                                    renderNoShipping();
                                }
                            },
                            error: function() {
                                renderShippingMethodsFromApi([]);
                            }
                        });

                    }
                });

                // AJAX untuk kecamatan
                const cityCode = $('#city').find(':selected').data('code');
                $.ajax({
                    url: '{{ route('api.districts.wilayah') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        submit: 'kecamatan',
                        city_id: cityCode
                    },
                    success: function(data) {
                        $('#district').find('option[value="memuat"]').remove();
                        $('#district').append(new Option('Pilih Kecamatan', '', true, true))
                            .trigger('change');

                        (data || []).forEach(function(opt) {
                            const $option = new Option(opt.name, opt.name);
                            $option.setAttribute('data-code', opt.code);
                            $('#district').append($option);
                        });
                        const savedDistrict = $('#district').data('saved-text');
                        if (savedDistrict) {
                            $('#district').val(savedDistrict).trigger('change.select2');
                            $('#district').trigger({ type: 'select2:select', params: { data: { id: savedDistrict } } });
                        } else {
                            $('#district').val(null).trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#district').find('option[value="memuat"]').remove();
                        $('#district').append(new Option(
                            'Gagal memuat kecamatan mohon memilih ulang kota',
                            'gagal_memuat', true, true)).trigger('change');

                        setTimeout(function() {
                            $('#district').find('option[value="gagal_memuat"]')
                                .remove();
                            $('#district').val(null).trigger(
                                'change'); // Reset district select2
                        }, 2000); // Reset after 1 second
                    }
                });
            });

            //attach select2 to Kelurahan
            $('#sub_district').select2({
                placeholder: 'Pilih Kelurahan',
                allowClear: true
            });

            // on change district get the villages
            $('#district').on('select2:select', function(e) {

                // disabled all select
                $('#sub_district').prop('disabled', true).trigger('change.select2');

                // clear sub_district
                $('#sub_district').val(null).trigger('change');
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');

                if ($(this).val() === '' || $(this).val() === null || $(this).val() === 'Pilih Kecamatan') {
                    return;
                }

                const districtCode = $(this).find(':selected').data('code');

                // Reset village select2
                $('#sub_district').empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan</option>').trigger('change');

                $('#sub_district').append(new Option('Memuat...', 'memuat', true, true)).trigger(
                    'change');

                // AJAX untuk desa
                $.ajax({
                    url: '{{ route('api.sub.districts.wilayah') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        submit: 'desa',
                        district_id: districtCode
                    },
                    success: function(data) {
                        (data || []).forEach(function(opt) {
                            const $option = new Option(opt.name, opt.name);
                            $option.setAttribute('data-code', opt.code);
                            $('#sub_district').append($option);
                        });
                        $('#sub_district').find('option[value="memuat"]').remove();
                        const savedVillage = $('#sub_district').data('saved-text');
                        if (savedVillage) {
                            $('#sub_district').val(savedVillage).trigger('change.select2');
                        } else {
                            $('#sub_district').val(null).trigger('change');
                        }
                    },
                    complete: function() {
                        $('#sub_district').prop('disabled', false).trigger(
                            'change.select2');
                        if ($('#sub_district option').not(':disabled')
                            .length === 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: "Gagal memuat kecamatan, mohon pilih ulang kelurahan"
                            });
                            $('#district').val(null).trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching villages:', error);
                        $('#sub_district').find('option[value="memuat"]').remove();
                        $('#sub_district').empty().append(
                            '<option value="" disabled selected>Gagal memuat kelurahan</option>'
                        ).trigger(
                            'change');
                    }
                });
            });

            // Set hidden input saat kecamatan dipilih
            $('#district').on('select2:select', function(e) {
                $('#to_city_id').val(e.params.data.id);
            });
        });
    </script>
</x-app-layout>
