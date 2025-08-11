<x-app-layout>

    <style>
        /* Table */
        #cart-table {
            border: 2px solid #000;
            background: #fff;
        }

        #cart-table thead {
            /* background: #000; */
            /* color: #fff; */
        }

        #cart-table_filter {
            margin-bottom: 1rem;
        }

        #cart-table_paginate,
        #cart-table_info {
            margin-top: 1rem;
        }

        #cart-table th,
        #cart-table td {
            border-color: #222 !important;
        }

        #cart-table tbody tr {
            transition: background 0.2s;
        }

        #cart-table tbody tr:hover {
            background: #e5e5e5 !important;
        }

        /* DataTables controls */
        .dataTables_wrapper .dataTables_filter input {
            background: #fff;
            color: #000;
            border: 1px solid #000;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            background: #fff;
            color: #000;
            border: 1px solid #000;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #fff !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            border-radius: 0.25rem !important;
            margin: 0 2px;
            transition: background 0.2s, color 0.2s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            /* background: #000 !important; */
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_info {
            color: #222;
        }

        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label {
            color: #222;
        }

        #cart-table thead th {
            font-weight: bold;
            text-align: center;
        }
    </style>

    <div class="container px-4 py-6 mx-auto">
        <div class="overflow-x-auto">
            <table id="cart-table" class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">No</th>
                        <th class="px-4 py-2 border-b">Produk</th>
                        <th class="px-4 py-2 border-b">Harga</th>
                        <th class="px-4 py-2 border-b">Subtotal</th>
                        <th class="px-4 py-2 border-b">Jumlah</th>
                        <th class="px-4 py-2 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-4 mt-4 md:flex-row md:items-center md:justify-between">
            <div class="text-lg font-semibold text-black">
                Total Harga: <span id="cart-total-price">Rp {{ $total_price }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('products') }}"
                    class="px-4 py-2 text-black transition bg-white border border-black rounded hover:bg-gray-100">
                    Lanjutkan Belanja
                </a>
                <a href="javascript:void(0)" id="btn-checkout"
                    class="px-4 py-2 text-white transition bg-black border border-white rounded opacity-50 cursor-not-allowed hover:bg-gray-900">
                    Checkout
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#cart-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('cart.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'product_name',
                            name: 'product_name'
                        },
                        {
                            data: 'product_price',
                            name: 'product_price'
                        },
                        {
                            data: 'total_product_price',
                            name: 'total_product_price'
                        },
                        {
                            data: 'cart_qty',
                            name: 'cart_qty',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    language: {
                        processing: "Memproses...",
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        loadingRecords: "Sedang memuat...",
                        zeroRecords: "Tidak ada data yang ditemukan",
                        emptyTable: "Keranjang belanja kosong",
                        infoThousands: ".",
                        decimal: ",",
                        thousands: ".",
                        infoPostFix: "",
                        searchPlaceholder: "Cari produk...",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    drawCallback: function(settings) {
                        // Cek jumlah data yang ditampilkan
                        let dataCount = this.api().data().count();

                        // Aktifkan atau nonaktifkan tombol Checkout
                        if (dataCount === 0) {
                            $('#btn-checkout').prop('disabled', true)
                                .addClass('opacity-50 cursor-not-allowed')
                                .attr('href', 'javascript:void(0)')
                                .css({
                                    'cursor': 'not-allowed'
                                });
                        } else {
                            $('#btn-checkout').prop('disabled', false)
                                .removeClass('opacity-50 cursor-not-allowed')
                                .attr('href', "{{ route('checkout.index') }}")
                                .css({
                                    'cursor': ''
                                });
                        }
                    }
                });

                function debounceCounter(func, wait) {
                    let timeout;
                    let counter = 1;
                    return function(...args) {
                        const context = this;
                        clearTimeout(timeout);

                        timeout = setTimeout(() => {
                            func.apply(context, [counter, ...args]);
                            counter = 1; // Reset counter setelah berhenti klik
                        }, wait);

                        // Tambah counter hanya jika sudah pernah klik (klik kedua dst)
                        if (counter > 1) {
                            counter++;
                        }
                    };
                }

                // Update quantity
                const updateCartHandler = debounceCounter(function(quantity, event) {
                    let productId = $(this).data('id');
                    const action = $(this).data('action') || 'add';
                    const button = $(this);
                    $.ajax({
                        url: "{{ route('cart.update') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_product: productId,
                            quantity: quantity,
                            action: action
                        },
                        success: function(res) {
                            updateCartBadge(res.data.total_items);

                            let row = button.closest('tr');
                            const quantityInput = row.find('.quantity-input');
                            const productPrice = row.find('.product-price');
                            const cartTotalPrice = $('#cart-total-price');

                            if (quantityInput.length) {
                                quantityInput.val(res.data.cart_qty).trigger('change');
                            }

                            if (productPrice.length) {
                                productPrice.html("Rp " + (res.data.total_price)
                                    .toLocaleString('id-ID', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }));
                            }

                            if (cartTotalPrice.length) {
                                cartTotalPrice.html("Rp " + (res.data.cart_total_price)
                                    .toLocaleString('id-ID', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }));
                                // :v mager inject pas rubah desain jadi bisa diskon tu
                                table.ajax.reload(null, false);
                            }

                            if (res.data.cart_qty <= 0) {
                                table.row(row).remove().draw();
                            }
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message ||
                                    'Gagal menambah ke keranjang'
                            });
                        }
                    });
                }, 400);

                $('#cart-table').on('click', '.btn-update-cart', updateCartHandler);

                // Delete cart item
                $('#cart-table').on('click', '.btn-delete-cart', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Hapus Item',
                        text: "Apakah Anda yakin ingin menghapus item ini dari keranjang?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('cart.destroy', '') }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id_cart: id
                                },
                                success: function(res) {
                                    table.ajax.reload(null, false);
                                    updateCartBadge(res.data.total_items);
                                    Swal.fire(
                                        'Terhapus!',
                                        'Baranag telah dihapus dari keranjang.',
                                        'success'
                                    );
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Gagal!',
                                        xhr.responseJSON?.message ||
                                        'Gagal menghapus barang.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
