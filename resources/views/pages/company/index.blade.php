@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pelatihan')

@section('breadcumbSubtitle', 'Pelatihan List')

@section('content')


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
                <div class="col-md-8">
                    <div class="d-flex gap-3 itemscenter">
                        <a href="{{ url()->previous() }}">
                            <img src="{{ asset('assets/back.png') }}">
                        </a>
                        <div class="input-group">
                            <span class="input-group-text">
                                <img src="{{ asset('assets/search.png') }}">
                            </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Cari ID atau Nama Karyawan" id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searchTerm()">
                            <option value="">Pilih Tipe</option>
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-2">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searchTerm()">
                            <option value="">Pilih Status</option>
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="TABLE">
            <div class="row d-flex between mb-2">
                <div class="col-md-8"></div>
                <div class="col-md-4 d-flex justify-content-end" style="display: flex; justify-content: flex-end; gap: 5px;"></div>
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Karyawan</th>
                        <th>Tipe Pelatihan</th>
                        <th>Judul</th>
                        <th>TIpe</th>
                        <th>Nilai</th>
                        <th>Status</th>
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
        var detailUrl = "{{ url('/company/test/detail/') }}";

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searhMediaByCategory(event) {
            const categoryId = event.target.value;;
            DataTables(__getId('search-datatable').value, categoryId);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const type_pelatihan = document.getElementById('search-statuses').value;

            // Panggil DataTables dengan parameter tambahan untuk type_pelatihan dan tipe tes
            DataTables(searchTerm, type_pelatihan);
        }


        function DataTables(searchTerm = '', categoryId = 'undefined') {
            // Set DataTable's error mode to 'none' to prevent alerts
            console.log(categoryId);
            $.fn.dataTable.ext.errMode = 'none';

            const ajaxConfig = {
                ...propertyDB, // Assuming `propertyDB` is a predefined object
                ajax: {
                    url: `{{ url('api/company/test/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: {
                        ...getCurrentToken(), // Assuming getCurrentToken() adds required authentication tokens
                        'search[value]': searchTerm,
                        'type_pelatihan': categoryId
                    },
                    error: function(xhr, error, thrown) {
                        const message = xhr?.responseJSON?.message || 'An error occurred';
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            // if (data) {
                            //     // Misalkan URL-nya adalah '/pelatihan/' diikuti oleh id_pelatihan
                            //     return `<a href="${detailUrl}/${row.id}" >${data}</a>`;
                            // } else {
                                return row.get_pelatihan ? row.get_pelatihan.id_pelatihan : '-'; // Return a dash when `data` is falsy
                            // }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.user ? row.user.employe_name : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan ? row.get_pelatihan.kategori : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan ? row.get_pelatihan.judul_pelatihan : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan ? row.get_pelatihan.type : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            const nilai_pretest = row.nilai_pre_test || 0; // Ambil tanggal mulai atau ''
                            const nilai_posttest = row.nilai_post_test || 0; // Ambil tanggal selesai atau '-'

                            // Format outputnya sesuai kebutuhan
                            return `${nilai_pretest} / ${nilai_posttest}`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.status == "Belum Pre Test") {
                                return `<span class="badge badge-xs bg-secondary">${row.status}</span>`;
                            } else if (row.status == "Belum Post Test") {
                                return `<span class="badge badge-xs bg-secondary">${row.status}</span>`;
                            } else {
                                return '<span class="badge badge-xs bg-success">Sudah Selesai</span>'; // Menampilkan badge dengan '-' jika tidak ada data
                            }
                        }
                    },
                ],
                initComplete: function() {
                    // Initialize checkbox behavior when the DataTable is loaded
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
                }
            };

            // If DataTable already exists, clear and destroy it
            if ($.fn.DataTable.isDataTable('#data-table')) {
                $('#data-table').DataTable().clear().destroy();
            }

            // Initialize the DataTable with the updated config
            const table = $('#data-table').DataTable(ajaxConfig);

            // Handle checkbox changes dynamically in the DataTable
            $('#data-table tbody').on('change', '.row-checkbox', function() {
            });

            // Initialize FixedHeader if available
            if ($.fn.dataTable.FixedHeader) {
                new $.fn.dataTable.FixedHeader(table);
            }
        }

        // Call DataTables function to initialize
        DataTables();


    </script>
@endsection
