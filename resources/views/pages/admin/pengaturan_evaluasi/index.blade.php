@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Jadwal Pelatihan')

@section('breadcumbSubtitle', 'Jadwal Pelatihan List')

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
        <div class="card"  style="padding: 1%;border-radius:1rem">
            <div class="card-bod">
                <p>Pengaturan Evaluasi</p>
            </div>
            <form id="evaluationForm">
                <div class="form-group">
                    <div class="row">
                        @if ($get_time_setting)
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="total" id="total" value="{{$get_time_setting ? $get_time_setting->total : 0}}">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="type" id="type">
                                            <option value="Tahun" {{$get_time_setting->type == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                                            <option value="Bulan" {{$get_time_setting->type == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                                            <option value="Hari" {{$get_time_setting->type == 'Hari' ? 'selected' : '' }}>Hari</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="btnSave">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="total" id="total" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="type" id="type">
                                            <option value="Tahun" >Tahun</option>
                                            <option value="Bulan" >Bulan</option>
                                            <option value="Hari" >Hari</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="btnSave">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-8"></div>
                    </div>
                </div>
            </form>
        </div><br>

        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-12">
                    <div class="d-flex gap-3 itemscenter">
                        <a href="{{ url()->previous() }} ">
                            <img src="{{ asset('assets/back.png') }}"></a>
                        </a>
                        <div class="input-group">
                            <span class="input-group-text"> <img src="{{ asset('assets/search.png') }}"> </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Cari ID atau Nama vendor..."
                                id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                </div>
            </div>

            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">ID</th>
                        <th class="pt-0">Nama Vendor</th>
                        <th class="pt-0">Tanggal Pelatihan</th>
                        <th class="pt-0">Tipe Pelatihan</th>
                        <th class="pt-0">Judul Pelatihan</th>
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

@endsection

@section('js')


    <script>
        var detailUrl = "{{ url('/superadmin/setting_evaluasi/detail/') }}";

        $(document).ready(function () {
            $('#btnSave').on('click', function (e) {
                e.preventDefault(); // Mencegah submit form default

                // Ambil elemen form
                var form = $('#evaluationForm')[0];

                // Inisialisasi FormData
                const formData = new FormData(form);

                // Validasi di sisi klien
                var total = $('#total').val();
                var type = $('#type').val();

                // Validasi di sisi klien
                if (!type) {
                    blockUI("Field 'type' wajib diisi.", _.ERROR);
                    return;
                }

                if (!total || total <= 0) {
                    blockUI("Field 'total' wajib diisi dan harus lebih besar dari 0.", _.ERROR);
                    return;
                }

                formData.append('token', getCurrentToken()['token']);

                $.ajax({
                    url: "{{ url('/api/superadmin/setting_evaluasi/store_time_evaluasi') }}", // Pastikan ini adalah route yang sesuai
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        total: total,
                        type: type
                    },
                    success: function (response) {
                        if (response.success) {
                            blockUI(response.message); // Tampilkan pesan sukses
                        } else {
                            blockUI("Gagal menyimpan data", _.ERROR);
                        }
                    },
                    error: function (xhr) {
                        alert("Terjadi kesalahan: " + xhr.responseText);
                    }
                });
            });
        });

        var detailUrlAfterFinish = "{{ url('/superadmin/setting_evaluasi/detail-after-finish/') }}";

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searhMediaByCategory(event) {
            const categoryId = event.target.value;;
            DataTables(__getId('search-datatable').value, categoryId);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const type_pelatihan = document.getElementById('search-tipe').value;

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
                    url: `{{ url('api/superadmin/setting_evaluasi/data-table') }}`,
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
                        data: null, // Kolom untuk ID dinamis
                        orderable: false, // Menonaktifkan sorting pada kolom ini
                        className: 'text-center', // Menyelaraskan teks di tengah
                        render: function(data, type, row, meta) {
                            // Menghitung nomor urut dengan format 3 digit
                            var pageInfo = meta.settings.oInstance.api().page.info();
                            var rowNumber = meta.row + 1 + pageInfo.start; // +1 untuk menyesuaikan dengan basis 0
                            var formattedNumber = rowNumber.toString().padStart(3, '0'); // Nomor dengan padding 0
                            return `<a href="${detailUrl}/${data.id}" class="link-detail">${formattedNumber}</a>`;
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
                            return row.vendor ? row.vendor.company_name : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_evaluasi3 ? row.get_evaluasi3.tanggal_pelaksanaan : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_evaluasi3 ? row.get_evaluasi3.pelatihan.kategori : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_evaluasi3 ? row.get_evaluasi3.pelatihan.judul_pelatihan : '-';
                        }
                    },

                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.status == 'Belum Dievaluasi') {
                                return `<span class="badge badge-xs bg-secondary">${row.status}</span>`;
                            } else if(row.status == 'Sudah Dievaluasi') {
                                return `<span class="badge badge-xs bg-success">${row.status}</span>`; // Menampilkan badge dengan '-' jika tidak ada data
                            }else{
                                return '-'
                            }
                        }
                    },
                ],
                initComplete: function() {
                    // Initialize checkbox behavior when the DataTable is loaded
                    if (!initCompleteCalled) {  // Check if initComplete has not been called yet
                        var dataLength = table.rows().data().length;
                        // if (dataLength < 1) {
                        //     $('.TABLE-WITHOUT-SEARCH-BAR').show();
                        //     $('.SEARCH').hide();
                        //     $('.TABLE').hide();
                        // } else {
                        //     $('.TABLE-WITHOUT-SEARCH-BAR').hide();
                        //     $('.SEARCH').show();
                        //     $('.TABLE').show();
                        // }
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
