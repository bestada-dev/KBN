@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Evaluasi Level 1')

@section('breadcumbSubtitle', 'Evaluasi Level 1 List')

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
                <div class="col-md-6">
                    <div class="d-flex gap-3 itemscenter">
                        <a href="{{ url()->previous() }}">
                            <img src="{{ asset('assets/back.png') }}">
                        </a>
                        <div class="input-group">
                            <span class="input-group-text">
                                <img src="{{ asset('assets/search.png') }}">
                            </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Judul atau ID Pelatihan" id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                
                <div class="col-md-6">
                        <form>
                    <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                                    <select class="form-select" name="type" id="pelatihan_id" onchange="searchByDropdown()">
                                        <option value="">Pilih Pelatihan</option>
                                        <option value="Pelatihan">Pelatihan Public</option>
                                        <option value="Pengembangan">Pengembangan Diri</option>
                                    </select>
                                    <span class="text-danger error-message" id="pelatihan_id-error"></span>
                                </div>
    </div>
    <div class="col-md-6">

                                <div class="form-group">
                                    <select class="form-select" name="status_evaluasi" id="status_evaluasi" onchange="searchByDropdown()">
                                        <option value="">Pilih Status</option>
                                        <option value="Belum Dievaluasi">Belum Dievaluasi</option>
                                        <option value="Sudah Dievaluasi">Sudah Dievaluasi</option>
                                    </select>
                                    <span class="text-danger error-message" id="status_evaluasi-error"></span>
                                </div>
                        </div>
                        </div>
                            </form>
                    </div>
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
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Tipe Pelatihan</th>
                        <th>Judul Pelatihan</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Tanggal Evaluasi</th>
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
        var detailUrl = "{{ url('/vendor/evaluasi_level_1/detail/') }}";
        var detailUrlAfterFinish = "{{ url('/vendor/evaluasi_level_1/detail-after-finish/') }}";

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const pelatihan_id = document.getElementById('pelatihan_id').value;
            const status_evaluasi = document.getElementById('status_evaluasi').value;
            

            // Panggil DataTables dengan parameter tambahan untuk kategori_pelatihan dan tipe tes
            DataTables(searchTerm, pelatihan_id, status_evaluasi);
        }


        function DataTables(searchTerm = '', pelatihan_id = undefined, status_evaluasi = undefined) {
            // Set DataTable's error mode to 'none' to prevent alerts
            $.fn.dataTable.ext.errMode = 'none';

            const ajaxConfig = {
                ...propertyDB, // Assuming `propertyDB` is a predefined object
                ajax: {
                    url: `{{ url('api/vendor/evaluasi_level_1/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: {
                        ...getCurrentToken(), // Assuming getCurrentToken() adds required authentication tokens
                        'search[value]': searchTerm,
                        'pelatihan_id': pelatihan_id,
                        'status_evaluasi': status_evaluasi
                    },
                    error: function(xhr, error, thrown) {
                        const message = xhr?.responseJSON?.message || 'An error occurred';
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        data: null, // Kolom untuk ID dinamis
                        orderable: false, // Menonaktifkan sorting pada kolom ini
                        className: 'text-center', // Menyelaraskan teks di tengah
                        render: function(data, type, row, meta) {
                            // debugger;
                            // Menghitung nomor urut dengan format 3 digit
                            var pageInfo = meta.settings.oInstance.api().page.info();
                            var rowNumber = meta.row + 1 + pageInfo.start; // +1 untuk menyesuaikan dengan basis 0
                            var formattedNumber = rowNumber.toString().padStart(3, '0'); // Nomor dengan padding 0
                            if(!row.get_evaluasi_lv1){
                                // OLD : return `<a href="${detailUrl}/${data.id}" class="link-detail">${formattedNumber}</a>`;
                                return `<span style="color:#666">${formattedNumber}</span>`;
                            }else{
                                return `<a href="${detailUrlAfterFinish}/${data.id}" class="link-detail">${formattedNumber}</a>`;
                            }
                        }
                    },
                    // {
                    //     data: 'code',
                    //     render: function(data, type, row, meta) {
                    //         return `<a href="${detailUrl}/${row.id}" >${row.get_evaluasi3 ? row.get_evaluasi3.code : '-'}</a>`;
                    //     }
                    // },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.user ? row.user?.employe_name : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan ? row.get_pelatihan?.kategori : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            // const ok = data;
                            // debugger;
                            return row.get_pelatihan ? row.get_pelatihan?.judul_pelatihan : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan ? row.get_pelatihan?.tanggal_mulai : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_evaluasi_lv1 ? row.get_evaluasi_lv1?.tanggal_evaluasi : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.get_evaluasi_lv1 && row.get_evaluasi_lv1?.status == 'Belum Dievaluasi') {
                                return `<span class="badge badge-xs bg-secondary">${row.get_evaluasi_lv1?.status || 'Belum Dievaluasi'}</span>`;
                            } else if(row.get_evaluasi_lv1 && row.get_evaluasi_lv1?.status == 'Sudah Dievaluasi') {
                                return `<span class="badge badge-xs bg-success">${row.get_evaluasi_lv1?.status}</span>`; // Menampilkan badge dengan '-' jika tidak ada data
                            }else{
                                return `<span class="badge badge-xs bg-secondary">Belum Dievaluasi</span>`;
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
