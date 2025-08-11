@props(['BankName' => ''])
@props(['BankTag' => ''])
@props(['BankNumber' => ''])
@props(['BankRecipient' => ''])
@props(['tab' => ''])
@props(['id' => ''])

<div class="flex items-center gap-3 form-group" x-show="tab === '{{ $tab }}'">
    <input type="radio" id="{{ $id }}" name="payment_method" value="{{ $id }};{{ $BankNumber }};{{ $BankRecipient }}"
        class="hidden accent-black" :checked="tab === '{{ $tab }}'">
    <label for="{{ $id }}" class="flex-1 cursor-pointer">
        <!-- Header Section -->
        <div class="relative p-4 overflow-hidden text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl">
            <div class="absolute top-0 right-0 w-16 h-16 translate-x-4 -translate-y-4 bg-white rounded-full opacity-10">
            </div>
            <div class="relative z-10 flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg bg-opacity-20">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M2 10h20v8a2 2 0 01-2 2H4a2 2 0 01-2-2v-8zm18-4a2 2 0 012 2v2H2V8a2 2 0 012-2h16zM8 16h2v2H8v-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold">{{ $BankName }}</h3>
                    <p class="text-sm text-blue-100">{{ $BankTag }}</p>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6 bg-white border-2 border-blue-100 shadow-lg rounded-b-2xl">
            <!-- Instructions -->
            <div class="mb-6">
                <h4 class="flex items-center gap-2 mb-4 text-base font-semibold text-gray-800">
                    <div class="flex items-center justify-center w-5 h-5 bg-blue-600 rounded-full">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    Langkah Pembayaran
                </h4>
                <div class="space-y-2">
                    <div class="flex items-start gap-3 text-sm text-gray-700">
                        <span
                            class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                        <span>Catat nomor rekening dan nama pemilik di bawah</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-gray-700">
                        <span
                            class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                        <span>Lakukan transfer sesuai total pesanan ke rekening berikut</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-gray-700">
                        <span
                            class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                        <span>Upload bukti pembayaran pada form di bawah</span>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-gray-700">
                        <span
                            class="w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">4</span>
                        <span>Pesanan akan diproses setelah pembayaran terverifikasi</span>
                    </div>
                </div>
            </div>

            <!-- Account Details Card -->
            <div
                class="relative p-5 overflow-hidden border-2 border-blue-200 bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-2xl">
                <div class="absolute w-24 h-24 bg-blue-500 rounded-full -top-6 -right-6 opacity-5"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm0 4v8h16V8H4zm2 2h2v2H6v-2z" />
                        </svg>
                        <span class="font-bold text-blue-900">Detail Rekening Transfer</span>
                    </div>

                    <!-- Account Number -->
                    <div class="p-4 mb-3 bg-white border border-blue-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">Nomor
                                    Rekening</div>
                                <div id="test" class="font-mono text-xl font-bold tracking-wider text-blue-900">
                                    {{ $BankNumber }}</div>
                            </div>
                            {{-- <span
                                class="flex items-center justify-center px-3 ml-3 text-white transition-all duration-200 bg-blue-600 rounded-lg cursor-pointer hover:bg-blue-700 hover:scale-105 group aspect-square"
                                onclick="copyToClipboardNoSpace('test', 'Nomor rekening berhasil disalin!'); this.classList.add('active'); setTimeout(() => this.classList.remove('active'), 200);"
                                title="Copy No. Rekening" style="transition: box-shadow 0.2s;"
                                onmousedown="this.style.boxShadow='0 0 0 4px #3b82f633';"
                                onmouseup="this.style.boxShadow='';" onmouseleave="this.style.boxShadow='';">
                                <i class="text-xl transition-transform ti ti-copy group-hover:scale-110"></i>
                            </span>
                            <script>
                                function copyToClipboardNoSpace(elementId, message) {
                                    const el = document.getElementById(elementId);
                                    if (!el) {
                                        alert('Element not found!');
                                        return;
                                    }
                                    const text = el.textContent.replace(/\s+/g, '');
                                    navigator.clipboard.writeText(text).then(() => {
                                        // Show toast message
                                        const toast = document.getElementById('copy-toast');
                                        const msg = document.getElementById('copy-message');
                                        if (msg) msg.textContent = message || 'Berhasil disalin!';
                                        if (toast) {
                                            toast.style.transform = 'translateX(0)';
                                            setTimeout(() => {
                                                toast.style.transform = 'translateX(100%)';
                                            }, 2000);
                                        }
                                    });
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire(
                                            'Berhasil!',
                                            'Nomor rekening berhasil disalin.',
                                            'success'
                                        );
                                    }
                                }
                            </script> --}}
                        </div>
                    </div>

                    <!-- Account Name -->
                    <div class="p-4 bg-white border border-blue-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">Nama Pemilik
                                </div>
                                <div id="nama-pemilik" class="text-lg font-bold text-blue-900">{{ $BankRecipient }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy Instructions -->
            {{-- <div class="flex items-center gap-2 mt-3 text-xs text-gray-500">
                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                <span>Klik tombol copy untuk menyalin data rekening</span>
            </div> --}}
        </div>
    </label>
    <div class="hidden mt-1 text-sm text-red-500 invalid-feedback"></div>

    <!-- Success Toast (Hidden by default) -->
    {{-- <div id="copy-toast"
        class="fixed z-50 flex items-center gap-2 px-4 py-3 text-white transition-transform duration-300 transform translate-x-full bg-green-500 rounded-lg shadow-lg top-4 right-4">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
        </svg>
        <span id="copy-message">Berhasil disalin!</span>
    </div> --}}
</div>
