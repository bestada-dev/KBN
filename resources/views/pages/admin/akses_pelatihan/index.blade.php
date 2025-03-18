@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Akses Pelatihan')

@section('breadcumbSubtitle', 'Akses Pelatihan List')

@section('content')
    <article class="">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
                <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">TambahData</button>
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
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searhMediaByCategory(event)">
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
                    <button id="btn-delete" class="btn btn-grey btn-sm " style="font-weight:600;margin-bottom:5px" disabled>
                        <img src="{{ asset('trash can.png') }}">
                    </button>
                    <button id="btn-edit" class="btn btn-grey btn-sm btn-edit-data"
                        style="font-weight:600;margin-bottom:5px" disabled>
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
                        <th class="pt-0">Tipe Pelatihan</th>
                        <th class="pt-0">Judul</th>
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
        $(document).ready(function() {
            function initializeSelect2() {
                $('#pelatihan_id, #judul_pelatihan_id, #company_id').select2({
                    dropdownParent: $('#formRequestModal') // Set parent to modal
                });
            }

            // Initialize Select2 on page load
            initializeSelect2();

            // Reinitialize Select2 when modal is shown
            $('#formRequestModal').on('shown.bs.modal', function() {
                initializeSelect2();
            });

            $('#pelatihan_id').on('change', function() {
                var pelatihanType = $(this).val();
                var judulPelatihanSelect = $('#judul_pelatihan_id');

                judulPelatihanSelect.empty().append('<option value="">Pilih Judul Pelatihan</option>');

                if (pelatihanType) {
                    $.ajax({
                        url: `{{ url('/superadmin/akses_pelatihan/get_judul_pelatihan') }}`,
                        type: 'GET',
                        data: {
                            pelatihan: pelatihanType
                        },
                        success: function(response) {
                            $.each(response, function(index, judulPelatihan) {
                                judulPelatihanSelect.append(
                                    `<option value="${judulPelatihan.id}">${judulPelatihan.judul_pelatihan}</option>`
                                );
                            });
                            judulPelatihanSelect.select2({
                                dropdownParent: $('#formRequestModal')
                            });
                        },
                        error: function(xhr) {
                            console.log('Error fetching judul pelatihan:', xhr);
                        }
                    });
                }
            });
        });
    </script>


    <script>
        $(document).on('click', '.btn-add-data', function() {
            $('#pelatihan_id').val('')
            $('#judul_pelatihan_id').val('')
            $('#company_id').val('')
            $('#employe_total').val('')
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });

        $(document).on('click', '.btn-edit-data', function() {
            var selectedData = [];
            $('.row-checkbox:checked').each(function() {
                var data = {
                    id: $(this).attr('data-id'),
                    type: $(this).attr('data-type'),
                    judul: $(this).attr('data-judul'),
                    company: $(this).attr('data-company'),
                    employeTotal: $(this).attr('data-employetotal')
                };
                selectedData.push(data);
            });

            if (selectedData.length > 0) {
                var firstSelected = selectedData[0];

                // Set nilai ID, company, dan employeTotal
                $('#user_id').val(firstSelected.id);
                $('#company_id').val(firstSelected.company);
                $('#employe_total').val(firstSelected.employeTotal);

                // Set nilai Tipe Pelatihan
                $('#pelatihan_id').val(firstSelected.type).trigger('change');

                // Tunggu hingga AJAX selesai mengisi dropdown `judul_pelatihan_id`
                $('#pelatihan_id').on('change', function() {
                    setTimeout(function() {
                        $('#judul_pelatihan_id').val(firstSelected.judul).trigger('change');
                    }, 500); // Atur delay jika dibutuhkan agar data ter-load sepenuhnya
                });

                var requestModal = new bootstrap.Modal($('#formRequestModal'));
                requestModal.show();
            } else {
                alert('Please select at least one checkbox to edit.');
            }
        });


        $(document).on('click', '#btn-delete', function() {
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
                    const res = await fetch(`{{ url('api/superadmin/akses_pelatihan/delete') }}`, customPost({
                        ids: idsToDelete // Kirim array ID ke server
                    }));
                    const result = await res.json();

                    const {
                        status,
                        message
                    } = result;

                    if (status) {
                        refreshDT(); // Panggil fungsi untuk menyegarkan DataTable
                        blockUI(message, _.DELETE);

                        setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/akses_pelatihan') }}`
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

            $('#request-admin-form').on('submit', function(e) {
                e.preventDefault();

                var isValid = true;

                // Validasi Tipe Pelatihan
                if ($('#pelatihan_id').val() === '') {
                    isValid = false;
                    $('#pelatihan_id-error').text('Tipe Pelatihan harus dipilih.');
                } else {
                    $('#pelatihan_id-error').text('');
                }

                // Validasi Judul Pelatihan
                if ($('#judul_pelatihan_id').val() === '') {
                    isValid = false;
                    $('#judul_pelatihan_id-error').text('Judul Pelatihan harus dipilih.');
                } else {
                    $('#judul_pelatihan_id-error').text('');
                }

                // Validasi Nama Perusahaan
                if ($('#company_id').val() === '') {
                    isValid = false;
                    $('#company_id-error').text('Nama Perusahaan harus dipilih.');
                } else {
                    $('#company_id-error').text('');
                }

                // Validasi Total Karyawan
                if ($('#employe_total').val() === '') {
                    isValid = false;
                    $('#employe_total-error').text('Total Karyawan harus diisi.');
                } else {
                    $('#employe_total-error').text('');
                }

                // Jika ada field yang tidak valid, hentikan submit
                if (!isValid) {
                    return;
                }

                // Jika validasi berhasil, lanjutkan dengan submit AJAX
                var formData = new FormData(this);
                formData.append('token', getCurrentToken()['token']);
                formData.append('userLoggedIn', @json(Auth::id()));
                var url = '';

                if ($('input[name="user_id"]').val() === '') {
                    url = `{{ url('api/superadmin/akses_pelatihan/create') }}`;
                } else {
                    url = `{{ url('api/superadmin/akses_pelatihan/update') }}/${$('input[name="user_id"]').val()}`;
                    formData.append('_method', 'post');
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        __isBtnSaveOnProcessing(__querySelector(
                            '#request-admin-form #btn-save'), false);

                        if (response.success) {
                            $('#formRequestModal').modal('hide');
                            blockUI('Berhasil');

                            $('#formRequestModal').on('hidden.bs.modal', function() {
                                setTimeout(function() {
                                    window.location.href =
                                        `{{ url('/superadmin/akses_pelatihan') }}`
                                }, 2000);
                            });
                        } else {
                            blockUI('Terjadi kesalahan. Silahkan coba lagi.', _.ERROR);
                        }
                    },
                    error: function(xhr) {
                        __isBtnSaveOnProcessing(__querySelector(
                            '#request-admin-form #btn-save'), false);

                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            blockUI(xhr.responseJSON.message, _.ERROR);
                        } else {
                            blockUI('Terjadi kesalahan. Silahkan coba lagi.', _.ERROR);
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
            const categoryId = event.target.value;
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
                    url: `{{ url('api/superadmin/akses_pelatihan/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                        'kategori_id': categoryId
                    }),
                    error: function({
                        responseJSON: {
                            message
                        }
                    }, error, thrown) {
                        blockUI(message, 'error');
                    }
                },
                columns: [{
                        // Kolom Checkbox
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var jsonData = JSON.stringify(row).replace(/"/g,
                                '&quot;'); // Escape double quotes for HTML attribute
                            return `<input type="checkbox" class="row-checkbox" id="data-checked"
                                        data-id='${row.id}'
                                        data-type='${row.type}'
                                        data-judul='${row.judul_pelatihan_id}'
                                        data-company='${row.company_id}'
                                        data-employetotal='${row.employe_total}'
                                        value="${row.id}">`;
                        }
                    },
                    {
                        data: 'code',
                        orderable: false,
                    },
                    {
                        data: 'type',
                        orderable: false,
                        render: function(data) {
                            return data ? data : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: 'judul_pelatihan_id',
                        orderable: false,
                        render: function(data, type, row) {
                             return row.pelatihan  ? row.pelatihan.judul_pelatihan : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: 'company_id',
                        orderable: false,
                        render: function(data, type, row) {
                             return row.company  ? row.company.name : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
                    {
                        data: 'employe_total',
                        orderable: false,
                        render: function(data) {
                            return data ? data : ' - '; // Jika tidak ada, tampilkan ' - '
                        }
                    },
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
                            handleCheckboxes();
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
