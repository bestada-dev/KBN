@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Pelatihan')

@section('breadcumbSubtitle', 'Pelatihan Create')

@section('content')
<style>
    .p-10 {
        padding: 10px 0px 30px 30px; /* Padding 10px di semua sisi */
    }
    td {
        padding: 5px 9px; /* Menambahkan padding atas-bawah 10px dan kiri-kanan 15px */
    }
</style>
    <article>
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER d-flex justify-content-between align-items-center">
                <a href="{{ url('company/test_setting') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Detail</h5> <!-- Menghapus margin untuk menyelaraskan dengan tombol -->
                <button type="button" id="btn-add-data" class="btn btn-primary ms-auto">Tambah Karyawan</button> <!-- Tombol Sunting -->
            </div>
            <form id="form_detail_pelatihan" class="p-4 pt-3">
                <h5 class="m-0">Evaluasi</h5><br>
                <div class="form-group" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6"><p style="font-size:16px;font-weight:400;">ID</p></div>
                                <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->code}}</p></div>
                            </div>
                        </div>
                        <div class="col-md-8 text-end text-start">
                            @php
                                $statusPelatihan = $get_perusahaan->getPelatihanDanPengembangan->status_pelatihan ?? 'Belum Dimulai';
                            @endphp

                            @if ($statusPelatihan === 'Belum Dimulai')
                                <span class="badge bg-secondary">{{ $statusPelatihan }}</span>
                            @elseif ($statusPelatihan === 'Sedang Berlangsung')
                                <span class="badge bg-success">{{ $statusPelatihan }}</span>
                            @elseif ($statusPelatihan === 'Selesai')
                                <span class="badge bg-success">{{ $statusPelatihan }}</span>
                            @else
                                <span class="badge bg-secondary">Belum Dimulai</span>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Nama Vendor</p></div>
                                <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->getVendor->company_name : '' }}</p></div>
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Tanggal Pelatihan</p></div>
                                <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->tanggal_mulai : '-'}}</p></div>
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Tipe Pelatihan</p></div>
                                <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->kategori : '-'}}</p></div>
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Judul Pelatihan</p></div>
                                <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->judul_pelatihan : '-'}}</p></div>
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Total Karyawan</p></div>
                                <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->employe_total ? $get_perusahaan->employe_total  : 0}}</p></div>
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                </div><br>

                <h5 class="m-0">Daftar Karyawan</h5><br>
                <table class="table table-striped">
                    <thead>
                        <th>No</th>
                        <th>Nama</th>
                        <th></th>
                    </thead>
                    @foreach ($get_test_user as $datas)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{ $datas->user ? $datas->user->employe_name : '-' }} / {{ $datas->user ? $datas->user->nik : '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm delete-btn" data-id="{{ $datas->id }}" style="border-radius: 50px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </form>
        </div>

        <div class="modal fade custom-modal" id="formRequestModal" tabindex="-1" aria-labelledby="formRequestModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header between">
                        <strong class="" id="detailMediaModalLabel" style="font-size: 1.2rem;color: #2778c4">Pengaturan</strong>
                        <button type="button" class="btn btn-sm p-0" data-bs-dismiss="modal" aria-label="Close"
                            id="btn-close-request-media-modal">
                            <img src="{{ asset('assets/close.png') }}" width="70%" />
                        </button>
                    </div>
                    <form id="request-admin-form">

                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Nama Vendor</p></div>
                                            <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->getVendor->company_name : '' }}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Tanggal Pelatihan</p></div>
                                            <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->tanggal_mulai : '-'}}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Tipe Pelatihan</p></div>
                                            <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->kategori : '-'}}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6"><p style="font-size:16px;font-weight:400;">Judul Pelatihan</p></div>
                                            <div class="col-md-6 text-start"><p style="font-size:16px;font-weight:500;">{{$get_perusahaan->getPelatihanDanPengembangan ? $get_perusahaan->getPelatihanDanPengembangan->judul_pelatihan : '-'}}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 15px;">
                                <label for="customer_id" class="form-label">Pilih Karyawan</label>
                                <div id="employee-list">
                                    <!-- Karyawan input pertama -->
                                    <div class="employee-input mb-2 d-flex align-items-center">
                                        <select class="form-select select-employee" name="user_id[]" id="customer_id_0">
                                            <option value="">List Karyawan</option>
                                            @foreach ($get_employe as $emp)
                                                @if (!empty($emp->employe_name))
                                                    <option value="{{ $emp->id }}">{{ $emp->employe_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-employee d-none">Hapus</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mt-2" id="add-employee">Tambah Karyawan</button>
                                <span class="text-danger error-message" id="customer_id-error"></span>
                            </div>
                            <input type="hidden" name="seting_id" id="seting_id" value="{{$get_perusahaan->id}}">
                            <input type="hidden" name="type" id="type" value="{{$get_perusahaan->type}}">
                            <input type="hidden" name="judul_pelatihan_id" id="judul_pelatihan_id" value="{{$get_perusahaan->judul_pelatihan_id}}">
                            <input type="hidden" name="nilai" id="nilai" value="0">
                            <input type="hidden" name="created_by" id="created_by" value="{{Auth::user()->id}}">
                            <input type="hidden" id="max_add_employe" value="{{ $get_perusahaan->employe_total ? $get_perusahaan->employe_total : 0 }}">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-main btn-sm" id="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </article>


@endsection

@section('js')
<script>

    $(document).ready(function() {

        function initializeSelect2(selector) {
            $(selector).select2({
                dropdownParent: $('#formRequestModal'),
                placeholder: 'Pilih Karyawan'
            });
        }

        const maxEmployees = parseInt($('#max_add_employe').val());
        initializeSelect2('#customer_id_0');

        $('#add-employee').on('click', function() {
            const employeeCount = $('.select-employee').length;
            if (employeeCount >= maxEmployees) {
                blockUI(`Batas maksimal karyawan adalah ${maxEmployees}`, _.ERROR);
                return;
            }

            const newEmployeeInput = $(`
                <div class="employee-input mb-2 d-flex align-items-center">
                    <select class="form-select select-employee" name="user_id[]" id="customer_id_${employeeCount}">
                        <option value="">List Karyawan</option>
                        @foreach ($get_employe as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->employe_name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger btn-sm ms-2 remove-employee">Hapus</button>
                </div>
            `);

            $('#employee-list').append(newEmployeeInput);
            initializeSelect2(`#customer_id_${employeeCount}`);
        });


        $(document).on('click', '.remove-employee', function() {
            $(this).closest('.employee-input').remove();
        });


        $('#formRequestModal').on('shown.bs.modal', function() {
            $('.select-employee').each(function() {
                if (!$(this).data('select2')) {
                    initializeSelect2($(this));
                }
            });
        });

        $('.delete-btn').click(function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Apakah yakin ?',
                text: "Akan menghapus data user pada pelatihan ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("test_setting.delete", ":id") }}'.replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                $('button[data-id="' + id + '"]').closest('tr').remove();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'Failed to delete the data. Please try again.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });

    $('#request-admin-form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('token', getCurrentToken()['token']);

        $.ajax({
            url: '{{ route("test_setting.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    blockUI('Data berhasil disimpan');
                    setTimeout(function() {
                        $('#formRequestModal').modal('hide');
                        location.reload();
                    }, 3000);
                } else {
                    alert('Gagal menyimpan data');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            }
        });
    });


    $(document).on('click', '#btn-add-data', function() {
        $('#user_id').val('')
        $('#email').val('')
        var requestModal = new bootstrap.Modal($('#formRequestModal'));
        requestModal.show();
    });
</script>
@endsection
