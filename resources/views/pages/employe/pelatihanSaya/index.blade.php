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
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Judul atau ID Pelatihan" id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searchByDropdown()">
                            <option value="">Pilih Tipe</option>
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                            <option value="Pengembangan">Pengembangan Diri</option>
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
                        <th>Kategori</th>
                        <th>Judul Pelatihan</th>
                        <th>Tipe</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Hari</th>
                        <th>Jam Pelaksanaan</th>
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
        var detailUrl = "{{ url('/employe/training/detail/') }}";

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const kategori_pelatihan = document.getElementById('search-statuses').value;

            // Panggil DataTables dengan parameter tambahan untuk kategori_pelatihan dan tipe tes
            DataTables(searchTerm, kategori_pelatihan);
        }


        function DataTables(searchTerm = '', kategori_pelatihan = undefined) {
            // Set DataTable's error mode to 'none' to prevent alerts
            console.log(kategori_pelatihan);
            $.fn.dataTable.ext.errMode = 'none';

            const ajaxConfig = {
                ...propertyDB, // Assuming `propertyDB` is a predefined object
                ajax: {
                    url: `{{ url('api/employe/training/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: {
                        ...getCurrentToken(), // Assuming getCurrentToken() adds required authentication tokens
                        'search[value]': searchTerm,
                        'kategori_pelatihan': kategori_pelatihan
                    },
                    error: function(xhr, error, thrown) {
                        const message = xhr?.responseJSON?.message || 'An error occurred';
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    // {
                    //     orderable: false,
                    //     className: 'text-center',
                    //     render: function(data, type, row) {
                    //         return `<input type="checkbox" class="row-checkbox" data-id="${row.id}" value="${row.id}">`;
                    //     }
                    // },
                    {
                        data: 'code',
                        render: function(data, type, row, meta) {
                                return `<a href="${detailUrl}/${row.id}" >${row.get_pelatihan?.id_pelatihan}</a>`;

                        }
                    },
                    {
                        data: 'kategori',
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_pelatihan ? row.get_pelatihan.kategori : '-';
                        }
                    },
                    {
                        data: 'judul_pelatihan_id',
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
                            const formatTanggal = (tanggal) => {
                                if (!tanggal) return ''; // Jika tanggal kosong, kembalikan string kosong
                                const dateObj = new Date(tanggal);
                                if (isNaN(dateObj)) return tanggal; // Jika bukan tanggal valid, kembalikan input awal
                                const day = String(dateObj.getDate()).padStart(2, '0');
                                const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
                                const year = dateObj.getFullYear();
                                return `${day}/${month}/${year}`;
                            };

                            const tanggalMulai = formatTanggal(row.get_pelatihan?.tanggal_mulai);
                            const tanggalSelesai = formatTanggal(row.get_pelatihan?.tanggal_akhir);

                            // Format outputnya sesuai kebutuhan
                            return `${tanggalMulai} - ${tanggalSelesai}`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.get_pelatihan && row.get_pelatihan.hari) {
                                return `<span class="badge badge-xs bg-primary">${row.get_pelatihan.hari}</span>`;
                            } else {
                                return '<span class="badge badge-xs bg-secondary">-</span>'; // Menampilkan badge dengan '-' jika tidak ada data
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            const formatJamMenit = (waktu) => {
                                if (!waktu) return ''; // Jika waktu kosong, kembalikan string kosong
                                const [jam, menit] = waktu.split(':'); // Pisahkan jam dan menit jika formatnya HH:mm:ss
                                if (!jam || !menit) return waktu; // Jika format tidak valid, kembalikan input aslinya
                                return `${jam.padStart(2, '0')}:${menit.padStart(2, '0')}`; // Pastikan format HH:mm
                            };

                            const jamMulai = formatJamMenit(row.get_pelatihan?.jam_mulai);
                            const jamSelesai = formatJamMenit(row.get_pelatihan?.jam_akhir);

                            // Format output sesuai kebutuhan
                            return `${jamMulai} - ${jamSelesai}`;
                        }
                    }
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
