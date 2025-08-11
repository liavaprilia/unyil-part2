<x-app-layout>

    <style>
        /* Table */
        #products-table {
            border: 2px solid #000;
            background: #fff;
        }

        #products-table thead {
            /* background: #000; */
            /* color: #fff; */
        }

        #products-table_filter {
            margin-bottom: 1rem;
        }

        #products-table_paginate,
        #products-table_info {
            margin-top: 1rem;
        }

        #products-table th,
        #products-table td {
            border-color: #222 !important;
        }

        #products-table tbody tr {
            transition: background 0.2s;
        }

        #products-table tbody tr:hover {
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

        #products-table thead th {
            font-weight: bold;
            text-align: center;
        }
    </style>

    <div class="container px-4 py-6 mx-auto">
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Daftar Pemesanan</h1>
        <div class="overflow-x-auto">
            <table id="products-table" class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: '{{ route('admin.transactions.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tshipping_receipt_name',
                        name: 'tshipping_receipt_name',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
                    // id transaction
                    {
                        data: 'id_transaction',
                        name: 'id_transaction',
                        visible: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
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
                    emptyTable: "Tidak ada data yang tersedia di tabel ini",
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
                order: [
                    [8, 'desc']
                ]
            });
        });
    </script>
</x-app-layout>
