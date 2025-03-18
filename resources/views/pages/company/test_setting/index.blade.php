@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pengaturan Pelatihan')

@section('breadcumbSubtitle', 'Pengaturan Pelatihan List')

@section('content')
    <article class="">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
                <!-- <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">TambahData</button> -->
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-9">
                    <div class="d-flex gap-3 itemscenter">
                        <a href="{{ url()->previous() }} ">
                            <img src="{{ asset('assets/back.png') }}"></a>
                        </a>
                        <div class="input-group">
                            <span class="input-group-text"> <img src="{{ asset('assets/search.png') }}"> </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Search data..."
                                id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses">
                            <option value="">Semua Pelatihan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Pengembangan">Pengembangan</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                    {{-- <a href="" class="btn btn-orange btn-sm" style="font-weight:600;margin-bottom:5px"> <img src="{{ asset('assets/add.png') }}"> Add Media</a> --}}
                </div>
                <div class="col-md-4 d-flex justify-content-end"
                    style="display: flex; justify-content: flex-end; gap: 5px;">
                    {{-- <button id="btn-delete" class="btn btn-grey btn-sm " style="font-weight:600;margin-bottom:5px" disabled>
                        <img src="{{ asset('trash can.png') }}">
                    </button>
                    <button id="btn-edit" class="btn btn-grey btn-sm btn-edit-data"
                        style="font-weight:600;margin-bottom:5px" disabled>
                        <img src="{{ asset('edit 2.png') }}" style="color: white">
                    </button>
                    <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </button> --}}
                </div>
            </div>

            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">ID</th>
                        <th class="pt-0">Nama Vendor</th>
                        <th class="pt-0">Tanggal Pelatihan</th>
                        <th class="pt-0">Tipe Pelatihan</th>
                        <th class="pt-0">Judul</th>
                        <th class="pt-0">Total Karyawan</th>
                        <th class="pt-0">Status</th>
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

    <!-- Modal -->
    <div class="modal fade custom-modal" id="formRequestModal" tabindex="-1" aria-labelledby="formRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header between">
                    <strong class="" id="detailMediaModalLabel" style="font-size: 1.2rem;color: #2778c4">Akses
                        Training</strong>
                    <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close-request-media-modal">
                        <img src="{{ asset('assets/close.png') }}" width="70%" />
                    </button>
                </div>
                <form id="request-admin-form">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="pelatihan_id" class="form-label">Tipe Pelatihan</label>
                            <select class="form-select" name="type" id="pelatihan_id">
                                <option value="">Pilih Pelatihan</option>
                                <option value="Pelatihan">Pelatihan Public</option>
                                <option value="Pengembangan">Pengembangan Diri</option>
                            </select>
                            <span class="text-danger error-message" id="pelatihan_id-error"></span>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label for="judul_pelatihan_id" class="form-label">Judul Pelatihan</label>
                            <select class="form-select" name="judul_pelatihan_id" id="judul_pelatihan_id">
                                <option value="">Pilih Pelatihan</option>
                            </select>
                            <span class="text-danger error-message" id="judul_pelatihan_id-error"></span>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label for="company_id" class="form-label">Nama Perusahaan</label>
                            <select class="form-select" name="company_id" id="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($get_perusahaan as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-message" id="company_id-error"></span>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label for="employe_total" class="form-label">Total Karyawan</label>
                            <input type="text" class="form-control" name="employe_total" id="employe_total">
                            <span class="text-danger error-message" id="employe_total-error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-main btn-sm" id="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var detailUrl = "{{ url('/company/test_setting/detail/') }}";

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
                    url: `{{ url('api/company/test_setting/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                        'pelatihan': categoryId
                    }),
                    error: function({
                        responseJSON: {
                            message
                        }
                    }, error, thrown) {
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        data: 'code',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            if (data) {
                                // Misalkan URL-nya adalah '/pelatihan/' diikuti oleh id_pelatihan
                                return `<a href="${detailUrl}/${row.id}" >${data}</a>`;
                            } else {
                                return '-'; // Return a dash when `data` is falsy
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                             return row.get_pelatihan_dan_pengembangan  ? row.get_pelatihan_dan_pengembangan.get_vendor.company_name : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan_dan_pengembangan.tanggal_mulai ? row.get_pelatihan_dan_pengembangan.tanggal_mulai : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan_dan_pengembangan ? row.get_pelatihan_dan_pengembangan.kategori : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                             return row.get_pelatihan_dan_pengembangan  ? row.get_pelatihan_dan_pengembangan.judul_pelatihan : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.employe_total ? row.employe_total : 0; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.get_pelatihan_dan_pengembangan.status_pelatihan === 'Belum Dimulai') {
                                return '<span class="badge bg-secondary">' + (row.get_pelatihan_dan_pengembangan.status_pelatihan || '-') + '</span>';
                            } else if (row.get_pelatihan_dan_pengembangan.status_pelatihan === 'Sedang Berlangsung') {
                                return '<span class="badge bg-success">' + (row.get_pelatihan_dan_pengembangan.status_pelatihan || '-') + '</span>';
                            } else if (row.get_pelatihan_dan_pengembangan.status_pelatihan === 'Selesai') {
                                return '<span class="badge bg-success">' + (row.get_pelatihan_dan_pengembangan.status_pelatihan || '-') + '</span>';
                            } else {
                                return '<span class="badge bg-secondary">Belum Dimulai</span>';
                            }
                        }
                    }
                ],
                initComplete: function() {
                    // Inisialisasi checkbox behavior
                    // handleCheckboxes(); // Panggil fungsi untuk mengatur status tombol
                    if (!initCompleteCalled) { // Check if initComplete has not been called yet
                        var dataLength = table.rows().data().length;
                        if (dataLength < 1) {
                            $('.TABLE-WITHOUT-SEARCH-BAR').show();
                            $('.SEARCH').hide();
                            $('.TABLE').hide();
                        } else {
                            $('.TABLE-WITHOUT-SEARCH-BAR').hide();
                            $('.SEARCH').show();
                            $('.TABLE').show();
                            // handleCheckboxes();
                        }
                        initCompleteCalled = true; // Set the flag to true after initComplete is executed
                    }
                }
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
