<x-app-layout>
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #000;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            height: 3.6rem;
        }

        /* text in center both way */
        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            /* center up down only */
            display: flex;
            align-items: center;
            height: 100%;
        }
    </style>
    <div x-data="orderDetails()" class="min-h-screen" id="order-details">
        <!-- Header -->
        <header class="p-6 border-b border-black">
            <div class="mx-auto max-w-7xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">ID PESANAN</p>
                        <p class="text-2xl font-bold">{{ $transaction->id_transaction }}</p>
                    </div>
                    <div class="text-right" id="status-order-container">
                        <p class="text-sm text-gray-600">STATUS PESANAN</p>
                        @php
                        // ['processed', 'shipped', 'completed', 'cancelled']
                        $colorClass = match ($transaction->transaction_status) {
                        'processed' => 'bg-yellow-200',
                        'shipped' => 'bg-blue-200',
                        'completed' => 'bg-green-200',
                        'cancelled' => 'bg-red-200',
                        default => 'bg-gray-200',
                        };

                        $text = match ($transaction->transaction_status) {
                        'processed' => 'PROSES',
                        'shipped' => 'DIKIRIM',
                        'completed' => 'SELESAI',
                        'cancelled' => 'DIBATALKAN',
                        default => 'UNKNOWN',
                        };
                        @endphp
                        <span
                            class="inline-block px-4 py-2 text-sm font-bold text-black {{ $colorClass }} border border-black">
                            {{ strtoupper($text) }}
                        </span>

                    </div>
                </div>
            </div>
        </header>

        <div class="p-6 mx-auto max-w-7xl">
            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Status & Actions -->
                    <div class="bg-white border border-black">
                        <div class="p-4 border-b border-black">
                            <h2 class="text-xl font-bold">STATUS & AKSI</h2>
                        </div>

                        <form class="grid gap-6 p-6 md:grid-cols-2 base-form"
                            action="{{ route('admin.transactions.update', $transaction->id_transaction) }}"
                            method="POST" enctype="multipart/form-data" data-function-callback="afterSaveTracking">
                            @csrf
                            @method('PUT')
                            <!-- Status Dropdown -->
                            <div class="form-group">
                                <label class="block mb-3 text-sm font-bold text-gray-600 uppercase">STATUS
                                    PESANAN</label>
                                <div class="relative">
                                    <select x-model="status" @change="updateStatus()" name="transaction_status"
                                        class="w-full p-4 font-bold text-black bg-white border border-black select2 focus:outline-none focus:bg-gray-50">
                                        <option value="processed" @if ($transaction->transaction_status === 'processed') selected @endif>
                                            PROSES</option>
                                        <option value="shipped" @if ($transaction->transaction_status === 'shipped') selected @endif>
                                            DIKIRIM</option>
                                        <option value="completed" @if ($transaction->transaction_status === 'completed') selected @endif>
                                            SELESAI</option>
                                        <option value="cancelled" @if ($transaction->transaction_status === 'cancelled') selected @endif>
                                            DIBATALKAN</option>
                                    </select>
                                </div>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>

                            <!-- Tracking Number -->
                            <div class="form-group">
                                <label class="block mb-3 text-sm font-bold text-gray-600 uppercase">NOMOR RESI</label>
                                <div class="flex gap-2">
                                    <input type="text" x-model="trackingNumber" placeholder="MASUKKAN NOMOR RESI"
                                        value="{{ $transaction->tshipping_tracking_number }}"
                                        name="tshipping_tracking_number"
                                        class="flex-1 p-4 font-bold border border-black focus:outline-none focus:bg-gray-50">
                                </div>
                                <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                            </div>

                            <!-- Shipping Proof File -->
                            <div class="col-span-2 space-y-4 form-group">
                                <div class="px-4 py-4 mb-4">
                                    <p class="mb-2 text-sm font-bold text-gray-600 uppercase">FOTO RESI PENGIRIMAN
                                    </p>
                                    <div id="drop-area"
                                        class="flex flex-col items-center justify-center w-full p-4 transition-all duration-200 border border-dashed cursor-pointer bg-gray-50 hover:bg-gray-100 border-zinc-400">
                                        @if ($transaction->tshipping_proof)
                                        <div id="preview-container" class="flex justify-center mb-3">
                                            <img src="{{ Storage::url($transaction->tshipping_proof) }}"
                                                alt="Bukti Pengiriman" class="rounded max-h-64">
                                        </div>
                                        @else
                                        <div id="preview-container" class="flex justify-center mb-3"></div>
                                        @endif
                                        <span class="text-5xl ti ti-cloud-upload"></span>
                                        <span class="mb-1 text-sm text-zinc-500">Drag & drop gambar di sini, atau
                                            klik
                                            untuk memilih file</span>
                                        <span class="text-xs text-zinc-400">Format: JPG, PNG, max 2MB</span>
                                        <input type="file" id="tshipping_proof" name="tshipping_proof"
                                            accept="image/*" class="hidden" />
                                        <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const dropArea = document.getElementById('drop-area');
                                        const input = document.getElementById('tshipping_proof');
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
                            <!-- Save Button -->

                            <button @click="saveTracking()" type="submit"
                                class="flex items-center justify-center col-span-2 gap-2 px-6 py-3 text-sm font-bold text-white bg-black border border-black hover:bg-gray-800">
                                SIMPAN
                            </button>

                        </form>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white border border-black">
                        <div class="p-4 border-b border-black">
                            <h2 class="text-xl font-bold">DAFTAR PRODUK</h2>
                        </div>

                        @foreach ($transaction->details as $td)
                        <div class="p-6 border-b-2 border-gray-200">
                            <div class="flex gap-6">
                                <div
                                    class="flex items-center justify-center w-[96px] h-[128px] bg-gray-100 border border-gray-300">
                                    <img src="{{ $td->tdproduct_img ? Storage::url($td->tdproduct_img) : 'https://placehold.co/300x400' }}"
                                        alt="Gambar Produk" class="object-cover w-full h-full rounded"
                                        style="max-width:96px; max-height:128px;" />
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-xl font-bold">{{ $td->tdproduct_name }}</h3>
                                    <p class="mt-1 text-sm font-bold text-gray-500">BERAT:
                                        {{ $td->tdproduct_weight }}g per item
                                    </p>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center gap-6">
                                            <span class="text-sm font-bold">JUMLAH: {{ $td->tdproduct_qty }}</span>
                                            <span class="text-sm font-bold text-gray-600">TOTAL BERAT:
                                                {{ $td->tdproduct_weight * $td->tdproduct_qty }}g</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="flex flex-col items-end space-y-6 text-right">
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-600">HARGA SATUAN</p>
                                        @if ($td->tdproduct_discount > 0)
                                        <p class="text-base font-bold text-red-600 line-through">Rp
                                            {{ number_format($td->tdproduct_price, 0, ',', '.') }}
                                        </p>
                                        <p class="text-lg font-bold">Rp
                                            {{ number_format($td->tdproduct_price - $td->tdproduct_discount, 0, ',', '.') }}
                                        </p>
                                        @else
                                        <p class="text-lg font-bold">Rp
                                            {{ number_format($td->tdproduct_price, 0, ',', '.') }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-600">TOTAL</p>
                                        @if ($td->tdproduct_total_discount > 0)
                                        <div class="flex gap-3">
                                            <p class="text-base font-bold text-red-600 line-through">Rp
                                                {{ number_format($td->tdproduct_total_price * $td->tdproduct_qty, 0, ',', '.') }}
                                            </p>
                                            <p class="text-lg font-bold">Rp
                                                {{ number_format($td->tdproduct_total_price * $td->tdproduct_qty - $td->tdproduct_total_discount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        @else
                                        <p class="text-2xl font-bold">Rp
                                            {{ number_format($td->tdproduct_price * $td->tdproduct_qty, 0, ',', '.') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="p-6 bg-white border border-black">
                        <h3 class="mb-6 text-xl font-bold">RINGKASAN PESANAN</h3>
                        <div class="space-y-3 text-lg">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-600">SUBTOTAL</span>
                                @if($transaction->total_discount > 0)
                                <div class="flex gap-3">
                                    <p class="text-base font-bold text-red-600 line-through">Rp
                                        {{ number_format($transaction->subtotal_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-lg font-bold">Rp
                                        {{ number_format($transaction->subtotal_price - $transaction->total_discount, 0, ',', '.') }}
                                    </p>
                                </div>
                                @else
                                <p class="text-lg font-bold">Rp
                                    {{ number_format($transaction->subtotal_price, 0, ',', '.') }}
                                </p>
                                @endif
                            </div>

                            @if ($transaction->total_discount > 0)
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-600">DISKON</span>
                                <span class="font-bold text-red-600">-Rp
                                    {{ number_format($transaction->total_discount, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="font-bold text-gray-600">ONGKOS KIRIM (JNE REG)</span>
                                <span class="font-bold">Rp
                                    {{ number_format($transaction->tshipping_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="pt-4 mt-4 border-t-2 border-black">
                                <div class="flex justify-between text-2xl">
                                    <span class="font-bold">TOTAL</span>
                                    <span class="font-bold">Rp
                                        @if($transaction->total_discount > 0)
                                        {{ number_format(($transaction->subtotal_price - $transaction->total_discount) + $transaction->tshipping_price, 0, ',', '.') }}</span>
                                    @else
                                    {{ number_format($transaction->subtotal_price + $transaction->tshipping_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    {{-- <div class="bg-white border border-black">
                        <div class="p-4 border-b border-black">
                            <h3 class="text-lg font-bold">INFO PELANGGAN</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <p class="text-sm font-bold text-gray-600 uppercase">NAMA</p>
                                <p class="text-lg font-bold">AHMAD WIJAYA</p>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-600 uppercase">EMAIL</p>
                                <p class="font-bold">ahmad.wijaya@email.com</p>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-600 uppercase">TELEPON</p>
                                <p class="font-bold">+62 812-3456-7890</p>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Shipping Address -->
                    <div class="bg-white border border-black rounded-lg">
                        <div class="p-4 border-b border-black">
                            <h3 class="text-lg font-bold">ALAMAT PENGIRIMAN</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <p class="text-lg font-bold">{{ $transaction->tshipping_receipt_name }}</p>
                                <p class="font-bold">{{ $transaction->tshipping_phone }}</p>
                                <p class="font-bold leading-relaxed">{{ $transaction->tshipping_address }}</p>
                                @php
                                $alamat = collect([
                                $transaction->tshipping_provience,
                                $transaction->tshipping_city,
                                $transaction->tshipping_district,
                                $transaction->tshipping_subdistrict,
                                $transaction->tshipping_zip_code,
                                ])
                                ->filter()
                                ->implode(', ');
                                @endphp

                                <div class="text-sm font-bold">
                                    {{ $alamat }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white border border-black rounded-lg">
                        <div class="p-4 border-b border-black">
                            <h2 class="text-xl font-bold">INFORMASI PENGIRIMAN</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="p-3 border border-gray-300 bg-gray-50">
                                <p class="mb-1 text-sm font-bold text-gray-600 uppercase">KURIR</p>
                                <p class="font-bold">{{ $transaction->tshipping_method }}</p>
                            </div>
                            <div class="p-3 border border-gray-300 bg-gray-50">
                                <p class="mb-1 text-sm font-bold text-gray-600 uppercase">ONGKIR</p>
                                <p class="font-bold">Rp.
                                    {{ number_format($transaction->tshipping_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3 border border-gray-300 bg-gray-50">
                                <p class="mb-1 text-sm font-bold text-gray-600 uppercase">TOTAL BERAT</p>
                                <p class="font-bold">{{ $transaction->total_weight }}GR</p>
                            </div>
                            @if ($transaction->transaction_note)
                            <div class="p-3 border border-gray-300 bg-gray-50">
                                <p class="mb-1 text-sm font-bold text-gray-600 uppercase">CATATAN</p>
                                <p class="font-bold">{{ $transaction->transaction_note }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    <div class="bg-white border border-black rounded-lg">
                        <div class="p-4 border-b border-black">
                            <h3 class="text-lg font-bold">BUKTI PEMBAYARAN</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                <div class="p-3 border border-gray-300 bg-gray-50">
                                    <p class="mb-1 text-sm font-bold text-gray-600 uppercase">BANK TUJUAN</p>
                                    <p class="font-bold">
                                        {{ strtoupper(str_replace('_', ' ', explode(';', $transaction->transaction_pay_method)[0])) }}
                                    </p>
                                    <p class="text-sm font-bold">
                                        {{ explode(';', $transaction->transaction_pay_method)[1] }}
                                        ({{ explode(';', $transaction->transaction_pay_method)[2] }})
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-2 text-sm font-bold text-gray-600 uppercase">BUKTI TRANSFER</p>
                                    <div class="p-3 transition-colors border border-gray-300 cursor-pointer bg-gray-50 hover:bg-gray-100"
                                        @click="showModal = true">
                                        <img :src="src" alt="Bukti Transfer"
                                            class="object-contain w-full h-32 rounded" />
                                        <p class="mt-2 text-xs font-bold text-center text-gray-600 uppercase">KLIK
                                            UNTUK PERBESAR</p>
                                    </div>
                                </div>

                                <div class="space-y-2 text-sm font-bold">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 uppercase">TANGGAL:</span>
                                        <span>{{ $transaction->created_at->translatedFormat('d F Y, H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 uppercase">JUMLAH:</span>
                                        @if($transaction->total_discount > 0)
                                        <span>Rp {{ number_format(($transaction->subtotal_price - $transaction->total_discount) + $transaction->tshipping_price, 0, ',', '.') }}</span>
                                        @else
                                        <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    {{-- <div class="bg-white border border-black">
                        <div class="p-4 border-b border-black">
                            <h3 class="text-lg font-bold">LINIMASA PESANAN</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <div class="w-4 h-4 mt-1 bg-black"></div>
                                    <div class="flex-1">
                                        <p class="font-bold">PESANAN DIBUAT</p>
                                        <p class="text-xs font-bold text-gray-600">20 JUL 2025, 10:30</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-4 h-4 mt-1 bg-black"></div>
                                    <div class="flex-1">
                                        <p class="font-bold">PEMBAYARAN DITERIMA</p>
                                        <p class="text-xs font-bold text-gray-600">20 JUL 2025, 10:35</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-4 h-4 mt-1 bg-black"></div>
                                    <div class="flex-1">
                                        <p class="font-bold">PESANAN TERKONFIRMASI</p>
                                        <p class="text-xs font-bold text-gray-600">20 JUL 2025, 11:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Payment Proof Modal -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="showModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75">
            <div @click.stop class="max-w-2xl max-h-screen overflow-auto bg-white border border-black">
                <div class="flex items-center justify-between p-4 border-b border-black">
                    <h3 class="text-lg font-bold">BUKTI PEMBAYARAN - DETAIL</h3>
                    <button @click="showModal = false" class="text-2xl font-bold hover:text-gray-600">×</button>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-6 bg-gray-100 border border-gray-300 h-96">
                        <img :src="src" alt="Bukti Pembayaran"
                            class="object-contain w-full h-full rounded" />
                    </div>
                    {{-- <div class="p-4 border border-gray-300 bg-gray-50">
                        <h4 class="mb-4 font-bold uppercase">RINCIAN TRANSFER</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm font-bold">
                            <div class="text-gray-600 uppercase">BANK PENGIRIM:</div>
                            <div>BCA</div>
                            <div class="text-gray-600 uppercase">NAMA PENGIRIM:</div>
                            <div>AHMAD WIJAYA</div>
                            <div class="text-gray-600 uppercase">TANGGAL:</div>
                            <div>20 JUL 2025, 10:32</div>
                            <div class="text-gray-600 uppercase">JUMLAH:</div>
                            <div>Rp 292.500</div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Shipping Receipt Modal -->
        {{-- <div x-show="showReceiptModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="showReceiptModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75">
            <div @click.stop class="max-w-2xl max-h-screen overflow-auto bg-white border-4 border-black">
                <div class="flex items-center justify-between p-4 border-b border-black">
                    <h3 class="text-lg font-bold">BUKTI PENGIRIMAN - DETAIL</h3>
                    <button @click="showReceiptModal = false"
                        class="text-2xl font-bold hover:text-gray-600">×</button>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-6 bg-gray-100 border border-gray-300 h-96">
                        <span class="text-xl font-bold text-gray-600">FOTO RESI PENGIRIMAN</span>
                    </div>
                    <div class="p-4 border border-gray-300 bg-gray-50">
                        <h4 class="mb-4 font-bold uppercase">RINCIAN PENGIRIMAN</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm font-bold">
                            <div class="text-gray-600 uppercase">KURIR:</div>
                            <div>JNE REGULAR</div>
                            <div class="text-gray-600 uppercase">NOMOR RESI:</div>
                            <div>JNE12345678900</div>
                            <div class="text-gray-600 uppercase">BERAT:</div>
                            <div>850 GRAM</div>
                            <div class="text-gray-600 uppercase">ONGKOS KIRIM:</div>
                            <div>Rp 15.000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Toast Notification -->
        {{-- <div x-show="showToast" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="fixed z-50 px-6 py-4 bg-white border-4 border-black top-6 right-6">
            <p class="font-bold">BERHASIL DISIMPAN!</p>
        </div> --}}
    </div>

    <script>
        $(document).ready(function() {
            // select 2
            $('.select2').select2();
        });

        function orderDetails() {
            return {
                showToast: false,
                showModal: false,
                showReceiptModal: false,
                status: '{{ $transaction->transaction_status }}',
                trackingNumber: '{{ $transaction->tshipping_tracking_number }}',
                src: '{{ $transaction->transaction_pay_proof ? asset('storage/' . $transaction->transaction_pay_proof) : 'https://placehold.co/700x320' }}',

                    updateStatus() {
                        this.showToast = true;
                        setTimeout(() => {
                            this.showToast = false;
                        }, 3000);
                    },

                saveTracking() {
                    this.showToast = true;
                    setTimeout(() => {
                        this.showToast = false;
                    }, 3000);
                }
            }
        }

        function afterSaveTracking(response) {
            $('#status-order-container').load(window.location.href + ' #status-order-container > *');
            $('#preview-container').load(window.location.href + ' #preview-container > *');
        }
    </script>
</x-app-layout>