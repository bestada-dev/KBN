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
                {{-- <div class="col-md-4">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searhMediaByCategory(event)">
                            <option value="">Pilih</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </form>
                </div> --}}
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{url('/superadmin/master/zonning')}}">Management</a></li>
                          <li class="breadcrumb-item"><span>List Zoning</span></li>
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
                        <th class="pt-0">Zone Name</th>
                        <th class="pt-0">Address</th>
                        <th class="pt-0">Link Maps</th>
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
                <form id="form-add-and-update-zoning" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 15px">
                            <label for="zone_name" class="form-label required-label">Zone Name <b>(*)</b></label>
                            <input type="text" class="form-control form-control-sm" name="zone_name" id="zone_name"
                                placeholder="Enter Zone Name">
                            <div id="zone_name_error" class="error-message"></div>
                        </div>
                        <div class="form-group" style="margin-top: 15px">
                            <label for="address" class="form-label required-label">Address <b>(*)</b></label>
                            <textarea name="address" id="address" class="form-control form-control-sm" cols="10" rows="3"
                                placeholder="Enter Address"></textarea>
                            <div id="address_error" class="error-message"></div>
                        </div>
                        <div class="form-group" style="margin-top: 15px">
                            <label for="link_map" class="form-label required-label">Link Map <b>(*)</b></label>
                            <input type="text" class="form-control form-control-sm" name="link_map" id="link_map"
                                placeholder="Masukan Link Maps">
                            <div id="link_map_error" class="error-message"></div>
                        </div>

                        <div id="form-container">
                            <div class="row form-row" style="margin-top: 5px">
                                <div class="col-md-8">
                                    <div class="form-group" style="margin-top: 15px">
                                        <label for="strategic_location" class="form-label required-label">Strategic
                                            Location <b>(*)</b></label>
                                        <input type="text" class="form-control form-control-sm"
                                            name="strategic_location[]" placeholder="Masukan Strategic Location">
                                        <div id="strategic_location_error_0" class="error-message" style="color: red; font-size: 9px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-top: 15px">
                                        <label for="distance" class="form-label required-label">Distance
                                            <b>(*)</b></label>
                                        <div class="input-group" style="max-width: 300px;">
                                            <!-- Dropdown -->
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                id="dropdownMenuButton_1">
                                                KM
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_1">
                                                <li><a class="dropdown-item" href="#" data-value="KM">KM</a></li>
                                                <li><a class="dropdown-item" href="#" data-value="M">M</a></li>
                                            </ul>
                                            <!-- Input Hidden for distance_type -->
                                            <input type="hidden" name="distance_type[]" value="KM">
                                            <!-- Input Number -->
                                            <input type="number" class="form-control" placeholder="Enter amount"
                                                name="distance[]">
                                        </div>
                                        <div id="distance_error_0" class="error-message" style="color: red; font-size: 9px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end" style="margin-top: 5px">
                                    <button class="btn btn-danger btn-sm btn-remove-form" type="button"
                                        style="display: none;">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add More Button -->
                        <div class="col-md-12 d-flex justify-content-end" style="margin-top: 15px">
                            <button class="btn btn-success btn-sm" type="button" id="addMoreBtn"><i
                                    class="bi bi-plus"></i> Add More</button>
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

    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Google Maps Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="mapIframe" src="" frameborder="0" style="width: 100%; height: 100%;" allowfullscreen></iframe>
                </div>
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
            }, 'Are you sure?', 'You want the cancel to adding Zonning', 'Yes', 'No')

        }

        $(document).ready(function() {
            const defaultDistanceType = "KM";

            // Event handler untuk dropdown
            $(document).on('click', '.dropdown-menu a', function(e) {
                e.preventDefault();

                // Ambil teks dan nilai dari item yang dipilih
                const selectedText = $(this).text();
                const selectedValue = $(this).data('value');

                // Perbarui tombol dropdown
                const dropdownButton = $(this).closest('.input-group').find('.dropdown-toggle');
                dropdownButton.text(selectedText);

                // Perbarui input hidden
                const hiddenInput = $(this).closest('.input-group').find('input[name="distance_type[]"]');
                hiddenInput.val(selectedValue);

                console.log('Selected Value:', selectedValue);
            });

            // Tambahkan form baru
            let formCount = 1;
            $('#addMoreBtn').on('click', function() {
                formCount++;

                // Clone form pertama
                let newForm = $('.form-row').first().clone();

                // Reset nilai input pada form baru
                newForm.find('input').val('');
                newForm.find('input[name="distance_type[]"]').val(
                defaultDistanceType); // Set nilai default "KM"
                newForm.find('.dropdown-toggle').text(defaultDistanceType); // Set label dropdown ke "KM"
                newForm.find('.btn-remove-form').show(); // Tampilkan tombol remove pada form baru

                // Tambahkan form baru ke container
                $('#form-container').append(newForm);

                // Toggle visibility tombol remove
                toggleRemoveButton();
            });

            // Hapus form
            $(document).on('click', '.btn-remove-form', function() {
                $(this).closest('.form-row').remove();
                formCount--;
                toggleRemoveButton();
            });

            // Tampilkan / sembunyikan tombol remove
            function toggleRemoveButton() {
                if (formCount <= 1) {
                    $('.btn-remove-form').hide();
                } else {
                    $('.btn-remove-form').show();
                }
            }
        });

        $(document).on('click', '.btn-preview-map', function () {
            const mapLink = $(this).data('link');
            let embedUrl;

            // Check and convert the link to an embeddable URL
            if (mapLink.includes('/place/')) {
                const coordinates = mapLink.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                if (coordinates) {
                        embedUrl = `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10000!2d${coordinates[2]}!3d${coordinates[1]}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z${coordinates[1]}!5e0!3m2!1sen!2sid!4v${Date.now()}`;
                } else {
                    alert("The link is not valid for embedding.");
                    return;
                }
            } else {
                alert("The link format is not supported for embedding.");
                return;
            }

            // Set the URL to iframe
            $('#mapIframe').attr('src', embedUrl);

            // Show the modal
            $('#mapModal').modal('show');
        });

        // Clear the iframe when modal is closed
        $('#mapModal').on('hidden.bs.modal', function () {
            $('#mapIframe').attr('src', '');
        });



        $(document).on('click', '.btn-add-data', function() {
            $('#detailMediaModalLabel').text('Add Zoning');
            $('#user_id').val('')
            $('#zone_name').val('')
            $('#address').val('')
            $('#link_map').val('')
            $('#strategic_location*').val('')
            $('#distance*').val('')
            $('#distance_type*').val('')
            var requestModal = new bootstrap.Modal($('#formRequestModal'));
            requestModal.show();
        });

        $(document).on('click', '.btn-edit-data', function(e) {
            e.preventDefault(); // Mencegah reload halaman jika href="#" digunakan
            $('#detailMediaModalLabel').text('Update Zoning');


            // Ambil data dari atribut data-*
            var userId = $(this).data('id');
            var zoneName = $(this).data('zone_name');
            var address = $(this).data('address');
            var linkMaps = $(this).data('link_map');

            // Isi form modal dengan data yang diperoleh
            $('#user_id').val(userId);
            $('#zone_name').val(zoneName);
            $('#address').val(address);
            $('#link_map').val(linkMaps);

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
                    const res = await fetch(`{{ url('api/superadmin/master/zoning/delete') }}`,
                        customPost({
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
                            window.location.href = `{{ url('/superadmin/master/zonning') }}`
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
            $('#form-add-and-update-zoning').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('token', getCurrentToken()['token'])
                formData.append('userLoggedIn', @json(Auth::id()))
                var email = $('#email').val();
                var url = ''; // URL untuk AJAX request
                __isBtnSaveOnProcessing(__querySelector('#form-add-and-update-zoning #btn-save'), true);

                if ($('input[name="user_id"]').val() === '') {
                    url = `{{ url('api/superadmin/master/zoning/create') }}`;
                } else {
                    url =
                        `{{ url('api/superadmin/master/zoning/update') }}/${$('input[name="user_id"]').val()}`;
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
                                '#form-add-and-update-zoning #btn-save'), false);
                            $('#formRequestModal').modal('hide');

                            blockUI('Berhasil');

                            setTimeout(function() {
                                if (!$('#formRequestModal').hasClass(
                                        'show')) {
                                    window.location.href =`{{ url('/superadmin/master/zonning') }}`
                                }
                            }, 2000);

                        } else {
                            __isBtnSaveOnProcessing(__querySelector(
                                '#form-add-and-update-zoning #btn-save'), false);

                            blockUI('Terjadi kesalahan. Silahkan coba lagi.')
                        }
                    },
                    error: function(xhr) {
                        // Jika status 422 (Validasi Gagal)
                        if (xhr.status === 422) {
                            __isBtnSaveOnProcessing(__querySelector(
                                '#form-add-and-update-zoning #btn-save'), false);

                            var errors = xhr.responseJSON.errors;

                            // Tampilkan error yang spesifik di elemen tertentu
                            if (errors.zone_name) {
                                $('#zone_name_error').html(
                                    '<div style="color: red;font-size: 9px;">' + errors
                                    .zone_name[0] + '</div>'
                                );
                            }

                            if (errors.address) {
                                $('#address_error').html(
                                    '<div style="color: red;font-size: 9px;">' + errors
                                    .address[0] + '</div>'
                                );
                            }

                            if (errors.link_map) {
                                $('#link_map_error').html(
                                    '<div style="color: red;font-size: 9px;">' + errors
                                    .link_map[0] + '</div>'
                                );
                            }

                            Object.keys(errors).forEach(function(key) {
                                // Periksa apakah key memiliki pola seperti "strategic_location.0", "distance.1"
                                if (key.startsWith('strategic_location')) {
                                    var index = key.split('.')[1]; // Ambil indeks (misalnya "0", "1")
                                    $('#strategic_location_error_' + index).html(
                                        '<div style="color: red;font-size: 9px;">' + errors[key][0] + '</div>'
                                    );
                                }

                                if (key.startsWith('distance')) {
                                    var index = key.split('.')[1]; // Ambil indeks (misalnya "0", "1")
                                    $('#distance_error_' + index).html(
                                        '<div style="color: red;font-size: 9px;">' + errors[key][0] + '</div>'
                                    );
                                }
                            });
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
                    url: `{{ url('api/superadmin/master/zoning/data-table') }}`,
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
                            return row.zone_name ? row.zone_name : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.address ? row.address : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.link_map) {
                                return `
                                    <button class="btn btn-link btn-preview-map" data-link="${row.link_map}">
                                        See Maps
                                    </button>
                                `;
                            }
                            return '-';
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
                                    <li><a class="dropdown-item text-success" onclick="clickUpdate(${data.id})">Edit</a></li>
                                    <li><a class="dropdown-item text-danger" id="btn-delete" data-id="${data.id}" href="#">Delete</a></li>

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

        function clickUpdate(id){
            console.log("ID:", id);
            const url = `{{ url('/superadmin/master/zonning/edit') }}/${id}`;
            console.log("Redirecting to:", url);
            window.location.href = url;
        }


        function handleStatusToggle(id, isActive) {
            console.log(`ID: ${id}, Status: ${isActive ? 'Active' : 'Inactive'}`);
            const label = document.querySelector(`label[for="statusToggle-${id}"]`);

            if (label) {
                label.textContent = isActive ? 'Active' : 'Inactive';
            }



            const confirmation = confirm(
                `Are you sure you want to change the status to ${isActive ? 'Active' : 'Inactive'}?`);
            if (!confirmation) {
                // Kembalikan toggle ke status sebelumnya jika dibatalkan
                const toggle = document.getElementById(`statusToggle-${id}`);
                toggle.checked = !isActive;
                return;
            }

            // Kirim permintaan AJAX untuk memperbarui status
            $.ajax({
                url: `{{ url('api/superadmin/master/zoning/changeStatus') }}/${id}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // Pastikan CSRF token ada
                    user_status_id: isActive ? 'on' : 'off'
                },
                success: function(response) {
                    if (response.success) {
                        blockUI(response.message); // Tampilkan pesan sukses
                        setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/master/zonning') }}`
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
