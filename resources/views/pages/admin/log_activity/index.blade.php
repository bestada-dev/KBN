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
                        <input  class="form-control pencarian_tanggan" id="datepicker" placeholder="Masukan Tanggal" onchange="searchByDropdown()">
                        {{-- <select class="form-control form-control-sm" id="type_status" onchange="searchByDropdown()">
                            <option value="">All Status</option>
                            <option value="Available">Available</option>
                            <option value="NotAvailable">NotAvailable</option>
                        </select> --}}
                    </form>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{url('/superadmin/log-activity')}}">Logs</a></li>
                          <li class="breadcrumb-item"><span>Activity Logs</span></li>
                        </ol>
                    </nav>
                    {{-- <a href="" class="btn btn-orange btn-sm" style="font-weight:600;margin-bottom:5px"> <img src="{{ asset('assets/add.png') }}"> Add Media</a> --}}
                </div>
                {{-- <div class="col-md-4 d-flex justify-content-end" style="display: flex; justify-content: flex-end; gap: 5px;">
                    <a href="{{ url('superadmin/property/create') }}" class="btn btn-blue btn-sm btn-add-data" style="font-weight:600;margin-bottom:5px">
                        <img src="{{ asset('plus.png') }}">
                    </a>
                </div> --}}
            </div>
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">Administrator</th>
                        <th class="pt-0">Date</th>
                        <th class="pt-0">Activity</th>
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

        function searchTerm() {
            DataTables(__getId('search-datatable').value);
        }

        function searchByDropdown() {
            const searchTerm = __getId('search-datatable').value;
            const tanggal = document.getElementById('datepicker').value;


            // Panggil DataTables dengan parameter tambahan untuk kategori_pelatihan dan tipe tes
            DataTables(searchTerm, tanggal);
        }



        function DataTables(searchTerm = '', tanggal = undefined) {
            console.log(tanggal);
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: `{{ url('api/superadmin/log-activity/data-table') }}`,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'search[value]': searchTerm,
                        'tanggal': tanggal, // Kirim tipe tes ke backend
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
                            return row.user_name ?? '-' ;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return row.date ? moment(row.date).format('DD/MM/YYYY, HH:mm') : '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return `
                                <span style="color: #757575;">${row.type || '-'}</span>
                                <span>${row.activity || '-'}</span>
                            `;
                        }
                    },



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
