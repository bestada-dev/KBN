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
                    <a href="{{ url('superadmin/property/create') }}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">Tambah
                        Data</a>
                @endif
            </z>
        </div>

        <!-- ------------------------- Jika ada data ------------------------- -->
        <div class="SEARCH">
            <div class="row w-100">
                <div class="col-md-6">
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
                        <select class="form-control form-control-sm" id="type_status" onchange="searchByDropdown()">
                            <option value="">All Status</option>
                            <option value="Available">Available</option>
                            <option value="NotAvailable">NotAvailable</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    <form>
                        <select class="form-control form-control-sm" id="type_category" onchange="searchByDropdown()">
                            <option value="">Select Zoning</option>
                            @foreach ($category as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                    {{-- <a href="" class="btn btn-orange btn-sm" style="font-weight:600;margin-bottom:5px"> <img src="{{ asset('assets/add.png') }}"> Add Media</a> --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{url('/superadmin/property')}}">Management</a></li>
                          <li class="breadcrumb-item"><span>List Property</span></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 d-flex justify-content-end" style="display: flex; justify-content: flex-end; gap: 5px;">
                    <a href="{{ url('superadmin/property/create') }}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </a>
                </div>
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">Category</th>
                        <th class="pt-0">Zoning</th>
                        <th class="pt-0">Type</th>
                        <th class="pt-0">Link</th>
                        <th class="pt-0">Status</th>
                        <th class="pt-0">Total Viewer</th>
                        <th class="pt-0"></th>
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

    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">Tonton Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="videoFrame" width="100%" height="400" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>

        const baseUrl = "{{ asset('/videos/') }}";
        function openVideoModal(videoPath) {
            // Set URL video dalam iframe
            const videoUrl = `${baseUrl}/${videoPath}`;
            console.log(videoUrl);
            document.getElementById('videoFrame').src = videoUrl;

            // Tampilkan modal
            const videoModal = new bootstrap.Modal(document.getElementById('videoModal'), {});
            videoModal.show();
        }

            // Hapus URL video saat modal ditutup
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('videoFrame').src = '';
        });

        // Hapus URL video saat modal ditutup
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('videoFrame').src = '';
        });


        $(document).on('click', '#btn-delete', function () {
            // Ambil semua checkbox yang dicentang
            var id = $(this).attr('data-id')
            console.log(id)
            // Tampilkan konfirmasi sebelum menghapus
            __swalConfirmation(async (data) => {
                try {
                    // Kirim permintaan DELETE untuk setiap ID
                    const res = await fetch(`{{ url('api/superadmin/property/delete') }}`, customPost({
                        ids: id // Kirim array ID ke server
                    }));
                    const result = await res.json();

                    const { status, message } = result;

                    if (status) {
                        refreshDT(); // Panggil fungsi untuk menyegarkan DataTable
                        blockUI(message);

                        setTimeout(function() {
                            window.location.href = `{{ url('/superadmin/property') }}`
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

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const type_status = document.getElementById('type_status').value;
            const type_category = document.getElementById('type_category').value;


            // Panggil DataTables dengan parameter tambahan untuk kategori_pelatihan dan tipe tes
            DataTables(searchTerm, type_status, type_category);
        }



        function DataTables(searchTerm = '', type_status = undefined, type_category = undefined) {
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: `{{ url('api/superadmin/property/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                        'type_status': type_status, // Kirim tipe tes ke backend
                        'type_category': type_category,
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
                            return row.get_category.name ?? '-' ;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.get_zoning.zone_name ?? '-';
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
                            if (row.type_upload === 'link') {
                                // Tampilkan teks embed untuk link
                                return `<a href="${row.url}" target="_blank">See Vedio</a>`;
                            } else if (row.type_upload === 'upload_vidio') {
                                // Tombol untuk membuka modal video
                                return `
                                    <a href="#"  onclick="openVideoModal('${row.vidio}')">
                                        See Video
                                    </a>`;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'status',
                        orderable: false,
                        render: function(data) {
                            // Cek status dan kembalikan badge yang sesuai
                            if (data == "Available") {
                                return '<span class="badge" style="background-color:#D5FFCC; color:#1EB200">Available</span>'; // Untuk status 1
                            } else {
                                return '<span class="badge" style="background-color:#E6E6E5; color:#B4B3B1">NotAvailable</span>'; // Untuk status lainnya
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.count_viewer.length > 0 ? row.count_viewer.length : 0;
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

        function clickUpdate(id){
            console.log("ID:", id);
            const url = `{{ url('/superadmin/property/update') }}/${id}`;
            console.log("Redirecting to:", url);
            window.location.href = url;
        }

    </script>
@endsection
