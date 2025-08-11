<x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">Dashboard Penjual</h1>
            <p class="text-gray-600">Kelola dan pantau penjualan Anda dengan mudah</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-5">
            <!-- Total Pesanan Masuk -->
            <div class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-600">Total Pesanan Masuk</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalOrders">100</p>
                        {{-- <p class="mt-1 text-sm text-green-600">+12% dari bulan lalu</p> --}}
                    </div>
                    <div class="px-4 py-3 bg-blue-100 rounded-lg">
                        <span class="text-xl text-blue-600 ti ti-package"></span>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan Diproses -->
            <div class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-600">Total Pesanan Diproses</p>
                        <p class="text-3xl font-bold text-gray-900" id="processedOrders">100</p>
                        {{-- <p class="mt-1 text-sm text-green-600">+5% dari bulan lalu</p> --}}
                    </div>
                    <div class="px-4 py-3 bg-yellow-100 rounded-lg">
                        <span class="text-xl text-yellow-600 ti ti-analyze"></span>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan Dikirim -->
            <div class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-600">Total Pesanan Dikirim</p>
                        <p class="text-3xl font-bold text-gray-900" id="shippedOrders">100</p>
                        {{-- <p class="mt-1 text-sm text-green-600">+8% dari bulan lalu</p> --}}
                    </div>
                    <div class="px-4 py-3 bg-orange-100 rounded-lg">
                        <span class="text-xl text-orange-600 ti ti-truck-delivery"></span>
                    </div>
                </div>
            </div>

            <!-- Total Pesanan Selesai -->
            <div class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-600">Total Pesanan Selesai</p>
                        <p class="text-3xl font-bold text-gray-900" id="completedOrders">100</p>
                        {{-- <p class="mt-1 text-sm text-green-600">+15% dari bulan lalu</p> --}}
                    </div>
                    <div class="px-4 py-3 bg-green-100 rounded-lg">
                        <span class="text-xl text-green-600 ti ti-circle-check"></span>
                    </div>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-600">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalProducts">100</p>
                        {{-- <p class="mt-1 text-sm text-blue-600">3 produk baru</p> --}}
                    </div>
                    <div class="px-4 py-3 bg-purple-100 rounded-lg">
                        <span class="text-xl text-purple-600 ti ti-box"></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <!-- Income Chart -->
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Pendapatan Harian</h2>
                    <select
                        class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>7 Hari Terakhir</option>
                        <option>30 Hari Terakhir</option>
                        <option>3 Bulan Terakhir</option>
                    </select>
                </div>
                <div id="incomeChart" class="h-80"></div>
            </div>

            <!-- Order Chart -->
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Pesanan Harian</h2>
                    <select
                        class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>7 Hari Terakhir</option>
                        <option>30 Hari Terakhir</option>
                        <option>3 Bulan Terakhir</option>
                    </select>
                </div>
                <div id="orderChart" class="h-80"></div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2">

            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                <div class="space-y-3">

                    @foreach ($transactions as $transaction)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">#ORD-{{ explode('-', $transaction->id_transaction)[0] }}</p>
                                <p class="text-sm text-gray-600">{{ ucfirst($transaction->tshipping_receipt_name) }}</p>
                            </div>
                            <span
                            @if ($transaction->transaction_status == 'processed')
                                class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">{{ ucfirst($transaction->transaction_status) }}</span>
                            {{-- @elseif ($transaction->transaction_status == 'processed')
                                class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">{{ $transaction->transaction_status }}</span> --}}
                            @elseif ($transaction->transaction_status == 'shipped')
                                class="px-2 py-1 text-xs font-medium text-orange-800 bg-orange-100 rounded-full">{{ ucfirst($transaction->transaction_status) }}</span>
                            @elseif ($transaction->transaction_status == 'completed')
                                class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">{{ ucfirst($transaction->transaction_status) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Produk Terlaris</h3>
                <div class="space-y-3">

                    @foreach ($popularProducts as $product)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->total_qty }} terjual</p>
                            </div>
                            <p class="text-sm font-medium text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>

    <script>
        // Sample data for charts
        const incomeData = {
            series: [{
                name: 'Pendapatan',
                data: [8500000, 12300000, 9800000, 15600000, 11200000, 13800000, 16500000]
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#3B82F6'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            grid: {
                show: true,
                borderColor: '#F3F4F6',
                strokeDashArray: 1,
                xaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            xaxis: {
                categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    },
                    formatter: function(value) {
                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        };

        const orderData = {
            series: [{
                name: 'Pesanan',
                data: [12, 18, 14, 22, 16, 19, 24]
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#10B981'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '40%'
                }
            },
            grid: {
                show: true,
                borderColor: '#F3F4F6',
                strokeDashArray: 1,
                xaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + ' pesanan';
                    }
                }
            }
        };

        // Initialize charts
        const incomeChart = new ApexCharts(document.querySelector("#incomeChart"), incomeData);
        const orderChart = new ApexCharts(document.querySelector("#orderChart"), orderData);

        incomeChart.render();
        orderChart.render();

        // Animate numbers on page load
        function animateValue(id, start, end, duration) {
            const obj = document.getElementById(id);
            const range = end - start;
            const minTimer = 50;
            let stepTime = Math.abs(Math.floor(duration / range));
            stepTime = Math.max(stepTime, minTimer);
            const startTime = new Date().getTime();
            const endTime = startTime + duration;
            let timer;

            function run() {
                const now = new Date().getTime();
                const remaining = Math.max((endTime - now) / duration, 0);
                const value = Math.round(end - (remaining * range));
                obj.innerHTML = value;
                if (value === end) {
                    clearInterval(timer);
                }
            }

            timer = setInterval(run, stepTime);
            run();
        }

        // Animate stats on page load
        window.addEventListener('load', function() {
            animateValue('totalOrders', 0, {{ $transactionsCount }}, 1500);
            animateValue('processedOrders', 0, {{ $transactionsProcessedCount }}, 1500);
            animateValue('shippedOrders', 0, {{ $transactionsShippedCount }}, 1500);
            animateValue('completedOrders', 0, {{ $transactionsDoneCount }}, 1500);
            animateValue('totalProducts', 0, {{ $productsCount }}, 1500);
        });
    </script>
</x-app-layout>
