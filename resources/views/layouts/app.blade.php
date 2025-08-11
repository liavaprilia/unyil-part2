<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Toko Unyil') }}</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3718/3718330.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .inria-sans-light {
            font-family: "Inria Sans", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .inria-sans-regular {
            font-family: "Inria Sans", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .inria-sans-bold {
            font-family: "Inria Sans", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .inria-sans-light-italic {
            font-family: "Inria Sans", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .inria-sans-regular-italic {
            font-family: "Inria Sans", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .inria-sans-bold-italic {
            font-family: "Inria Sans", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hide arrows in Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        #order-items-scroll::-webkit-scrollbar {
            width: 6px;
            background: #f1f1f1;
            border-radius: 8px;
        }

        #order-items-scroll::-webkit-scrollbar-thumb {
            background: #bdbdbd;
            border-radius: 8px;
        }

        #order-items-scroll {
            scrollbar-width: thin;
            scrollbar-color: #bdbdbd #f1f1f1;
        }

        .items-scroll::-webkit-scrollbar {
            width: 4px;
            background: #f1f1f1;
            border-radius: 8px;
        }

        .items-scroll::-webkit-scrollbar-thumb {
            background: #bdbdbd;
            border-radius: 8px;
        }

        .items-scroll {
            scrollbar-width: thin;
            scrollbar-color: #bdbdbd #f1f1f1;
        }

        /* Hide default DataTables loading */
        /* .dataTables_processing {
            display: none !important;
        } */

        .dataTables_wrapper,
        .dataTables_scroll {
            position: relative;
            min-height: 370px;
        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute !important;
            top: 50% !important;
            left: 60% !important;
            transform: translate(-50%, -50%) !important;

            min-width: 200px !important;
            min-height: 50px !important;

            background-color: #f1f1f1 !important;
            color: #333 !important;
            font-weight: bold;
            font-size: 1rem;
            text-align: center;

            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 999;
        }

        /* Custom loading row styles */
        .loading-row {
            background: #f8f9fa;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="https://unpkg.com/imask"></script>
</head>

{{-- <body class="flex flex-col min-h-screen font-sans antialiased bg-gray-100">
    <x-sidebar/>
    <div class="flex flex-col flex-1">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>
    <x-footer/>
</body> --}}

<body class="flex min-h-screen font-sans antialiased bg-gray-100" style="min-width: 360px;">
    <!-- Sidebar kiri -->
    @auth
        @if (auth()->user()->hasRole('Admin'))
            <aside class="sticky top-0 z-40 h-screen bg-white border-r shadow-sm w-44 min-w-[176px]">
                <x-sidebar />
            </aside>
        @endif
    @endauth

    <!-- Konten utama -->
    <div class="flex flex-col items-center flex-1 min-w-0" style="min-width: 0;">
        <div class="sticky top-0 z-30 flex justify-center w-full min-w-0">
            <div class="w-full max-w-[1800px]">
                @include('layouts.navigation')
            </div>
        </div>

        <!-- Page Heading -->
        @isset($header)
            <header class="flex justify-center w-full min-w-0 bg-white shadow">
                <div class="w-full min-w-0 px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex justify-center flex-1 w-full">
            <div class="w-full max-w-[1800px] overflow-x-auto min-w-[320px]">
                {{ $slot }}
            </div>
        </main>

        <div class="flex justify-center w-full">
            <div class="w-full max-w-[1800px]">
                <x-footer />
            </div>
        </div>
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        // // Custom DataTable loading functionality
        // function addDataTableLoading() {
        //     // Check for tables that are already DataTables
        //     $('.dataTable').each(function() {
        //         const table = $(this);
        //         const tbody = table.find('tbody');
        //         const columnCount = table.find('thead th').length;

        //         // Skip if already processed
        //         if (table.data('custom-loading-added')) return;
        //         table.data('custom-loading-added', true);

        //         // Add loading row
        //         const loadingRow = `
    //             <tr class="loading-row custom-loading-row">
    //                 <td colspan="${columnCount}" class="py-8 text-center">
    //                     <div class="flex items-center justify-center space-x-2">
    //                         <svg class="w-5 h-5 text-gray-600 animate-spin" xmlns="http://www.w3.org/2000/svg"
    //                             fill="none" viewBox="0 0 24 24">
    //                             <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
    //                                 stroke-width="4"></circle>
    //                             <path class="opacity-75" fill="currentColor"
    //                                 d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    //                         </svg>
    //                         <span class="text-sm text-gray-600">Memuat data...</span>
    //                     </div>
    //                 </td>
    //             </tr>
    //         `;

        //         // Show loading immediately if table is empty or has "No data available" message
        //         if (tbody.find('tr').length === 0 || tbody.find('tr').text().includes('No data available')) {
        //             tbody.find('.custom-loading-row').remove();
        //             tbody.html(loadingRow);
        //         }

        //         // Show loading when processing starts
        //         table.on('processing.dt', function(e, settings, processing) {
        //             if (processing) {
        //                 tbody.find('.custom-loading-row').remove();
        //                 tbody.empty(); // Clear all existing rows first
        //                 tbody.html(loadingRow); // Then add loading row
        //             } else {
        //                 tbody.find('.custom-loading-row').remove();
        //             }
        //         });

        //         // Also handle xhr events for AJAX loading
        //         table.on('xhr.dt', function() {
        //             tbody.find('.custom-loading-row').remove();
        //             tbody.empty(); // Clear all existing rows first
        //             tbody.html(loadingRow); // Then add loading row
        //         });

        //         // Remove loading when draw is complete
        //         table.on('draw.dt', function() {
        //             tbody.find('.custom-loading-row').remove();
        //         });
        //     });
        // }

        // // Initialize custom loading on document ready and also when new DataTables are created
        // $(document).ready(function() {
        //     // Initial check
        //     setTimeout(addDataTableLoading, 100);

        //     // Re-check periodically for new DataTables (reduce frequency)
        //     setInterval(function() {
        //         addDataTableLoading();
        //     }, 2000);
        // });

        // // Also hook into DataTable initialization
        // $(document).on('init.dt', function() {
        //     setTimeout(addDataTableLoading, 50);
        // });

        // // Hook into preInit for earlier detection
        // $(document).on('preInit.dt', function() {
        //     setTimeout(addDataTableLoading, 10);
        // });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // format rupiah on all input with class 'rupiah'
        $(document).ready(function() {
            $('.rupiah').each(function() {
                if ($(this).val().startsWith('0')) {
                    $(this).val($(this).val().substring(1));
                }
                console.log('Applying mask to input:', this);
                const input = $(this);
                const min = +input.data('min') || 1;
                const maskOptions = {
                    mask: 'Rp num',
                    blocks: {
                        num: {
                            mask: IMask.MaskedNumber,
                            thousandsSeparator: '.',
                            radix: ',',
                            scale: 0,
                            signed: false,
                            padFractionalZeros: false,
                            normalizeZeros: true,
                            min: min, // <-- minimum 1 ensures no 0
                            max: 999999999999,
                            unmask: true,
                            prepare: function(value) {
                                // Block input if it's '0' at start
                                if (value === '0') return '';
                                return value;
                            }
                        }
                    }
                };
                const maskedInput = IMask(input[0], maskOptions);
                // maskedInput.on('accept', function() {
                //     const value = maskedInput.unmaskedValue;
                //     input.val(value);
                // });
            });

            // make dynamic base-form
            // using ajax with class base-form
        });

        $(document).on('submit', '.base-form', function(e) {
            e.preventDefault();

            const form = $(this);

            const needConfirmation = form.data('need-confirmation') === true || form.data('need-confirmation') ===
                'true';
            const customOptions = form.data('options-confirm') || {};

            if (needConfirmation) {
                const defaultOptions = {
                    title: 'Apakah Anda yakin?',
                    text: 'Tindakan ini tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                };

                const swalOptions = {
                    ...defaultOptions,
                    ...customOptions
                };

                Swal.fire(swalOptions).then((result) => {
                    if (result.isConfirmed) {
                        handleAjaxSubmit(form); // jalankan fungsi submit ajax
                    }
                });
            } else {
                handleAjaxSubmit(form); // tanpa konfirmasi langsung submit
            }
        });

        function handleAjaxSubmit(form) {
            const formData = new FormData(form[0]);
            const url = form.attr('action');
            const method = form.attr('method') || 'POST';

            const submitButton = form.find('button[type="submit"]');
            const originalButtonText = submitButton.html();

            if (submitButton.length) {
                submitButton.prop('disabled', true);
                submitButton.html('<span class="text-2xl ti ti-loader ti-spin"></span> Loading...');
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (submitButton.length) submitButton.html(originalButtonText);

                    $('.form-group .invalid-feedback').addClass('hidden').text('');
                    $('input, textarea, select').removeClass('border-red-500');

                    Toast.fire({
                        icon: 'success',
                        title: response.message || 'Success!'
                    });

                    const callbackFunction = form.data('function-callback');
                    if (callbackFunction && typeof window[callbackFunction] === 'function') {
                        window[callbackFunction](response);
                    }
                },
                error: function(xhr) {
                    if (submitButton.length) {
                        submitButton.prop('disabled', false);
                        submitButton.html(originalButtonText);
                    }

                    const response = xhr.responseJSON;

                    if (response?.errors) {
                        const errors = response.errors;
                        // check the errors is string or not
                        if (typeof errors === 'string') {
                            Toast.fire({
                                icon: 'error',
                                title: response?.message || 'Terjadi kesalahan saat mengirim form.'
                            });
                            return;
                        }
                        const firstErrorField = Object.keys(errors)[0];
                        const firstErrorInput = firstErrorField.replace(/\./g, '\\.');
                        const firstErrorElement = $('input[name="' + firstErrorInput + '"], textarea[name="' +
                            firstErrorInput + '"], select[name="' + firstErrorInput +
                            '"], input[type="radio"][name="' + firstErrorInput + '"]').first();

                        if (firstErrorElement.length) {
                            if (firstErrorElement.is(':hidden')) {
                                firstErrorElement.closest('.form-group').get(0).scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            } else {
                                firstErrorElement.get(0).scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }
                        }

                        $('.form-group .invalid-feedback').addClass('hidden').text('');
                        $('input, textarea, select').removeClass('border-red-500');

                        for (const key in errors) {
                            const errorMessage = errors[key].join('<br>');
                            const inputName = key.replace(/\./g, '\\.');
                            const inputField = $('[name="' + inputName + '"]');

                            if (inputField.length) {
                                inputField.closest('.form-group')
                                    .find('input, textarea, select')
                                    .addClass('border-red-500');

                                inputField.closest('.form-group')
                                    .find('.invalid-feedback')
                                    .removeClass('hidden')
                                    .html(errorMessage);
                            }
                        }
                    }

                    Toast.fire({
                        icon: 'error',
                        title: response?.message || 'Terjadi kesalahan saat mengirim form.'
                    });
                },
                complete: function() {
                    if (submitButton.length) {
                        submitButton.prop('disabled', false);
                    }
                }
            });
        }

        $(document).on('input paste focus', 'input, textarea, select', function() {
            const inputField = $(this);

            // Hide the error message and remove red border on input, paste or focus
            inputField.closest('.form-group').find('.invalid-feedback').addClass('hidden');
            inputField.removeClass('border-red-500');
        });

        function deleteProduct(e) {
            const id = $(e).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data produk yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/products') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.table').DataTable().ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message || 'Gagal menghapus produk'
                                });
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            Toast.fire({
                                icon: 'error',
                                title: response?.message ||
                                    'Terjadi kesalahan saat menghapus produk'
                            });
                        }
                    });
                }
            });
        }

        function updateCartBadge(total) {
            if (total > 0) {
                $('.cart-badge').removeClass('hidden');
            } else {
                $('.cart-badge').addClass('hidden');
            }

            $('.cart-badge').html(total);
        }
    </script>

    @stack('scripts')
</body>

</html>