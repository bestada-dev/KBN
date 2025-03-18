@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Evaluasi Level 3')

@section('breadcumbSubtitle', 'Evaluasi Level 3 List')

@section('content')


    <article class="">
        <!-- ------------------------- Jika tidak ada data ------------------------- -->
        <div class="TABLE-WITHOUT-SEARCH-BAR" style="display:none">
            <z>
                <img src="{{ asset('assets/no-data.png') }}">
                <b>Data belum tersedia</b>
                <a href="{{url('/superadmin/evaluasi_level_3/create')}}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">TambahData</a>
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
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Judul atau ID Pelatihan"
                                id="search-datatable">
                        </div>
                        <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-3 itemscenter">
                        <div class="input-group">
                            <select name="pelatihan" id="pelatihan" class="form-control" onchange="searchByDropdown()">
                                <option value="">Pilih Pelatihan</option>
                                <option value="Pelatihan">Pelatihan Public</option>
                                <option value="Pengembangan">Pengembangan Diri</option>
                            </select>
                        </div>
                    </div>
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
                    <a href="{{url('/superadmin/evaluasi_level_3/create')}}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </a>
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
        var detailUrl = "{{ url('/superadmin/evaluasi_level_3/detail/') }}";

        $(document).on('click', '.btn-edit-data', function() {
            var getId = $('.row-checkbox:checked').attr('data-id');
            if (getId) {
                // Redirect ke halaman edit/{id}
                window.location.href = '/superadmin/evaluasi_level_3/update/' + getId;
            } else {
                blockUI('Pilih data terlebih dahulu.', _.ERROR);
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
                    const res = await fetch(`{{ url('api/superadmin/evaluasi_level_3/delete') }}`, customPost({
                        ids: idsToDelete // Kirim array ID ke server
                    }));
                    const result = await res.json();

                    const { status, message } = result;

                    if (status) {
                        refreshDT(); // Panggil fungsi untuk menyegarkan DataTable
                        blockUI("berhasil");

                        setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/evaluasi_level_3') }}`
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

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searhMediaByCategory(event) {
            const categoryId = event.target.value;;
            DataTables(__getId('search-datatable').value, categoryId);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const pelatihan = document.getElementById('pelatihan').value;

            // Panggil DataTables dengan parameter tambahan untuk pelatihan dan tipe tes
            DataTables(searchTerm, pelatihan);
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


        function DataTables(searchTerm = '', categoryId = undefined) {
            // Set DataTable's error mode to 'none' to prevent alerts
            $.fn.dataTable.ext.errMode = 'none';

            const ajaxConfig = {
                ...propertyDB, // Assuming `propertyDB` is a predefined object
                ajax: {
                    url: `{{ url('api/superadmin/evaluasi_level_3/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: {
                        ...getCurrentToken(), // Assuming getCurrentToken() adds required authentication tokens
                        'search[value]': searchTerm,
                        'pelatihan': categoryId,
                    },
                    error: function(xhr, error, thrown) {
                        const message = xhr?.responseJSON?.message || 'An error occurred';
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="row-checkbox" data-id="${row.id}" value="${row.id}">`;
                        }
                    },
                    {
                        data: 'code',
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
                        data: 'pelatihan',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return row.pelatihan ? row.pelatihan.kategori : '';
                        }
                    },
                    {
                        data: 'judul_pelatihan',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return row.pelatihan ? row.pelatihan.judul_pelatihan : '';
                        }
                    },
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
            const table = $('#data-table').DataTable(ajaxConfig);

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
