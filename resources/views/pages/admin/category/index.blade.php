@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Admin')

@section('breadcumbSubtitle', 'Admin List')

@section('content')


    <article class="">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
                @if (Session::get('data.user.is_admin'))
                    <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">Tambah
                        Data</button>
                @endif
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-8">
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

            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{url('/superadmin/master/category')}}">Management</a></li>
                          <li class="breadcrumb-item"><span>List Category</span></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 d-flex justify-content-end"
                    style="display: flex; justify-content: flex-end; gap: 5px;">
                    <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </button>
                </div>
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">Category</th>
                        <th class="pt-0">Icon</th>
                        <th class="pt-0">Available Properties</th>
                        <th class="pt-0">Action</th>
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
    @include('pages.register.css_upload_file')
    <div class="modal fade custom-modal" id="formRequestModal" tabindex="-1" aria-labelledby="formRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header between">
                    <strong class="" id="detailMediaModalLabel" style="font-size: 1.2rem;color: #2778c4"></strong>
                    <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close-request-media-modal">
                        <img src="{{ asset('assets/close.png') }}" width="70%" />
                    </button>
                </div>
                <form id="request-admin-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 15px">
                            <label for="name" class="form-label required-label">Name <b>(*)</b></label>
                            <input type="text" class="form-control form-control-sm" name="name" id="name"
                                placeholder="Enter Name">
                            <span class="text-danger error-message" id="name-error"></span>
                        </div>

                        <div class="form-group" style="margin-top: 15px">
                            <label for="icon" class="form-label">Icon</label>
                            <div class="file-upload-container">
                                <input type="file" class="file-input" id="icon" name="icon"
                                    accept="image/png, image/jpeg">
                                <div class="file-upload-box" onclick="document.getElementById('icon').click();">
                                    <div class="file-details">
                                        <i class="bi bi-file-earmark"></i>
                                        <div>
                                            <span id="fileName" class="file-name-size">Pilih file</span>
                                            <span id="fileSize" class="file-size"></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn-icon ms-2" id="viewButton">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn-icon ms-2" id="resetButton">
                                    <i class="bi bi-save2"></i>
                                </button>
                            </div>
                            <p class="upload-info">Ukuran maksimum: 5 MB. Format file: PNG atau JPG.</p>
                            <span class="text-danger error-message" id="icon-error"></span>
                        </div>

                        <!-- Modal for Image Preview -->
                        <div class="modal fade" id="imagePreviewModal" tabindex="-1"
                            aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imagePreviewModalLabel">Preview Gambar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img id="modalImagePreview" src="" alt="Preview Gambar"
                                            style="max-width: 100%; max-height: 400px;">
                                        <p id="noImageText" style="display: none;">Tidak ada gambar untuk ditampilkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Batal</button> --}}
                        <button type="button" class="btn btn-default btn-sm" onclick="onCancel()">Batal</button>
                        <button type="submit" class="btn btn-main btn-sm" id="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function onCancel() {
            return __swalConfirmation(async (data) => {
                try {
                    // console.log(data);
                    $('#formRequestModal').modal('hide');
                        // window.location.href='{{ url('logout') }}'
                } catch (error) {
                    console.error(error);
                    blockUI('Ops.. something went wrong!', _.ERROR)
                }
            }, 'Are you sure?', 'You want the cancel to adding Category', 'Yes', 'No')

        }

        $(document).on('click', '.btn-add-data', function() {
            $('#detailMediaModalLabel').text('Add Category');
            $('#user_id').val('')
            $('#name').val('')
            $('#icon').val(''); // Reset input file
            $('#fileName').text('Pilih file'); // Reset teks file name
            $('#fileSize').text(''); // Reset teks ukuran file

            // Reset pratinjau gambar jika ada
            $('#modalImagePreview').attr('src', '#'); // Set src pratinjau menjadi default
            $('#modalImagePreview').hide(); // Sembunyikan gambar
            $('#noImageText').show(); // Tampilkan teks "tidak ada gambar"

            // Tampilkan modal
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });

        $(document).on('click', '.btn-edit-data', function (e) {
            e.preventDefault(); // Mencegah reload halaman jika href="#" digunakan
            $('#detailMediaModalLabel').text('Update Category');

            // Ambil data dari atribut data-*
            var userId = $(this).data('id');
            var name = $(this).data('name');
            var icon = $(this).data('icon'); // Path icon dari data-*

            // Isi form modal dengan data yang diperoleh
            $('#user_id').val(userId);
            $('#name').val(name);

            // Jika ada data icon, tampilkan gambar di modal
            if (icon) {
                $('#fileName').text('File sudah ada');
                $('#fileSize').text('');
                $('#modalImagePreview').attr('src', `/storage/${icon}`).show(); // Pastikan URL benar
                $('#noImageText').hide();
            } else {
                $('#modalImagePreview').hide();
                $('#noImageText').show();
                $('#fileName').text('Pilih file');
            }

            // Tampilkan modal
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });




        $(document).on('click', '#btn-delete', function() {
            // Ambil semua checkbox yang dicentang
            var id = $(this).attr('data-id')
            console.log(id)
            // Tampilkan konfirmasi sebelum menghapus
            __swalConfirmation(async (data) => {
                try {
                    // Kirim permintaan DELETE untuk setiap ID
                    const res = await fetch(`{{ url('api/superadmin/master/category/delete') }}`, customPost({
                        ids: id // Kirim array ID ke server
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
                            window.location.href = `{{ url('/superadmin/admin') }}`
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
            $('#request-admin-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah submit default

                // Hapus pesan error sebelumnya
                $(".error-message").text("");

                var formData = new FormData(this);
                formData.append('token', getCurrentToken()['token'])
                var url = '';

                // Tentukan URL berdasarkan mode (create/update)
                if ($('input[name="user_id"]').val() === '') {
                    url = `{{ url('api/superadmin/master/category/create') }}`;
                } else {
                    url = `{{ url('api/superadmin/master/category/update') }}/${$('input[name="user_id"]').val()}`;
                    formData.append('_method', 'POST'); // Tambahkan untuk update
                }

                // Proses AJAX
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // Tampilkan loading (opsional)
                        $('#btn-save').prop('disabled', true).text('Processing...');
                    },
                    success: function(response) {
                        $('#btn-save').prop('disabled', false).text('Simpan');

                        if (response.success) {
                            // Tutup modal jika berhasil
                            $('#formRequestModal').modal('hide');
                            alert('Data berhasil disimpan.');
                            // Refresh halaman atau data tabel (opsional)
                            // location.reload();
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    error: function(xhr) {
                        $('#btn-save').prop('disabled', false).text('Simpan');

                        if (xhr.status === 422) {
                            // Validasi gagal, tampilkan pesan error
                            var errors = xhr.responseJSON.errors;

                            if (errors.name) {
                                $('#name-error').text(errors.name[0]);
                            }

                            if (errors.icon) {
                                $('#icon-error').text(errors.icon[0]);
                            }
                        } else {
                            alert('Terjadi kesalahan pada server. Silakan coba lagi.');
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



        function DataTables(searchTerm, categoryId = undefined) {
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: `{{ url('api/superadmin/master/category/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                        'status_id': categoryId
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
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.name ? row.name : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.icon) {
                                return `<img src="/storage/${row.icon}" alt="Icon" style="width: 50px; height: 50px;">`;
                            }
                            return '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            // Pastikan row.get_property terdefinisi dan merupakan array
                            return Array.isArray(row.get_property) ? row.get_property.length : 0;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data) {
                            return `
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a
                                            class="dropdown-item text-success btn-edit-data"
                                            href="#"
                                            data-id="${data.id}"
                                            data-name="${data.name}"
                                            data-icon="${data.icon}"
                                        >
                                            Edit
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item text-danger" id="btn-delete" data-id="${data.id}" href="#">Delete</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                </ul>
                            </div>
                        `;
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
            $('#data-table tbody').on('change', '.row-checkbox', function() {});

            // Initialize FixedHeader
            if ($.fn.dataTable.FixedHeader) {
                new $.fn.dataTable.FixedHeader(table);
            }
        }

        // Call DataTables function to initialize
        DataTables();
    </script>

    {{-- untuk popup image  --}}
    <script>
        const icon = document.getElementById('icon');
        const fileNameDisplay = document.getElementById('fileName');
        const fileSizeDisplay = document.getElementById('fileSize');
        const modalImagePreview = document.getElementById('modalImagePreview');
        const noImageText = document.getElementById('noImageText');
        const resetButton = document.getElementById('resetButton');
        const viewButton = document.getElementById('viewButton');
        const imagePreviewModalElement = document.getElementById('imagePreviewModal');
        const imagePreviewModal = new bootstrap.Modal(imagePreviewModalElement); // Initialize the modal

        // Update file details when a file is selected
        icon.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                fileNameDisplay.textContent = file.name;
                fileSizeDisplay.textContent = `• ${(file.size / 1024 / 1024).toFixed(2)} MB`;
            }
        });

        // Show image preview when the view button is clicked
        viewButton.addEventListener('click', function() {
            const file = icon.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalImagePreview.src = e.target.result; // Set the image source in the modal
                    modalImagePreview.style.display = 'block'; // Show the image in modal
                    noImageText.style.display = 'none'; // Hide "no image" text
                    imagePreviewModal.show(); // Explicitly show the modal using Bootstrap's JS method
                };
                reader.readAsDataURL(file);
            } else {
                modalImagePreview.style.display = 'none'; // Hide the image in modal
                noImageText.style.display = 'block'; // Show "no image" text
                imagePreviewModal.show(); // Still show the modal, even if no image is selected
            }
        });

        // Reset file input and clear all fields when the reset button is clicked
        resetButton.addEventListener('click', function() {
            icon.value = ''; // Clear the file input
            fileNameDisplay.textContent = 'Pilih file';
            fileSizeDisplay.textContent = '';

            // Clear modal image preview and reset state
            modalImagePreview.src = '#';
            modalImagePreview.style.display = 'none';
            noImageText.style.display = 'block'; // Show "no image" text
        });

        // Ensure the image is loaded correctly every time the modal is opened
        $('#imagePreviewModal').on('show.bs.modal', function() {
            const file = icon.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalImagePreview.src = e.target.result; // Set the image source in the modal
                    modalImagePreview.style.display = 'block'; // Show the image in modal
                    noImageText.style.display = 'none'; // Hide "no image" text
                };
                reader.readAsDataURL(file);
            } else {
                modalImagePreview.style.display = 'none'; // Hide the image in modal
                noImageText.style.display = 'block'; // Show "no image" text
            }
        });
    </script>
@endsection
