@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Certificate')

@section('breadcumbSubtitle', 'Certificate List')

@section('content')


    <article class="">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
                <a href="{{url('/pelatihan_saya/create')}}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">TambahData</a>
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-12">
                    <div class="d-flex gap-3 itemscenter">
                        <a href="{{ url()->previous() }} ">
                            <img src="{{ asset('assets/back.png') }}"></a>
                        </a>
                        <div class="input-group">
                            <span class="input-group-text"> <img src="{{ asset('assets/search.png') }}"> </span>
                            <input type="text" class="form-control form-control-sm" placeholder="Nama Perusahaan     ..."
                                id="search_company">
                        </div>

                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <!-- <div class="col-md-4">
                    <form>
                        <select class="form-control form-control-sm" id="search-statuses" onchange="searchByDropdown()">
                            <option value="">Kategori Pelatihan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Pengembangan">Pengembangan</option>
                        </select>
                    </form>
                </div> -->
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

            <div class="row">
                <div class="col-md-12">
                <form>
                <div class="form-group" style="margin-top: 15px;">
                            <label for="pelatihan_id" class="form-label">Kategori Pelatihan</label>
                            <select class="form-select" name="type" id="pelatihan_id" onchange="searchByDropdown()">
                                <option value="">Pilih Pelatihan</option>
                                <option value="Pelatihan">Pelatihan Public</option>
                                <option value="Pengembangan">Pengembangan Diri</option>
                            </select>
                            <span class="text-danger error-message" id="pelatihan_id-error"></span>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label for="judul_pelatihan_id" class="form-label">Judul Pelatihan</label>
                            <select class="form-select" name="judul_pelatihan_id" id="judul_pelatihan_id" onchange="searchByDropdown()">
                                <option value="">Pilih Pelatihan</option>
                            </select>
                            <span class="text-danger error-message" id="judul_pelatihan_id-error"></span>
                        </div>
                        </form>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-12">
                    <label class="form-label"> Kategori Pelatihan </label>
                        <select class="form-control form-control-sm" id="kategori_pelatihan" onchange="searchByDropdown()">
                            <option value="">Pilih</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Pengembangan">Pengembangan</option>
                        </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label"> Judul Pelatihan </label>
                        <select class="form-control form-control-sm" id="pelatihan_id" onchange="searchByDropdown()" required disabled>
                        <option value="">Pilih</option>
                        </select>
                </div>
            </div> -->
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Nama Peserta</th>
                        <th>Kategori Pelatihan</th>
                        <th>Judul Pelatihan</th>
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
                    <strong class="" id="detailMediaModalLabel" style="font-size: 1.2rem;color: #2778c4">Sertifikat</strong>
                    <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close-request-media-modal">
                        <img src="{{ asset('assets/close.png') }}" width="70%" />
                    </button>
                </div>
                <form id="request-admin-form">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="pelatihan_id" id="pelatihan_id2" value="">
                    <div class="modal-body">
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="director" class="form-label">Nama Direktur</label>
                            <input type="text" class="form-control" name="director" id="director">
                            <span class="text-danger error-message" id="director-error"></span>
                        </div>
                        <!-- <div class="form-group" style="margin-top: 15px;">
                            <label for="company_id" class="form-label">Nama Perusahaan</label>
                            <select class="form-control" name="company_id" id="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($get_perusahaan as $val)
                                    <option value="{{ $val->id }}">{{ $val->admin_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-message" id="company_id-error"></span>
                        </div> -->
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="company_id" class="form-label">Nama Perusahaan</label>
                            <select class="form-control" name="company_id" id="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach ($get_perusahaan as $val)
                                    <option value="{{ $val->id }}">{{ $val->admin_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-message" id="company_id-error" required></span>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label for="user_id" class="form-label">Nama Peserta</label>
                            <select class="form-select" name="user_id" id="user_id" required>
                                <option value="">Pilih </option>
                            </select>
                            <span class="text-danger error-message" id="user_id-error"></span>
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
        var detailUrl = "{{ url('/superadmin/certificate/detail/') }}";

        function initializeSelect2() {
                $('#company_id, #user_id').select2({dropdownParent: $('#formRequestModal')});
        }
            initializeSelect2();
            $('#formRequestModal').on('shown.bs.modal', function() {
                initializeSelect2();
            });
         

        //     $('#formRequestModal').on('shown.bs.modal', function() {
        //     initializeSelect2();
        // });

        $(document).on('click', '.btn-add-data', function() {
            const judulPelatihan = $('#judul_pelatihan_id').val();
            if(!judulPelatihan) {
                blockUI('Anda harus pilih judul pelatihan terlebih dahulu!', _.ERROR); 
                return;
            }
            
            $('#director').val('Ariadi Nuratmojo');
            $('#company_id').val(''); 
            $('#user_id').val('');
            $('#pelatihan_id2').val(judulPelatihan);

            log(__getId('pelatihan_id2').value, 'OKKELAH')
            // var requestModal = new bootstrap.Modal($('#formRequestModal'));
            // requestModal.show();

    $('#formRequestModal').modal('show');
            // initializeSelect2();
        });

        $('#company_id').on('change', function() {
                var company_id = $(this).val();
                var userIdSelect = $('#user_id');

                userIdSelect.empty().append('<option value="">Nama Peserta</option>');
// debugger
                if (company_id) {
                    $.ajax({
                        url: `{{ url('api/employe/test/find-by-judul-and-company') }}`,
                        type: 'POST',
                        data: {
                            company_id,
                            pelatihan_id: __getId('pelatihan_id2').value
                        },
                        success: function(response) {
                            // debugger;
                            $.each(response, function(index, item) {
                                userIdSelect.append(
                                    `<option value="${item.user_id}">${item.user.admin_name}</option>`
                                );
                            });
                            initializeSelect2()
                        },
                        error: function(xhr) {
                            console.log('Error fetching judul pelatihan:', xhr);
                        }
                    });
                }
            });

     // karna fungsinya sama jadi di rename aja ,,,
        async function saveForm(e) {

if(e) {
    e.preventDefault();
}

__isBtnSaveOnProcessing(__querySelector('#request-admin-form #btn-save'), true);

try {
    let formData = new FormData(__getId('request-admin-form'));
    // debugger;
    formData.append('token', getCurrentToken()['token'])
    formData.append('userLoggedIn', @json(Auth::id()));
    $.ajax({
        url:`{{ url('api/superadmin/certificate/update') }}`,
        method:"POST",
        data: formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success:function(result){

            __isBtnSaveOnProcessing(__querySelector('#request-admin-form #btn-save'), false);

            const {status, message, data} = result;

            if (status) {

                blockUI(message)

                setTimeout(()=> {
                    window.location.href = `{{ url('superadmin/certificate')}}`;
                }, 1500)

            } else {

                blockUI('Ops.. something went wrong!', _.ERROR)
                console.error(message);

            }
        },
        error: function(err) {
            __isBtnSaveOnProcessing(__querySelector('#request-admin-form #btn-save'), false);
            console.error(err);
            const msg = err.responseJSON.message;
            blockUI('Ops.. something went wrong!', _.ERROR)
        }
    })

} catch (error) {
    console.error(error);
    blockUI('Ops.. something went wrong!', _.ERROR)
}
}
     const [ updateForm ] = [ saveForm ];
     const REQUIRED = {
        company_id:'required',
        director:'required',
        user_id:'required',
    }
$('#request-admin-form').submit((e) => {
    e.preventDefault();
}).validate(validateForm(REQUIRED, 'id', updateForm, saveForm));

        // $('#request-admin-form').on('submit', function(e) {
        //         e.preventDefault();

        //         var isValid = true;

        //         // Validasi Tipe Pelatihan
        //         if ($('#pelatihan_id').val() === '') {
        //             isValid = false;
        //             $('#pelatihan_id-error').text('Tipe Pelatihan harus dipilih.');
        //         } else {
        //             $('#pelatihan_id-error').text('');
        //         }

        //         // Validasi Judul Pelatihan
        //         if ($('#judul_pelatihan_id').val() === '') {
        //             isValid = false;
        //             $('#judul_pelatihan_id-error').text('Judul Pelatihan harus dipilih.');
        //         } else {
        //             $('#judul_pelatihan_id-error').text('');
        //         }

        //         // Validasi Nama Perusahaan
        //         if ($('#company_id').val() === '') {
        //             isValid = false;
        //             $('#company_id-error').text('Nama Perusahaan harus dipilih.');
        //         } else {
        //             $('#company_id-error').text('');
        //         }

        //         // Validasi Total Karyawan
        //         if ($('#employe_total').val() === '') {
        //             isValid = false;
        //             $('#employe_total-error').text('Total Karyawan harus diisi.');
        //         } else {
        //             $('#employe_total-error').text('');
        //         }

        //         // Jika ada field yang tidak valid, hentikan submit
        //         if (!isValid) {
        //             return;
        //         }

        //         // Jika validasi berhasil, lanjutkan dengan submit AJAX
        //         var formData = new FormData(this);
        //         formData.append('token', getCurrentToken()['token']);
        //         formData.append('userLoggedIn', @json(Auth::id()));
        //         var url = '';

        //         if ($('input[name="user_id"]').val() === '') {
        //             url = `{{ url('api/superadmin/akses_pelatihan/create') }}`;
        //         } else {
        //             url = `{{ url('api/superadmin/akses_pelatihan/update') }}/${$('input[name="user_id"]').val()}`;
        //             formData.append('_method', 'post');
        //         }

        //         $.ajax({
        //             url: url,
        //             type: 'POST',
        //             data: formData,
        //             contentType: false,
        //             processData: false,
        //             success: function(response) {
        //                 __isBtnSaveOnProcessing(__querySelector(
        //                     '#request-admin-form #btn-save'), false);

        //                 if (response.success) {
        //                     $('#formRequestModal').modal('hide');
        //                     blockUI('Berhasil');

        //                     $('#formRequestModal').on('hidden.bs.modal', function() {
        //                         setTimeout(function() {
        //                             window.location.href =
        //                                 `{{ url('/superadmin/akses_pelatihan') }}`
        //                         }, 2000);
        //                     });
        //                 } else {
        //                     blockUI('Terjadi kesalahan. Silahkan coba lagi.', _.ERROR);
        //                 }
        //             },
        //             error: function(xhr) {
        //                 __isBtnSaveOnProcessing(__querySelector(
        //                     '#request-admin-form #btn-save'), false);

        //                 if (xhr.status === 422) {
        //                     var errors = xhr.responseJSON.errors;
        //                     blockUI(xhr.responseJSON.message, _.ERROR);
        //                 } else {
        //                     blockUI('Terjadi kesalahan. Silahkan coba lagi.', _.ERROR);
        //                 }
        //             }
        //         });
        //     });



        
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
                                    `<option value="${judulPelatihan.id}">#${judulPelatihan.id} - ${judulPelatihan.judul_pelatihan}</option>`
                                );
                            });
                            judulPelatihanSelect.select2();
                        },
                        error: function(xhr) {
                            console.log('Error fetching judul pelatihan:', xhr);
                        }
                    });
                }
            });


            $(document).on('click', '.btn-edit-data', function() {
            var selectedData = [];
            $('.row-checkbox:checked').each(function() {
                var data = {
                    id: $(this).attr('data-id') || '', 
                    director: $(this).attr('data-director') || '',  // Default empty string if missing
                    pelatihan_id: $(this).attr('data-pelatihan-id') || '',  // Default empty string if missing
                    user_id: $(this).attr('data-user-id'), // Default empty string if missing
                    company: $(this).attr('data-company') || '',  // Default empty string if missing
                    kategori: $(this).attr('data-kategori') || ''  // Default empty string if missing
                };

                
                selectedData.push(data);
            });
            
            if (selectedData.length > 0) {
    var firstSelected = selectedData[0];
    $('#formRequestModal').modal('show');

    // Set initial values for fields
    $('#id').val(firstSelected.id);
    $('#director').val(firstSelected.director);
    $('#pelatihan_id2').val(firstSelected.pelatihan_id);
    $('#company_id').val(firstSelected.company).trigger('change');

    // Bind change event only once
    $('#company_id').one('change', function () {
        console.warn('company_id changed, triggering user_id selection');

        // Check if user_id options have loaded
        setTimeout(function () {
            $('#user_id').val(firstSelected.user_id).trigger('change');
            console.log('user_id set to:', $('#user_id').val());
        }, 1000); // Adjust delay as necessary
    });

     // Check if user_id options have loaded
     setTimeout(function () {
            $('#user_id').val(firstSelected.user_id).trigger('change');
            console.log('user_id set to:', $('#user_id').val());
        }, 1000); // Adjust delay as necessary
}
 else {
                alert('Please select at least one checkbox to edit.');
            }
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
                    const res = await fetch(`{{ url('api/superadmin/certificate/delete') }}`, customPost({
                        ids: idsToDelete // Kirim array ID ke server
                    }));
                    const result = await res.json();

                    const { status, message } = result;

                    if (status) {
                        refreshDT(); // Panggil fungsi untuk menyegarkan DataTable
                        blockUI(message, _.DELETE);

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

        function searchTerm() {
            DataTables(__getId('search_company').value);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search_company').value;
            const pelatihan_id = document.getElementById('pelatihan_id').value;
            const judul_pelatihan_id = document.getElementById('judul_pelatihan_id').value;
            

            // Panggil DataTables dengan parameter tambahan untuk kategori_pelatihan dan tipe tes
            DataTables(searchTerm, pelatihan_id, judul_pelatihan_id);
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


        function DataTables(searchTerm = '', pelatihan_id = undefined, judul_pelatihan_id  = undefined) {
            // Set DataTable's error mode to 'none' to prevent alerts
            $.fn.dataTable.ext.errMode = 'none';

            const ajaxConfig = {
                ...propertyDB, // Assuming `propertyDB` is a predefined object
                ajax: {
                    url: `{{ url('api/superadmin/certificate/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: {
                        ...getCurrentToken(), // Assuming getCurrentToken() adds required authentication tokens
                        'search[value]': searchTerm,
                        'pelatihan_id': pelatihan_id,
                        'judul_pelatihan_id': judul_pelatihan_id
                    },
                    error: function(xhr, error, thrown) {
                        const message = xhr?.responseJSON?.message || 'An error occurred';
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        // Kolom Checkbox
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var jsonData = JSON.stringify(row).replace(/"/g,
                                '&quot;'); // Escape double quotes for HTML attribute
                          

                                        return `<input type="checkbox" class="row-checkbox" id="data-checked"
            data-id='${row.id}'
            data-director='${row.director}'
            data-kategori='${row.pelatihan ? row.pelatihan.kategori : ''}' 
            data-pelatihan-id='${row.pelatihan ? row.pelatihan.id : ''}'
            data-user-id='${row.user_id}'
            data-company='${row.user.company_id ? row.user.company_id : ''}'
            value="${row.id}">`;

                        }
                    },
                    {
                        data: 'certificate_number',
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
                        data: 'company_name',
                        orderable: false,
                        render: function(data, type, row) {
                            return row.user.company ? row.user.company.name : '-';
                        }
                    },
                    {
                        data: 'nama_peserta',
                        orderable: false,
                        render: function(data, type, row) {
                            return row.user.admin_name ? row.user.admin_name : '-';
                        }
                    },
                    {
                        data: 'kategori',
                        orderable: false,
                        render: function(data, type, row) {
                            return row.pelatihan ? row.pelatihan.kategori : '-';
                        }
                    },
                    {
                        data: 'judul_pelatihan',
                        orderable: false,
                        render: function(data, type, row) {
                            return row.pelatihan ? row.pelatihan.judul_pelatihan : '-';
                        }
                    },
                ],
                initComplete: function() {
                    // Initialize checkbox behavior when the DataTable is loaded
                    if (!initCompleteCalled) {  // Check if initComplete has not been called yet
                        var dataLength = table.rows().data().length;
                        if (dataLength < 1) {
                            // $('.TABLE-WITHOUT-SEARCH-BAR').show();
                            // $('.SEARCH').hide();
                            // $('.TABLE').hide();
                        } else {
                            $('.TABLE-WITHOUT-SEARCH-BAR').hide();
                            $('.SEARCH').show();
                            $('.TABLE').show();
                            handleCheckboxes();
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
            table = $('#data-table').DataTable(ajaxConfig);

            // Handle checkbox changes dynamically in the DataTable
            $('#data-table tbody').on('change', '.row-checkbox', function() {
                handleCheckboxes();
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
