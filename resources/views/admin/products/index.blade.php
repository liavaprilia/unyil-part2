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

    <div class="container px-2 py-6 mx-auto">
        <div class="overflow-x-auto">
            <div class="flex justify-end w-full">
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-black transition bg-white border border-black rounded hover:bg-black hover:text-white">
                    <span class="mr-2 ti ti-plus"></span> Tambah Data
                </a>
            </div>
            <table id="products-table" class="table min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">No</th>
                        <th class="px-4 py-2 border-b" style="width: 8rem;">Gambar</th>
                        <th class="px-4 py-2 border-b">Nama</th>
                        <th class="px-4 py-2 border-b">Harga</th>
                        <th class="px-4 py-2 border-b">Diskon</th>
                        <th class="px-4 py-2 border-b">Deskripsi</th>
                        <th class="px-4 py-2 border-b">Stok</th>
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
                $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.products.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'product_image',
                            name: 'product_image',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'product_name',
                            name: 'product_name'
                        },
                        {
                            data: 'formatted_price',
                            name: 'product_price'
                        },
                        {
                            data: 'formatted_discount',
                            name: 'product_discount'
                        },
                        {
                            data: 'product_desc',
                            name: 'product_desc'
                        },
                        {
                            data: 'product_stock',
                            name: 'product_stock'
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
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
