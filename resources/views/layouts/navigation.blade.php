<nav x-data="{ open: false }" class="relative bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="flex items-center justify-between h-16 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- Logo -->
        <div class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
            <span class="text-4xl font-extrabold"
                style="color: #FCC1AF; letter-spacing: 0.02em; text-shadow: 1px 1px 0 #f9a88f, 2px 2px 0 #f9a88f;">
                Toko Unyil
            </span>
        </div>

        <!-- Left Side Of Navbar -->
        <div class="flex items-center space-x-8">
            @hasrole('User')
                <a href="{{ url('/') }}">
                    <div x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false"
                        :class="(hovered || {{ request()->routeIs('home') ? 'true' : 'false' }}) ? 'border-b border-black' : ''"
                        class="transition-all cursor-pointer">
                        Home
                    </div>
                </a>
                <a href="{{ route('products') }}">
                    <div x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false"
                        :class="(hovered || {{ request()->routeIs('products') ? 'true' : 'false' }}) ? 'border-b border-black' :
                        ''"
                        class="transition-all cursor-pointer">
                        Produk
                    </div>
                </a>
                <a href="{{ route('orders') }}">
                    <div x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false"
                        :class="(hovered || {{ request()->routeIs('orders') ? 'true' : 'false' }}) ? 'border-b border-black' : ''" class="transition-all cursor-pointer">
                        Pesanan
                    </div>
                </a>
            @endhasrole
        </div>

        <!-- Right Side Of Navbar -->
        <div>
            @auth
                <div class="flex items-center space-x-2 text-gray-700">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center h-8 hover:underline">{{ Auth::user()->name }}</a>
                    <span class="flex items-center h-8">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center h-8">
                        @csrf
                        <button type="submit" class="hover:underline">Log Out</button>
                    </form>
                    @hasrole('User')
                        <a href="{{ route('cart.index') }}" class="relative flex items-center h-8 ps-8 group wrapper-cart">
                            {{-- <a href="{{route('cart')}}" class="relative flex items-center h-8 ps-8 group"> --}}
                            <span
                                class="text-3xl transition-transform duration-200 ti ti-brand-appgallery group-hover:scale-110 group-hover:text-[#f9a88f]"></span>
                            @php
                                $cartCount = \App\Models\Cart::where('cart_user_id', auth()->id())->count();
                            @endphp
                            <span
                                class="absolute {{ $cartCount > 0 ? '' : 'hidden' }} cart-badge flex items-center justify-center w-5 h-5 text-xs font-bold text-white rounded-full shadow-lg bg-gradient-to-tr from-[#f9a88f] to-pink-500 -top-1 -right-2 animate-bounce">
                                {{ $cartCount ? $cartCount : '' }}
                            </span>
                        </a>
                    @endhasrole
                </div>
            @else
                <h1 class="text-gray-700 ">
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                    <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <a href="{{ route('register') }}" class="hover:underline">Register</a>
                </h1>
            @endauth
        </div>

    </div>
</nav>
