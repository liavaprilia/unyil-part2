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
                    <h2 class="text-xl font-semibold text-gray-900">Pendapatan</h2>
                    <select id="incomeFilter"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="week">Minggu Ini &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <option value="month">Bulan Ini</option>
                    </select>
                </div>
                <div id="incomeChart" class="h-64"></div>
            </div>

            <!-- Order Chart -->
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Pesanan</h2>
                    <select id="orderFilter"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="week">Minggu Ini &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <option value="month">Bulan Ini</option>
                    </select>
                </div>
                <div id="orderChart" class="h-64"></div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2">

            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                <div class="space-y-3 max-h-[26vh] pr-6 items-scroll overflow-hidden overflow-y-scroll" id="latest-orders-list">
                    @foreach ($transactions as $transaction)
                    <div class="flex items-center justify-between py-2 transition-all duration-500 translate-y-4 border-b border-gray-100 opacity-0">
                        <a href="{{ route('admin.transactions.show', $transaction->id_transaction) }}" class="flex items-center justify-between w-full">
                            <div>
                                <p class="font-medium text-gray-900">#ORD-{{ explode('-', $transaction->id_transaction)[0] }}</p>
                                <p class="text-sm text-gray-600">{{ ucfirst($transaction->tshipping_receipt_name) }}</p>
                            </div>
                            <span
                            @if ($transaction->transaction_status == 'processed')
                                class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">{{ ucfirst($transaction->transaction_status) }}</span>
                            @elseif ($transaction->transaction_status == 'shipped')
                                class="px-2 py-1 text-xs font-medium text-orange-800 bg-orange-100 rounded-full">{{ ucfirst($transaction->transaction_status) }}</span>
                            @elseif ($transaction->transaction_status == 'completed')
                                class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">{{ ucfirst($transaction->transaction_status) }}</span>
                            @endif
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Produk Terlaris</h3>
                <div class="space-y-3 max-h-[26vh] pr-6 items-scroll overflow-hidden overflow-y-scroll" id="popular-products-list">
                    @foreach ($popularProducts as $product)
                        <div class="flex items-center justify-between py-2 transition-all duration-500 translate-y-4 border-b border-gray-100 opacity-0">
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

        <script>
            // Animasi slide muncul untuk setiap item
            window.addEventListener('DOMContentLoaded', function() {
                function animateListItems(containerId) {
                    const items = document.querySelectorAll(`#${containerId} > div`);
                    items.forEach((item, idx) => {
                        setTimeout(() => {
                            item.classList.remove('opacity-0', 'translate-y-4');
                            item.classList.add('opacity-100', 'translate-y-0');
                        }, idx * 520);
                    });
                }
                animateListItems('latest-orders-list');
                animateListItems('popular-products-list');
            });
        </script>

    </div>

    <script>
        // Convert PHP data to JavaScript
        const dailyRevenue = @json($dailyRevenue);
        const dailyOrders = @json($dailyOrders);

        // Global chart instances
        let incomeChart;
        let orderChart;

        // Utility functions
        function getWeekData(data) {
            const today = new Date();
            const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);

            return data.filter(item => {
                const itemDate = new Date(item.date);
                return itemDate >= weekAgo && itemDate <= today;
            });
        }

        function getMonthData(data) {
            const today = new Date();
            const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());

            return data.filter(item => {
                const itemDate = new Date(item.date);
                return itemDate >= monthAgo && itemDate <= today;
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { month: 'short', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function getDayName(dateString) {
            const date = new Date(dateString);
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            return days[date.getDay()];
        }

        // Income Chart Configuration
        function createIncomeChart(data, period) {
            const categories = data.map(item =>
                period === 'week' ? getDayName(item.date) : formatDate(item.date)
            );
            const values = data.map(item => parseFloat(item.total) || 0);

            const config = {
                series: [{
                    name: 'Pendapatan',
                    data: values
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
                    categories: categories,
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
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value;
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

            return config;
        }

        // Order Chart Configuration
        function createOrderChart(data, period) {
            const categories = data.map(item =>
                period === 'week' ? getDayName(item.date) : formatDate(item.date)
            );
            const values = data.map(item => parseInt(item.count) || 0);

            const config = {
                series: [{
                    name: 'Pesanan',
                    data: values
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
                    categories: categories,
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

            return config;
        }

        // Initialize charts
        function initializeCharts() {
            // Initialize with week data
            const weekRevenueData = getWeekData(dailyRevenue);
            const weekOrderData = getWeekData(dailyOrders);

            // Create income chart
            const incomeConfig = createIncomeChart(weekRevenueData, 'week');
            incomeChart = new ApexCharts(document.querySelector("#incomeChart"), incomeConfig);
            incomeChart.render();

            // Create order chart
            const orderConfig = createOrderChart(weekOrderData, 'week');
            orderChart = new ApexCharts(document.querySelector("#orderChart"), orderConfig);
            orderChart.render();
        }

        // Update charts based on filter
        function updateIncomeChart(period) {
            const data = period === 'week' ? getWeekData(dailyRevenue) : getMonthData(dailyRevenue);
            const config = createIncomeChart(data, period);

            incomeChart.updateOptions({
                xaxis: config.xaxis,
                yaxis: config.yaxis
            });
            incomeChart.updateSeries(config.series);
        }

        function updateOrderChart(period) {
            const data = period === 'week' ? getWeekData(dailyOrders) : getMonthData(dailyOrders);
            const config = createOrderChart(data, period);

            orderChart.updateOptions({
                xaxis: config.xaxis,
                yaxis: config.yaxis
            });
            orderChart.updateSeries(config.series);
        }

        // Event listeners for filters
        document.getElementById('incomeFilter').addEventListener('change', function() {
            updateIncomeChart(this.value);
        });

        document.getElementById('orderFilter').addEventListener('change', function() {
            updateOrderChart(this.value);
        });

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

        // Initialize everything when page loads
        window.addEventListener('load', function() {
            // Initialize charts
            initializeCharts();

            // Animate stats
            animateValue('totalOrders', 0, {{ $transactionsCount }}, 1500);
            animateValue('processedOrders', 0, {{ $transactionsProcessedCount }}, 1500);
            animateValue('shippedOrders', 0, {{ $transactionsShippedCount }}, 1500);
            animateValue('completedOrders', 0, {{ $transactionsDoneCount }}, 1500);
            animateValue('totalProducts', 0, {{ $productsCount }}, 1500);
        });
    </script>
</x-app-layout>
