<x-guest-layout>
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Verifikasi Email Diperlukan</h2>
        
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            <p class="mb-2">Terima kasih telah mendaftar di <strong>{{ config('app.name') }}</strong>!</p>
            <p class="mb-2">Untuk keamanan akun Anda, kami memerlukan verifikasi email sebelum Anda dapat mengakses aplikasi.</p>
            <p>Silakan cek inbox email Anda dan klik tautan verifikasi yang telah kami kirimkan ke <strong>{{ Auth::user()->email }}</strong></p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                <strong>Email Terkirim!</strong> Tautan verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="mt-6 space-y-3">
            <div class="border-t pt-4">
                <p class="text-sm text-gray-500 mb-3">Tidak menerima email?</p>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
                        <i class="ti ti-mail-forward mr-2"></i>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-2">Atau</p>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 underline">
                        Keluar dan login dengan akun lain
                    </button>
                </form>
            </div>
        </div>
        
        <div class="mt-6 p-3 bg-yellow-50 border border-yellow-200 rounded">
            <p class="text-xs text-gray-600">
                <strong>Tips:</strong> Cek folder spam/junk jika email tidak ditemukan di inbox.
            </p>
        </div>
    </div>
</x-guest-layout>
