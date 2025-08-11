<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="max-w-md p-8 mx-auto bg-white rounded shadow-md">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="'Email'" class="text-black" />
            <x-text-input id="email"
                class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded focus:border-gray-500 focus:ring-gray-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-gray-500" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-black" />
            <x-text-input id="password"
                class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded focus:border-gray-500 focus:ring-gray-500"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-gray-500" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" value="1"
                    class="text-gray-700 border-gray-300 rounded shadow-sm focus:ring-gray-500" name="remember">
                <span class="text-sm text-gray-700 ms-2">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 mt-6 md:flex-row">
            <div>
                <a class="text-sm text-gray-700 underline hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                    href="{{ route('register') }}">
                    {{ __('Belum punya akun?') }}
                </a>
                {{-- @if (Route::has('password.request'))
                    <a class="text-sm text-gray-700 underline hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif --}}
            </div>
            <x-primary-button class="text-white bg-gray-900 hover:bg-gray-700 focus:ring-gray-500">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
