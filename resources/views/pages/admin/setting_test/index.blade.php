@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Test')

@section('breadcumbSubtitle', 'Test List')

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
                            <input type="text" class="form-control form-control-sm"
                                placeholder="Cari Judul atau ID Pelatihan" id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <form>
                        <input type="date" class="form-control form-control-sm" id="search-statuses"
                            onchange="searchTerm()">
                    </form>
                </div>
            </div>
        </div>

        <div class="TABLE">
            <div class="row d-flex between mb-2">
                <div class="col-md-8"></div>
                <div class="col-md-4 d-flex justify-content-end"
                    style="display: flex; justify-content: flex-end; gap: 5px;"></div>
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Vendor</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Tipe Pelatihan</th>
                        <th>Judul</th>
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

    <!-- Modal -->
    <div class="modal fade custom-modal" id="formRequestModal" tabindex="-1" aria-labelledby="formRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header between">
                    <strong id="formRequestModalLabel" style="font-size: 1.2rem; color: #2778c4">Akses Training</strong>
                    <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close-request-media-modal">
                        <img src="{{ asset('assets/close.png') }}" width="70%" />
                    </button>
                </div>
                <form id="request-admin-form">
                    <div class="modal-body" id="modalContent">
                        <!-- Konten data dari API akan dimasukkan di sini -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-main btn-sm" id="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        /* Gaya toggle switch */
        .form-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .form-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }
    </style>
@endsection

@section('js')
    <script>
        var detailUrl = "{{ url('/superadmin/setting_test/detail/') }}";

        $('#request-admin-form').on('submit', function (event) {
            event.preventDefault();

            const testData = [];

            $('#modalContent input[type="checkbox"][data-test-id]').each(function () {
                const testId = $(this).data('test-id');
                const isLocked = $(this).is(':checked') ? 2 : 1;

                testData.push({
                    id: testId,
                    is_locked: isLocked
                });
            });

            const formData = new FormData(this);
            formData.append('token', getCurrentToken()['token']);

            // Append each test entry as a separate `tests[]` entry in FormData
            testData.forEach((test, index) => {
                formData.append(`tests[${index}][id]`, test.id);
                formData.append(`tests[${index}][is_locked]`, test.is_locked);
            });

            $.ajax({
                url: "{{ url('/api/superadmin/setting_test/update-status-test') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log('Data updated successfully:', response);
                    blockUI('Data Berhasil Disimpan');
                    setTimeout(() => {
                        window.location.href = '{{ url('/superadmin/setting_test') }}';
                    }, 1500);
                },
                error: function (xhr, status, error) {
                    console.error('Error updating data:', error);
                    alert('Error updating test status. Please try again.');
                }
            });
        });



        function fetchData(id) {
            // Tampilkan spinner/loading sebelum data muncul
            $('#modalContent').html('<p>Loading...</p>');

            // Panggil API menggunakan ID yang di-pass sebagai parameter
            fetch(`{{ url('/superadmin/setting_test/detail_pelatihan') }}/${id}`) // Sesuaikan endpoint API
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    // Format data yang diterima dan masukkan ke dalam modal
                    let contentHtml = `
                <p><strong>ID:</strong> ${data.id_pelatihan || '-'}</p>
                <p><strong>Nama Vendor:</strong> ${data.company_id || '-'}</p>
                <p><strong>Tanggal Pelatihan:</strong> ${data.tanggal_mulai || '-'}</p>
                <p><strong>Tipe Pelatihan:</strong> ${data.type || '-'}</p>
                <p><strong>Judul Pelatihan:</strong> ${data.judul_pelatihan || '-'}</p>
            `;

                    // Tambahkan informasi PreTest dan PostTest dengan switch
                    if (data.get_test_by_pelatihan_and_pengembangan && data.get_test_by_pelatihan_and_pengembangan
                        .length > 0) {
                        data.get_test_by_pelatihan_and_pengembangan.forEach(test => {
                            const isLocked = test.is_locked === 1;
                            const switchStatus = isLocked ? '' : 'checked';
                            const statusText = isLocked ? 'Terkunci' : 'Terbuka';

                            contentHtml += `
                        <div style="display: flex; align-items: center; margin-top: 10px;">
                            <label class="form-switch">
                                <input type="checkbox" data-test-id="${test.id}" ${switchStatus} onchange="toggleLockStatus(this)">
                                <span class="slider"></span>
                            </label>
                            <p style="margin: 0; margin-left: 10px;">
                                <strong>${test.type_test}:</strong> <span id="statusText-${test.id}">${statusText}</span>
                            </p>
                        </div>
                    `;
                        });
                    } else {
                        contentHtml += `<p><strong>Test:</strong> Tidak ada data tes yang tersedia</p>`;
                    }

                    // Masukkan konten yang diformat ke dalam modal
                    $('#modalContent').html(contentHtml);
                })
                .catch(error => {
                    $('#modalContent').html('<p>Error loading data.</p>');
                    console.error('Error fetching data:', error);
                });

            // Tampilkan modal menggunakan Bootstrap 5
            var formRequestModal = new bootstrap.Modal(document.getElementById('formRequestModal'), {
                keyboard: false
            });
            formRequestModal.show();
        }



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
                    url: `{{ url('api/superadmin/setting_test/data-table') }}`,
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
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            // Format nomor urut dengan 4 digit, misalnya: 0001, 0002, 0003, dll.
                            const nomorUrut = (meta.row + 1).toString().padStart(4,
                            '0'); // Tambahkan 1 karena `meta.row` dimulai dari 0

                            // Tambahkan event onclick untuk membuka modal dan ambil data menggunakan fetchData
                            return `<a href="javascript:void(0)" onclick="fetchData(${row.id})">${nomorUrut}</a>`;
                        }
                    },
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
                            const tanggalMulai = row.tanggal_mulai ? moment(row.tanggal_mulai).format('D/M/YYYY') : '';
                            return `${tanggalMulai}`;
                        }
                    },

                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.type ? row.type : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.judul_pelatihan ? row.judul_pelatihan : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            let output = '';

                            // Loop untuk setiap item di get_test_by_pelatihan_and_pengembangan
                            row.get_test_by_pelatihan_and_pengembangan.forEach(test => {
                                // Cek apakah test is_locked, 1 (terkunci) atau 2 (terbuka)
                                const icon = test.is_locked === 1 ?
                                    '<i class="bi bi-lock" style="color: gray;"></i>' // Ikon kunci terkunci
                                    :
                                    '<i class="bi bi-unlock" style="color: green;"></i>'; // Ikon kunci terbuka

                                // Tambahkan ke output dengan ikon dan nama tipe test (Pre Test / Post Test)
                                output +=
                                    `${icon} <span style="color: ${test.is_locked === 1 ? 'gray' : 'green'};">${test.type_test}</span><br>`;
                            });

                            return output || '-'; // Jika tidak ada data, tampilkan tanda "-"
                        }
                    }
                ],
                initComplete: function() {
                    // Initialize checkbox behavior when the DataTable is loaded
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
                        }
                        initCompleteCalled = true; // Set the flag to true after initComplete is executed
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
            $('#data-table tbody').on('change', '.row-checkbox', function() {});

            // Initialize FixedHeader if available
            if ($.fn.dataTable.FixedHeader) {
                new $.fn.dataTable.FixedHeader(table);
            }
        }

        // Call DataTables function to initialize
        DataTables();
    </script>
@endsection
