@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Karyawan')

@section('breadcumbSubtitle', 'Karyawan List')

@section('content')

    <style>
        .dataTables_scrollBody {
            height: 52vh !important;
        }

        table th:nth-child(6) {
            min-width: 380px !important;
        }

        div#previewOnlyForPDFModalContent {
            width: 90vw;
            height: 90vh;
        }

        .nowrap .d-flex>div:first-child {
            min-width: 296px;
            display: block;
        }

        tbody tr td:last-child {
            position: sticky;
            right: -4px;
            border-left: 1px solid #f8f8f8 !important
        }

        thead th:last-child {
            position: sticky;
            right: -4px;
        }
    </style>

    <article class="">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-12">
                    <div class="d-flex gap-3 itemscenter">
                        <div class="input-group">
                            <span class="input-group-text"> <img src="{{ asset('assets/search.png') }}"> </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Nama Perusahaan..."
                                id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">ID</th>
                        <th class="pt-0">Nama Perusahaan</th>
                        <th class="pt-0">Total Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimuat oleh DataTables -->
                </tbody>
            </table>
        </div>

    </article>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div id="detailModalContent"></div>
            </div>
        </div>
    </div>

    <div class="modal fade custom-modal" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background:unset;border:unset;align-items:center;gap:1rem;position:relative">
                <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                    style="align-self: end;position: absolute;right: -3rem;top: -2rem;"><img
                        src="{{ asset('assets/close.png') }}" width="70%"></button>
                <div id="previewModalContent"></div>
            </div>
        </div>
    </div>


    <div class="modal fade custom-modal" id="previewOnlyForPDFModal" tabindex="-1"
        aria-labelledby="previewOnlyForPDFModalLabel" aria-hidden="true">
        <div class="modal-dialog m-0 modal-lg ">
            <div class="modal-content"
                style="background:unset;border:unset;align-items:center;gap:0;width: 100vw; left: 0; position: fixed;">
                <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                    style="align-self: end;margin-top:.5rem"><img src="{{ asset('assets/close.png') }}"
                        width="70%" /></button>
                <div id="previewOnlyForPDFModalContent"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var detailUrl = "{{ url('/superadmin/karyawan/detail/') }}";
        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searhMediaByCategory(event) {
            const categoryId = event.target.value;;
            DataTables(__getId('search-datatable').value, categoryId);
        }


        function DataTables(searchTerm, categoryId = undefined) {
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: `{{ url('api/superadmin/karyawan/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                    }),
                    error: function({ responseJSON: { message } }, error, thrown) {
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        data: null, // Kolom untuk ID dinamis
                        orderable: false, // Menonaktifkan sorting pada kolom ini
                        className: 'text-center', // Menyelaraskan teks di tengah
                        render: function(data, type, row, meta) {
                            // Menghitung nomor urut dengan format 3 digit
                            var pageInfo = meta.settings.oInstance.api().page.info();
                            var rowNumber = meta.row + 1 + pageInfo.start; // +1 untuk menyesuaikan dengan basis 0
                            var formattedNumber = rowNumber.toString().padStart(3, '0'); // Nomor dengan padding 0

                            // Menghasilkan link ke detail berdasarkan ID
                            return `<a href="${detailUrl}/${data.company_id}" class="link-detail">${formattedNumber}</a>`;
                        }
                    },
                    {
                        data: 'company_name',
                        orderable: false,
                        render: function(data) {
                            return data ? data : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return row.get_employe && row.get_employe.length > 0 ? row.get_employe.length : 0;
                        }
                    }
                ],
                initComplete: function () {
                    if (!initCompleteCalled) {  // Check if initComplete has not been called yet
                        var dataLength = table.rows().data().length;
                        if (dataLength < 1) {
                            $('.TABLE-WITHOUT-SEARCH-BAR').show();
                            $('.SEARCH').hide();
                            $('.TABLE').hide();
                        } else {
                            $('.TABLE-WITHOUT-SEARCH-BAR').hide();
                            $('.SEARCH').show();
                            $('.TABLE').show();
                        }
                        initCompleteCalled = true;  // Set the flag to true after initComplete is executed
                    }
                },
                // initComplete: function() {
                //     // Inisialisasi checkbox behavior
                //     handleCheckboxes(); // Panggil fungsi untuk mengatur status tombol
                // }
            };

            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#data-table')) {
                $('#data-table').DataTable().clear().destroy();
            }

            // Initialize DataTable with the prepared ajaxConfig
            table = $('#data-table').DataTable(ajaxConfig);

            // Attach event listeners to checkbox changes
            $('#data-table tbody').on('change', '.row-checkbox', function() {
                // handleCheckboxes(); // Update button states on checkbox change
            });

            // Initialize FixedHeader
            if ($.fn.dataTable.FixedHeader) {
                new $.fn.dataTable.FixedHeader(table);
            }
        }

        // Call DataTables function to initialize
        DataTables();


    </script>
@endsection
