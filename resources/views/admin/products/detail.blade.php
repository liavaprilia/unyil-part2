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
                @if ($product->product_discount > 0)
                    <div class="flex items-center mb-4 space-x-2">
                        <h2 class="text-3xl font-bold text-black inria-sans-light">Rp.
                            {{ number_format($product->product_price - $product->product_discount, 0, ',', '.') }}</h2>
                        <span
                            class="text-lg text-gray-400 line-through">Rp{{ number_format($product->product_price, 0, ',', '.') }}</span>
                        <span
                            class="text-sm font-semibold text-[#fb8763] bg-red-100 px-1 py-0.5 rounded">-{{ round(($product->product_discount / $product->product_price) * 100, 2) }}%</span>
                    </div>
                @else
                    <h2 class="mb-6 text-2xl font-semibold text-gray-900 bg-clip-text inria-sans-regular">Rp.
                        {{ number_format($product->product_price, 0, ',', '.') }}</h2>
                @endif
                <div class="flex items-center mb-6 space-x-3">
                    <span class="text-sm text-gray-500 inria-sans-regular">Tersedia: {{ $product->product_stock }}
                        Stok</span>
                </div>
            </div>

        </div>
</x-app-layout>
