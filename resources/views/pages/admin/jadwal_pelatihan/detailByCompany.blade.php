@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Jadwal Pelatihan')

@section('breadcumbSubtitle', 'Jadwal Pelatihan List')

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
                <div class="col-md-4">
                    <div class="d-flex gap-3 itemscenter">
                        <a href="{{ url()->previous() }} ">
                            <img src="{{ asset('assets/back.png') }}"></a>
                        </a>
                        <div class="input-group">
                            <select class="form-control form-control-sm" id="search-pelatihan" onchange="pencarianPelatihan(event)">
                                <option value="">Semua Pelatihan</option>
                                <option value="Pelatihan">Pelatihan</option>
                                <option value="Pengembangan">Pengembangan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-3 itemscenter">
                        <div class="input-group">
                            <select class="form-control form-control-sm" id="search-status" onchange="pencarianStatus(event)">
                                <option value="">Semua Pelatihan</option>
                                <option value="Belum Dimulai">Belum Dimulai</option>
                                <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-3 itemscenter">
                        <div class="input-group">
                            <input  class="form-control pencarian_tanggan" id="datepicker" placeholder="Masukan Tanggal" onchange="pencarianTanggal(event)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="TABLE">
            <div class="row  d-flex between mb-2">
                <div class="col-md-8">
                </div>
            </div>
            <input type="hidden"  id="getId" value="{{ Request::segment(4) }}">
            <table class="table table-hover mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="pt-0">ID</th>
                        <th class="pt-0">Tanggal Pelatihan</th>
                        <th class="pt-0">Hari</th>
                        <th class="pt-0">Jam Pelaksanaan</th>
                        <th class="pt-0">Tipe Pelatihan</th>
                        <th class="pt-0">Judul Pelatihan</th>
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

    <div class="modal fade custom-modal" id="jadwalPelatihanModal" tabindex="-1" aria-labelledby="jadwalPelatihanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jadwalPelatihanModalLabel">Jadwal Pelatihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <style>
                        .tables {
                            border-collapse: collapse;
                            width: 100%;
                        }

                        .tables tr {
                            height: 20px; /* Atur tinggi baris tabel */
                        }

                        .tables td {
                            border: none; /* Hilangkan garis di sekitar sel */
                            padding: 3px 8px; /* Tambahkan padding agar konten tidak terlalu rapat */
                        }
                    </style>
                    <table class="tables">

                        <tr>
                            <td style="width: 18%"><p style="font-size:14px; font-weight:400">ID</p></td>
                            <td></td>
                            <td><span id="modal-id" style="font-size:14px; font-weight:800"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 18%"><p style="font-size:14px; font-weight:400">Tipe Pelatihan</p></td>
                            <td></td>
                            <td><span id="modal-tipe-pelatihan" style="font-size:14px; font-weight:800"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 18%"><p style="font-size:14px; font-weight:400">Judul Pelatihan</p></td>
                            <td></td>
                            <td><span id="modal-judul-pelatihan" style="font-size:14px; font-weight:800"></span></td>
                        </tr>
                        <tr>
                            <td><p style="font-size:14px; font-weight:400">Total Peserta</p></td>
                            <td></td>
                            <td><span id="modal-total-peserta" style="font-size:14px; font-weight:800"></span></td>
                        </tr>
                    </table>

                    <!-- Input untuk tanggal dan jam -->
                    <input type="hidden" name="id_data" id="id_data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai">Dari Tanggal</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control">
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_akhir">Sampai Tanggal</label>
                                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control">
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_mulai">Dari Jam</label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control">
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_akhir">Sampai Jam</label>
                                <input type="time" id="jam_akhir" name="jam_akhir" class="form-control">
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveButton" disabled>Simpan</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

    <script>
        $(document).ready(function() {
            // Fungsi untuk mengecek apakah semua input terisi
            function checkInput() {
                const tanggalMulai = $('#tanggal_mulai').val();
                const tanggalAkhir = $('#tanggal_akhir').val();
                const jamMulai = $('#jam_mulai').val();
                const jamAkhir = $('#jam_akhir').val();

                // Jika semua input terisi, tombol "Simpan" diaktifkan
                if (tanggalMulai && tanggalAkhir && jamMulai && jamAkhir) {
                    $('#saveButton').prop('disabled', false);
                } else {
                    $('#saveButton').prop('disabled', true);
                }
            }

            // Event listener untuk setiap perubahan di input
            $('#tanggal_mulai, #tanggal_akhir, #jam_mulai, #jam_akhir').on('input change', function() {
                checkInput();
            });

            // Reset tombol dan input saat modal ditutup
            $('#timeModal').on('hidden.bs.modal', function () {
                $('#saveButton').prop('disabled', true);
                $('#tanggal_mulai, #tanggal_akhir, #jam_mulai, #jam_akhir').val('');
            });

            $('#saveButton').on('click', function() {

                const idData = $('#id_data').val();
                const tanggalMulai = $('#tanggal_mulai').val();
                const tanggalAkhir = $('#tanggal_akhir').val();
                const jamMulai = $('#jam_mulai').val();
                const jamAkhir = $('#jam_akhir').val();

                let isValid = true;

                $('.error-message').remove();

                if (!tanggalMulai) {
                    $('#tanggal_mulai').after('<span class="error-message text-danger">Tanggal mulai harus diisi.</span>');
                    isValid = false;
                }
                if (!tanggalAkhir) {
                    $('#tanggal_akhir').after('<span class="error-message text-danger">Tanggal akhir harus diisi.</span>');
                    isValid = false;
                }
                if (!jamMulai) {
                    $('#jam_mulai').after('<span class="error-message text-danger">Jam mulai harus diisi.</span>');
                    isValid = false;
                }
                if (!jamAkhir) {
                    $('#jam_akhir').after('<span class="error-message text-danger">Jam akhir harus diisi.</span>');
                    isValid = false;
                }

                if (isValid) {
                    $.ajax({
                        url: `{{url('/api/superadmin/jadwal_pelatihan/update-jadwal')}}`,
                        type: 'POST',
                        data: {
                            id: idData,
                            tanggal_mulai: tanggalMulai,
                            tanggal_akhir: tanggalAkhir,
                            jam_mulai: jamMulai,
                            jam_akhir: jamAkhir,
                        },
                        success: function(response) {
                            // alert('Data berhasil diperbarui!');
                            // $('#timeModal').modal('hide');
                            blockUI('Data Berhasil Disimpan');
                            // Optionally redirect after saving
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        },
                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan: ' + error);
                        }
                    });
                }
            });

        });
    </script>


    <script>
        var detailUrl = "{{ url('/superadmin/jadwal_pelatihan/detail_by_company/') }}";

        const id = $('#getId').val(); // Mendapatkan ID dari elemen dengan ID 'getId'
        const url = `{{ url('api/superadmin/jadwal_pelatihan/data-table-by-company') }}/${id}`;

        $(document).on('click', '.open-detail-modal', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            const id = $(this).data('id'); // Ambil ID dari atribut data-id
            // console.log(id);

            // AJAX untuk mengambil data berdasarkan ID
            $.ajax({
                url: `{{ url('/superadmin/jadwal_pelatihan/get_detail') }}/${id}`, // Endpoint API sesuai dengan rute Anda
                method: 'GET',
                success: function(response) {
                    // Asumsi response adalah data pelatihan
                    $('#id_data').val(response.id);
                    $('#modal-id').text(response.id_pelatihan); // Contoh mengisi ID
                    $('#modal-nama-vendor').text(response.nama_vendor);
                    $('#modal-tipe-pelatihan').text(response.kategori);
                    $('#modal-judul-pelatihan').text(response.judul_pelatihan);
                    $('#modal-total-peserta').text(response.akses_pelatihan ? response.akses_pelatihan.employe_total : 0);

                    // Tampilkan modal
                    $('#jadwalPelatihanModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", error);
                }
            });
        });

        function pencarianTanggal(e) {
            var value = e.target.value;
            console.log(value);
            DataTables(__getId('search-status').value, __getId('search-pelatihan').value, value);
        }

        function pencarianPelatihan(e) {
            var value = e.target.value;
            console.log(value);
            DataTables(__getId('search-status').value, value);
        }

        function pencarianStatus(e) {
            var value = e.target.value;
            console.log(value);
            DataTables(value, __getId('search-pelatihan').value);
        }


        function DataTables(status_id, pelatihan_id, tanggal) {
            // debugger
            $.fn.dataTable.ext.errMode = 'none';

            var ajaxConfig = {
                ...propertyDB,
                ajax: {
                    url: url,
                    dataType: 'JSON',
                    type: 'POST',
                    data: Object.assign({}, getCurrentToken(), {
                        'status_id': status_id,
                        'pelatihan_id': pelatihan_id,
                        'tanggal': tanggal,
                    }),
                    error: function({
                        responseJSON: {
                            message
                        }
                    }, error, thrown) {
                        blockUI(message, 'error');
                    }
                },
                columns: [
                    {
                        data: 'id_pelatihan',
                        render: function(data, type, row, meta) {
                            if (data) {
                                // Misalkan URL-nya adalah '/pelatihan/' diikuti oleh id_pelatihan
                                return `<a href="#" class="open-detail-modal" data-id="${row.id}">${data}</a>`;
                            } else {
                                return '-'; // Return a dash when `data` is falsy
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data) {
                            const tanggalMulaiRaw = data.tanggal_mulai || ''; // Ambil tanggal mulai atau ''
                            const tanggalSelesaiRaw = data.tanggal_akhir || ''; // Ambil tanggal selesai atau '-'

                            // Helper untuk memformat tanggal
                            function formatTanggal(dateStr) {
                                if (!dateStr) return ''; // Jika tanggal kosong, kembalikan string kosong
                                const date = new Date(dateStr);
                                return date.toLocaleDateString('id-ID'); // Format otomatis d/m/Y (Indonesia)
                            }

                            // Format tanggal mulai dan selesai
                            const tanggalMulai = formatTanggal(tanggalMulaiRaw);
                            const tanggalSelesai = formatTanggal(tanggalSelesaiRaw);

                            // Gabungkan hasil
                            return `${tanggalMulai} - ${tanggalSelesai}`;
                        }
                    },

                    {
                        data: 'hari',
                        orderable: false,
                        render: function(data) {
                            if (data) {
                                // Menggunakan Bootstrap badge
                                return `<span class="badge badge-xs bg-primary">${data}</span>`;
                            } else {
                                return '<span class="badge badge-xs bg-secondary">-</span>'; // Menampilkan badge dengan '-' jika tidak ada data
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data) {
                            const jamMulaiRaw = data.jam_mulai || ''; // Ambil jam mulai atau ''
                            const jamSelesaiRaw = data.jam_akhir || ''; // Ambil jam selesai atau ''

                            // Helper untuk memformat waktu ke jam:menit
                            function formatJamMenit(timeStr) {
                                if (!timeStr) return ''; // Jika waktu kosong, kembalikan string kosong
                                const [hour, minute] = timeStr.split(':'); // Pisahkan jam dan menit
                                return `${hour.padStart(2, '0')}:${minute.padStart(2, '0')}`; // Format ulang
                            }

                            // Format jam mulai dan selesai
                            const jamMulai = formatJamMenit(jamMulaiRaw);
                            const jamSelesai = formatJamMenit(jamSelesaiRaw);

                            // Gabungkan hasil
                            return `${jamMulai} - ${jamSelesai}`;
                        }
                    },

                    {
                        data: 'kategori',
                        orderable: false,
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'judul_pelatihan',
                        orderable: false,
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'status_pelatihan',
                        orderable: false,
                        render: function(data) {
                            if (data === 'Belum Dimulai') {
                                return '<span class="badge bg-secondary">' + (data || '-') + '</span>';
                            } else if (data === 'Sedang Berlangsung') {
                                return '<span class="badge bg-success">' + (data || '-') + '</span>';
                            } else if (data === 'Selesai') {
                                return '<span class="badge bg-success">' + (data || '-') + '</span>';
                            } else {
                                return '<span class="badge bg-light">' + (data || '-') + '</span>';
                            }
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
