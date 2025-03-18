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
                <div class="col-md-4">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searhMediaByCategory(event)">
                            <option value="">Pilih</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{url('/superadmin/admin')}}">Administrator</a></li>
                          <li class="breadcrumb-item"><span>List Admin</span></li>
                        </ol>
                    </nav>

                </div>
                <div class="col-md-4 d-flex justify-content-end" style="display: flex; justify-content: flex-end; gap: 5px;">
                    <button class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </button>
                </div>
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">Name</th>
                        <th class="pt-0">Phone Number</th>
                        <th class="pt-0">Email</th>
                        {{-- <th class="pt-0">Password</th> --}}
                        <th class="pt-0">Status</th>
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
                            <div id="nameError" class="error-message"></div>
                        </div>
                        <div class="form-group" style="margin-top: 15px">
                            <label for="phone_number" class="form-label required-label">Phone Number <b>(*)</b></label>
                            <input type="text" class="form-control form-control-sm" name="phone_number" id="phone_number"
                                placeholder="Enter Phone Number">
                            <div id="phoneError" class="error-message"></div>
                        </div>
                        <div class="form-group" style="margin-top: 15px">
                            <label for="email" class="form-label required-label">Email <b>(*)</b></label>
                            <input type="email" class="form-control form-control-sm" name="email" id="email"
                                placeholder="Masukan Email">
                            <div id="emailError" class="error-message"></div>
                        </div>
                        <div class="form-group" style="margin-top: 15px">
                            <label for="password" class="form-label required-label">Password <b>(*)</b></label>
                            <input type="password" class="form-control form-control-sm" name="password" id="password"
                                placeholder="Enter Password">
                            <div id="passwordError" class="error-message"></div>
                        </div>

                        <div class="form-group" style="margin-top: 15px">
                            <label for="status" class="form-label required-label">Status <b>(*)</b></label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="statusToggle"
                                        data-value="2",
                                        name="user_status_id",
                                        onchange="updateStatusLabel()"
                                    >
                                </div>
                                <span id="statusLabel" class="status-label">Inactive</span>
                            </div>
                            {{-- <div id="reasonError" class="error-message"></div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" onclick="onCancel()">Batal</button>
                        <button type="submit" class="btn btn-main btn-sm" id="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .dropdown-menu {
    position: absolute !important;
    z-index: 1050; /* Lebih tinggi dari elemen lainnya */
}
.table {
    overflow: visible !important; /* Hindari pemotongan dropdown */
}
    </style>
@endsection

@section('js')
    <script>
        function updateStatusLabel() {
            const toggle = document.getElementById('statusToggle');
            const label = document.getElementById('statusLabel');

            if (toggle.checked) {
                label.textContent = 'Active';
                label.style.color = '#28a745'; // Warna hijau
                toggle.setAttribute('data-value', '1'); // Set value to 1 for Active
            } else {
                label.textContent = 'Inactive';
                label.style.color = '#495057'; // Warna abu-abu gelap
                toggle.setAttribute('data-value', '2'); // Set value to 2 for Inactive
            }

            // Log the value for debugging purposes (optional)
            console.log(`Status Value: ${toggle.getAttribute('data-value')}`);
        }

        $(document).on('click', '.btn-add-data', function() {
            $('#detailMediaModalLabel').text('Add Administrator');
            $('#user_id').val('')
            $('#name').val('')
            $('#phone_number').val('')
            $('#email').val('')
            $('#password').val('')
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });

        $(document).on('click', '.btn-edit-data', function (e) {
            e.preventDefault(); // Mencegah reload halaman jika href="#" digunakan
            $('#detailMediaModalLabel').text('Update Administrator');


            // Ambil data dari atribut data-*
            var userId = $(this).data('id');
            var userEmail = $(this).data('email');
            var userName = $(this).data('name');
            var userPhone = $(this).data('phone');
            var userStatus = $(this).attr('data-status'); // Status dalam bentuk angka (1 atau 2)

            // Isi form modal dengan data yang diperoleh
            $('#user_id').val(userId);
            $('#email').val(userEmail);
            $('#name').val(userName);
            $('#phone_number').val(userPhone);

            // Atur status toggle dan label
            const statusToggle = $('#statusToggle');
            const statusLabel = $('#statusLabel');

            if (userStatus === '1') { // Jika status adalah 1 (Active)
                statusToggle.prop('checked', true); // Centang toggle
                statusToggle.attr('data-value', '1'); // Set value menjadi 1
                statusLabel.text('Active').css('color', '#28a745'); // Label ke Active
            } else if (userStatus === '2') { // Jika status adalah 2 (Inactive)
                statusToggle.prop('checked', false); // Tidak centang toggle
                statusToggle.attr('data-value', '2'); // Set value menjadi 2
                statusLabel.text('Inactive').css('color', '#495057'); // Label ke Inactive
            }

            // Tampilkan modal
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });



        $(document).on('click', '#btn-delete', function () {
            // Ambil semua checkbox yang dicentang
            var id = $(this).attr('data-id')
            console.log(id)
            // Tampilkan konfirmasi sebelum menghapus
            __swalConfirmation(async (data) => {
                try {
                    // Kirim permintaan DELETE untuk setiap ID
                    const res = await fetch(`{{ url('api/superadmin/admin/delete') }}`, customPost({
                        ids: id // Kirim array ID ke server
                    }));
                    const result = await res.json();

                    const { status, message } = result;

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
            }, 'Are you sure?', 'You want the cancel to adding administrator', 'Yes', 'No')

        }


        // function onCancel() {
        //     return __swalConfirmation(
        //         async (data) => {
        //             console.log(data);
        //             try {
        //                 // Jika pengguna memilih "Yes"
        //                 if (data.isConfirmed) {
        //                     setTimeout(function () {
        //                         if (!$('#formRequestModal').hasClass('show')) {
        //                             // Tutup dropdown secara manual
        //                             $('.dropdown-menu').removeClass('show');

        //                             // Arahkan ke URL yang ditentukan
        //                             window.location.href = `{{ url('/superadmin/admin') }}`;
        //                         }
        //                     }, 200);
        //                 } else if (data.isDismissed) {
        //                     // Jika pengguna memilih "No", dropdown tetap terbuka
        //                     console.log('Action canceled, dropdown remains open.');
        //                 }
        //             } catch (error) {
        //                 console.error(error);
        //                 blockUI('Ops.. something went wrong!', _.ERROR);
        //             }
        //         },
        //         'Are you sure to logout?',
        //         'You want to cancel adding administrator',
        //         'Yes',
        //         'No'
        //     );
        // }


        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Saat form disubmit
            $('#request-admin-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('token', getCurrentToken()['token'])
                formData.append('userLoggedIn', @json(Auth::id()))
                var email = $('#email').val();
                var url = ''; // URL untuk AJAX request
                __isBtnSaveOnProcessing(__querySelector('#request-admin-form #btn-save'), true);

                if ($('input[name="user_id"]').val() === '') {
                    url = `{{ url('api/superadmin/admin/create') }}`;
                } else {
                    url = `{{ url('api/superadmin/admin/update') }}/${$('input[name="user_id"]').val()}`;
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
                                '#request-admin-form #btn-save'), false);
                            $('#formRequestModal').modal('hide');

                            blockUI('Berhasil');

                            setTimeout(function() {
                                if (!$('#formRequestModal').hasClass(
                                    'show')) {
                                    window.location.href = `{{ url('/superadmin/admin') }}`
                                }
                            }, 2000);

                        } else {
                            __isBtnSaveOnProcessing(__querySelector(
                                '#request-admin-form #btn-save'), false);

                            blockUI('Terjadi kesalahan. Silahkan coba lagi.')
                        }
                    },
                    error: function(xhr) {
                        // Jika status 422 (Validasi Gagal)
                        if (xhr.status === 422) {
                            __isBtnSaveOnProcessing(__querySelector(
                                '#request-admin-form #btn-save'), false);

                            var errors = xhr.responseJSON.errors;

                            // Tampilkan error yang spesifik di elemen tertentu
                            if (errors.password) {
                                $('#passwordError').html(
                                    '<div style="color: red;font-size: 9px;">' + errors.password[0] + '</div>'
                                );
                            }

                            if (errors.email) {
                                $('#emailError').html(
                                    '<div style="color: red;font-size: 9px;">' + errors.email[0] + '</div>'
                                );
                            }

                            if (errors.name) {
                                $('#nameError').html(
                                    '<div style="color: red;font-size: 9px;">' + errors.name[0] + '</div>'
                                );
                            }

                            if (errors.phone_number) {
                                $('#phoneError').html(
                                    '<div style="color: red;font-size: 9px;">' + errors.phone_number[0] + '</div>'
                                );
                            }
                            // Anda bisa menambahkan logika untuk field error lainnya
                        } else {
                            // Tangani error umum lainnya
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



        function DataTables(searchTerm, categoryId = undefined) {
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: `{{ url('api/superadmin/admin/data-table') }}`,
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
                            return row.phone_number ? row.phone_number : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.email ? row.email : '-';
                        }
                    },
                    {
                        data: 'user_status_id',
                        orderable: false,
                        render: function(data) {
                            // Cek status dan kembalikan badge yang sesuai
                            if (data == 1) {
                                return '<span class="badge" style="background-color:#D5FFCC; color:#1EB200">Active</span>'; // Untuk status 1
                            } else {
                                return '<span class="badge" style="background-color:#E6E6E5; color:#B4B3B1">Inaactive</span>'; // Untuk status lainnya
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data) {
                            return `
                                <div class="dropdown">
                                    <button
                                        class="btn btn-link p-0"
                                        type="button"
                                        id="dropdownMenuButton-${data.id}"
                                        data-bs-toggle="dropdown"
                                        data-bs-boundary="viewport"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i> Action
                                    </button>
                                    <ul
                                        class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="dropdownMenuButton-${data.id}"
                                        style="z-index: 1050; max-width: 300px;">
                                        <li>
                                            <a
                                                class="dropdown-item text-success btn-edit-data"
                                                href="#"
                                                data-id="${data.id}"
                                                data-email="${data.email}"
                                                data-name="${data.name}"
                                                data-phone="${data.phone_number}"
                                                data-status="${data.user_status_id}">
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="dropdown-item text-danger"
                                                id="btn-delete"
                                                data-id="${data.id}"
                                                href="#">
                                                Delete
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li class="px-3">
                                            <div class="d-flex align-items-center">
                                                <span>Status:</span> &nbsp;&nbsp;
                                                <div class="form-check form-switch ms-auto">
                                                    <input
                                                        class="form-check-input toggle-status"
                                                        type="checkbox"
                                                        id="statusToggle-${data.id}"
                                                        ${data.user_status_id == 1 ? 'checked' : ''}
                                                        onchange="handleStatusToggle(${data.id}, this.checked)">
                                                    <label
                                                        class="form-check-label ms-2"
                                    for="statusToggle-${data.id}">
                                    ${data.user_status_id === 1 ? 'Active' : 'Inactive'}
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        `;
    }
}


                ],
                initComplete: function() {
                    // Inisialisasi checkbox behavior
                    // handleCheckboxes(); // Panggil fungsi untuk mengatur status tombol
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

            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#data-table')) {
                $('#data-table').DataTable().clear().destroy();
            }

            // Initialize DataTable with the prepared ajaxConfig
            table = $('#data-table').DataTable(ajaxConfig);

            // Attach event listeners to checkbox changes
            $('#data-table tbody').on('change', '.row-checkbox', function() {
            });

            // Initialize FixedHeader
            if ($.fn.dataTable.FixedHeader) {
                new $.fn.dataTable.FixedHeader(table);
            }
        }

        // Call DataTables function to initialize
        DataTables();

        function handleStatusToggle(id, isActive) {
            console.log(`ID: ${id}, Status: ${isActive ? 'Active' : 'Inactive'}`);
            const label = document.querySelector(`label[for="statusToggle-${id}"]`);

            if (label) {
                label.textContent = isActive ? 'Active' : 'Inactive';
            }



            const confirmation = confirm(`Are you sure you want to change the status to ${isActive ? 'Active' : 'Inactive'}?`);
            if (!confirmation) {
                // Kembalikan toggle ke status sebelumnya jika dibatalkan
                const toggle = document.getElementById(`statusToggle-${id}`);
                toggle.checked = !isActive;
                return;
            }

            // Kirim permintaan AJAX untuk memperbarui status
            $.ajax({
                url: `{{ url('api/superadmin/admin/changeStatus') }}/${id}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // Pastikan CSRF token ada
                    user_status_id: isActive ? 'on' : 'off'
                },
                success: function(response) {
                    if (response.success) {
                        blockUI(response.message); // Tampilkan pesan sukses
                        setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/admin') }}`
                        }, 1000); // 3000 milidetik = 3 detik
                    } else {
                        blockUI('Failed to update status: ' + response.message, _.ERROR);
                    }
                },
                error: function(xhr) {
                    blockUI('An error occurred. Please try again.');
                    console.error(xhr.responseText); // Debugging log jika ada error
                }
            });
        }


    </script>
@endsection
