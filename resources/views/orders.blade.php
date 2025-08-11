<x-app-layout>

    <style>
        /* Table */
        #orders-table {
            border: 2px solid #000;
            background: #fff;
        }

        #orders-table thead {
            /* background: #000; */
            /* color: #fff; */
        }

        #orders-table_filter {
            margin-bottom: 1rem;
        }

        #orders-table_paginate,
        #orders-table_info {
            margin-top: 1rem;
        }

        #orders-table th,
        #orders-table td {
            border-color: #222 !important;
        }

        #orders-table tbody tr {
            transition: background 0.2s;
        }

        #orders-table tbody tr:hover {
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

        #orders-table thead th {
            font-weight: bold;
            text-align: center;
        }
    </style>

    <div class="container px-4 py-6 mx-auto">
        <div class="overflow-x-auto">
            <table id="orders-table" class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">No</th>
                        <th class="px-4 py-2 border-b">ID Transaksi</th>
                        <th class="px-4 py-2 border-b">Nama Customer</th>
                        <th class="px-4 py-2 border-b">Tanggal</th>
                        <th class="px-4 py-2 border-b">Qty</th>
                        <th class="px-4 py-2 border-b">Harga</th>
                        <th class="px-4 py-2 border-b">Status</th>
                        <th class="px-4 py-2 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#orders-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('orders') }}",
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'id_transaction',
                            name: 'id_transaction',
                        },
                        {
                            data: 'tshipping_receipt_name',
                            name: 'tshipping_receipt_name',
                        },
                        {
                            data: 'created_at_display',
                            name: 'created_at_display'
                        },
                        {
                            data: 'details_count',
                            name: 'details_count'
                        },
                        {
                            data: 'total_price',
                            name: 'total_price'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            visible: false
                        }
                    ],
                    order: [
                        [8, 'desc']
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
                        emptyTable: "Data pesanan kosong",
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
                });
            });
        </script>
    @endpush
</x-app-layout>
