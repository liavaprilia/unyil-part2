<nav class="h-full py-6 space-y-4 text-gray-700">
    <div class="w-full mb-2 text-2xl font-bold text-center">
        <a href="{{ url('/') }}" class="text-gray-800">MENU</a>
    </div>

    <ul class="space-y-2">
        {{-- Dashboard view --}}
        @can('dashboard.view')
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 relative overflow-hidden transition
                        {{ request()->is('admin/dashboard*') ? 'bg-black text-white font-bold' : 'text-black' }}">
                    <span
                        class="absolute inset-0 z-0 transition-all duration-300 ease-out bg-black"
                        style="transform: translateX(-100%);"
                        x-data
                        x-init="
                            $el.parentElement.addEventListener('mouseenter',()=>{
                                $el.style.transform='translateX(0)';
                                $el.parentElement.classList.add('hovered');
                            });
                            $el.parentElement.addEventListener('mouseleave',()=>{
                                $el.style.transform='translateX(-100%)';
                                $el.parentElement.classList.remove('hovered');
                            });
                        "
                    ></span>
                    <span class="relative z-10 text-lg ti ti-dashboard"></span>
                    <span class="relative z-10">Dashboard</span>
                </a>
            </li>
        @endcan

        @can('dashboard.view')
            <li>
                <a href="{{ url('/home') }}"
                    class="flex items-center gap-3 px-3 py-2 relative overflow-hidden transition
                        {{ request()->is('home') ? 'bg-black text-white font-bold' : 'text-black' }}">
                    <span
                        class="absolute inset-0 z-0 transition-all duration-300 ease-out bg-black"
                        style="transform: translateX(-100%);"
                        x-data
                        x-init="
                            $el.parentElement.addEventListener('mouseenter',()=>{
                                $el.style.transform='translateX(0)';
                                $el.parentElement.classList.add('hovered');
                            });
                            $el.parentElement.addEventListener('mouseleave',()=>{
                                $el.style.transform='translateX(-100%)';
                                $el.parentElement.classList.remove('hovered');
                            });
                        "
                    ></span>
                    <span class="relative z-10 text-lg ti ti-home"></span>
                    <span class="relative z-10">Beranda Toko</span>
                </a>
            </li>
        @endcan

        @can('products.view')
            <li>
                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-3 px-3 py-2 relative overflow-hidden transition
                        {{ request()->is('admin/products*') ? 'bg-black text-white font-bold' : 'text-black' }}">
                    <span
                        class="absolute inset-0 z-0 transition-all duration-300 ease-out bg-black"
                        style="transform: translateX(-100%);"
                        x-data
                        x-init="
                            $el.parentElement.addEventListener('mouseenter',()=>{
                                $el.style.transform='translateX(0)';
                                $el.parentElement.classList.add('hovered');
                            });
                            $el.parentElement.addEventListener('mouseleave',()=>{
                                $el.style.transform='translateX(-100%)';
                                $el.parentElement.classList.remove('hovered');
                            });
                        "
                    ></span>
                    <span class="relative z-10 text-lg ti ti-package"></span>
                    <span class="relative z-10">Kelola Produk</span>
                </a>
            </li>
        @endcan

        @can('orders.view')
            <li>
                <a href="{{ route('admin.transactions.index') }}"
                    class="flex items-center gap-3 px-3 py-2 relative overflow-hidden transition
                        {{ request()->is('admin/transactions*') ? 'bg-black text-white font-bold' : 'text-black' }}">
                    <span
                        class="absolute inset-0 z-0 transition-all duration-300 ease-out bg-black"
                        style="transform: translateX(-100%);"
                        x-data
                        x-init="
                            $el.parentElement.addEventListener('mouseenter',()=>{
                                $el.style.transform='translateX(0)';
                                $el.parentElement.classList.add('hovered');
                            });
                            $el.parentElement.addEventListener('mouseleave',()=>{
                                $el.style.transform='translateX(-100%)';
                                $el.parentElement.classList.remove('hovered');
                            });
                        "
                    ></span>
                    <span class="relative z-10 text-lg ti ti-shopping-cart"></span>
                    <span class="relative z-10">Pemesanan</span>
                </a>
            </li>
        @endcan

        @can('products.view')
            <li>
                <a href="{{ route('products') }}"
                    class="flex items-center gap-3 px-3 py-2 relative overflow-hidden transition
                        {{ request()->is('products*') ? 'bg-black text-white font-bold' : 'text-black' }}">
                    <span
                        class="absolute inset-0 z-0 transition-all duration-300 ease-out bg-black"
                        style="transform: translateX(-100%);"
                        x-data
                        x-init="
                            $el.parentElement.addEventListener('mouseenter',()=>{
                                $el.style.transform='translateX(0)';
                                $el.parentElement.classList.add('hovered');
                            });
                            $el.parentElement.addEventListener('mouseleave',()=>{
                                $el.style.transform='translateX(-100%)';
                                $el.parentElement.classList.remove('hovered');
                            });
                        "
                    ></span>
                    <span class="relative z-10 text-lg ti ti-list-details"></span>
                    @php
                        $isAdmin = auth()->user()->hasRole('Admin');                        
                        if($isAdmin) {
                            $textProducts = 'Preview';
                        } else {
                            $textProducts = 'Produk';
                        }
                    @endphp
                    <span class="relative z-10">{{ $textProducts }}</span>
                </a>
            </li>
        @endcan

    </ul>
    <style>
        a.hovered .relative.z-10 {
            color: #fff !important;
            transition: color 0.2s;
        }
    </style>
</nav>
