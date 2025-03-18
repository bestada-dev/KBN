@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Vendor')

@section('breadcumbSubtitle', 'Vendor List')

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
                {{-- @if (Session::get('data.user.is_admin')) --}}
                    <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">Tambah
                        Data</button>
                {{-- @endif --}}
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-8">
                    <div class="d-flex gap-3 itemscenter">
                        {{-- <a href="{{ url()->previous() }} ">
                            <img src="{{ asset('assets/back.png') }}"></a>
                        </a> --}}
                        <div class="input-group">
                            <span class="input-group-text"> <img src="{{ asset('assets/search.png') }}"> </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Search data..."
                                id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searhMediaByCategory(event)">
                            <option value="">Pilih</option>
                            <option value="1">Terdaftar</option>
                            <option value="2">Belum Terdaftar</option>
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
                <div class="col-md-4 d-flex justify-content-end" style="display: flex; justify-content: flex-end; gap: 5px;">
                    <button id="btn-delete" class="btn btn-grey btn-sm " style="font-weight:600;margin-bottom:5px" disabled>
                        <img src="{{ asset('trash can.png') }}">
                    </button>
                    <button id="btn-edit" class="btn btn-grey btn-sm btn-edit-data"  style="font-weight:600;margin-bottom:5px" disabled>
                        <img src="{{ asset('edit 2.png') }}" style="color: white">
                    </button>
                    <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </button>
                </div>
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">
                            <input type="checkbox" name="select_btn" id="select_btn" onchange="toggleCheckboxes(this)">
                        </th>
                        <th class="pt-0">ID</th>
                        <th class="pt-0">Email</th>
                        <th class="pt-0">Nama Perusahaan</th>
                        <th class="pt-0">Alamat</th>
                        <th class="pt-0">Logo Perusahaan</th>
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
                    <strong class="" id="detailMediaModalLabel" style="font-size: 1.2rem;color: #2778c4">Tambah Vendor</strong>
                    <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close-request-media-modal">
                        <img src="{{ asset('assets/close.png') }}" width="70%" />
                    </button>
                </div>
                <form id="request-vendor-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <div class="modal-body">
                        <label for="email" class="form-label required-label">Email <b>(*)</b></label>
                        <input type="email" class="form-control form-control-sm" name="email" id="email"
                            placeholder="Masukan Email">
                        <div id="reasonError" class="error-message">

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
        $(document).on('click', '.btn-add-data', function() {
            $('#user_id').val('')
            $('#email').val('')
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });

        $(document).on('click', '.btn-edit-data', function() {
            var getEmail = $('.row-checkbox:checked').attr('data-email');
            var getId = $('.row-checkbox:checked').attr('data-id');
            $('#user_id').val(getId)
            $('#email').val(getEmail)
            // var data = JSON.parse(selectedIds)
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();

        });

        $(document).on('click', '#btn-delete', function () {
            // Ambil semua checkbox yang dicentang
            const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

            // Jika tidak ada checkbox yang dicentang, beri peringatan
            if (checkedCheckboxes.length === 0) {
                alert('Silakan pilih data yang ingin dihapus.');
                return;
            }

            // Kumpulkan ID dari checkbox yang dicentang
            const idsToDelete = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

            // Tampilkan konfirmasi sebelum menghapus
            __swalConfirmation(async (data) => {
                try {
                    // Kirim permintaan DELETE untuk setiap ID
                    const res = await fetch(`{{ url('api/superadmin/vendor/delete') }}`, customPost({
                        ids: idsToDelete // Kirim array ID ke server
                    }));
                    const result = await res.json();

                    const { status, message } = result;

                    if (status) {
                        refreshDT(); // Panggil fungsi untuk menyegarkan DataTable
                        blockUI(message, _.DELETE);

                        setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/vendor') }}`
                        }, 1000); // 3000 milidetik = 3 detik
                    } else {
                        blockUI('Ops.. something went wrong!', _.ERROR);
                        console.error(message);
                    }
                } catch (error) {
                    console.error(error);
                    blockUI('Ops.. something went wrong!', _.ERROR);
                }
            });
        });

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Saat form disubmit
            $('#request-vendor-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('token', getCurrentToken()['token'])
                formData.append('userLoggedIn', @json(Auth::id()))
                var email = $('#email').val();
                var url = ''; // URL untuk AJAX request
                __isBtnSaveOnProcessing(__querySelector('#request-vendor-form #btn-save'), true);

                if ($('input[name="user_id"]').val() === '') {
                    url = `{{ url('api/superadmin/vendor/create') }}`;
                } else {
                    url = `{{ url('api/superadmin/vendor/update') }}/${$('input[name="user_id"]').val()}`;
                    formData.append('_method', 'post'); // Tambahkan ini untuk update
                }

                console.log(url)

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            __isBtnSaveOnProcessing(__querySelector(
                                '#request-vendor-form #btn-save'), false);
                            $('#formRequestModal').modal('hide');

                            // Menampilkan pesan keberhasilan
                            blockUI('Berhasil');

                            // Menunggu 3 detik sebelum reload halaman
                            setTimeout(function() {
                                // Memastikan modal sudah tertutup sebelum reload
                                if (!$('#formRequestModal').hasClass(
                                    'show')) { // Cek jika modal sudah tidak ditampilkan
                                    // Menutup modal dengan dispatch event

                                    // Reload halaman
                                    window.location.href = `{{ url('/superadmin/vendor') }}`
                                }
                            }, 2000); // 3000 milidetik = 3 detik

                        } else {
                            __isBtnSaveOnProcessing(__querySelector(
                                '#request-vendor-form #btn-save'), false);

                            blockUI('Terjadi kesalahan. Silahkan coba lagi.')
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Unprocessable Entity (Validasi Gagal)
                            __isBtnSaveOnProcessing(__querySelector(
                                '#request-vendor-form #btn-save'), false);

                            var errors = xhr.responseJSON.errors;
                            if (errors.email) {
                                $('#reasonError').html(
                                    '<div style="color: red;font-size: 9px;">' + errors
                                    .email[0] + '</div>');
                            }
                        } else {
                            blockUI('Terjadi kesalahan. Silahkan coba lagi.')
                        }
                    }
                });
            });

            // Fungsi untuk membuka modal pada mode update dan mengisi data
            function openUpdateModal(id, email) {
                $('input[name="user_id"]').val(id);
                $('#email').val(email);
                $('#formRequestModal').modal('show');
            }
        });

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searhMediaByCategory(event) {
            const categoryId = event.target.value;;
            DataTables(__getId('search-datatable').value, categoryId);
        }

        function toggleCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
            handleCheckboxes(); // Update tombol berdasarkan checkbox yang dipilih
        }


        function handleCheckboxes() {
            // Get all checkboxes
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const checkedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;

            // Enable or disable buttons based on checkedCount
            const editButton = document.getElementById('btn-edit');
            const deleteButton = document.getElementById('btn-delete');

            if (checkedCount === 1) {
                // Enable both buttons if only one checkbox is checked
                editButton.disabled = false;
                deleteButton.disabled = false;
            } else if (checkedCount > 1) {
                // Enable delete only if more than one checkbox is checked
                editButton.disabled = true;
                deleteButton.disabled = false;
            } else {
                // Disable both buttons if no checkbox is checked
                editButton.disabled = true;
                deleteButton.disabled = true;
            }
        }


        function DataTables(searchTerm, categoryId = undefined) {
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: `{{ url('api/superadmin/vendor/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                        'status_id': categoryId
                    }),
                    error: function({ responseJSON: { message } }, error, thrown) {
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        // Kolom Checkbox
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var jsonData = JSON.stringify(row).replace(/"/g, '&quot;'); // Escape double quotes for HTML attribute
                            return `<input type="checkbox" class="row-checkbox" id="data-checked" data-id='${row.id}' data-email='${row.email}' value="${row.id}">`;
                        }
                    },
                    {
                        data: null, // Kolom untuk ID dinamis
                        orderable: false, // Menonaktifkan sorting pada kolom ini
                        className: 'text-center', // Menyelaraskan teks di tengah
                        render: function(data, type, row, meta) {
                            // Menghitung nomor urut dengan format 3 digit
                            var pageInfo = meta.settings.oInstance.api().page.info();
                            var rowNumber = meta.row + 1 + pageInfo.start; // +1 untuk menyesuaikan dengan basis 0
                            return rowNumber.toString().padStart(3, '0'); // Menghasilkan nomor dengan padding 0
                        }
                    },
                    {
                        data: 'email',
                        orderable: false,
                    },
                    {
                        data: 'company_name',
                        orderable: false,
                        render: function(data) {
                            return data ? data : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: 'address',
                        orderable: false,
                        render: function(data) {
                            return data ? data : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: 'company_logo',
                        orderable: false,
                        render: function(data) {
                            if (data) {
                                return `<img src="{{ asset('uploads/logos') }}/${data}" alt="Company Logo" style="width: 70px; height: 70px;">`;
                            } else {
                                return ' - '; // If no logo, display a dash
                            }
                        }
                    },
                    {
                        data: 'user_status_id',
                        orderable: false,
                        render: function(data) {
                            // Cek status dan kembalikan badge yang sesuai
                            if (data == 1) {
                                return '<span class="badge" style="background-color:#D5FFCC; color:#1EB200">Terdaftar</span>'; // Untuk status 1
                            } else {
                                return '<span class="badge" style="background-color:#E6E6E5; color:#B4B3B1">Belum Terdaftar</span>'; // Untuk status lainnya
                            }
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
                            handleCheckboxes();
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
                handleCheckboxes(); // Update button states on checkbox change
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
