@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Rvaluasi 3')

@section('breadcumbSubtitle', 'Rvaluasi 3 Create')

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
            <div class="HEADER d-flex ">
                <a href="{{ url('company/evaluasi_level_3') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Ulasan Penilaian</h5>
            </div>
            <form id="form_penilaian_evaluasi" class="p-4 pt-3">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Program/Materi</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getEvaluasi3 ? $getData->getEvaluasi3->pelatihan->judul_pelatihan : ''}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Tanggal Pelaksanaan</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getEvaluasi3 ? $getData->getEvaluasi3->tanggal_pelaksanaan : '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Tanggal Evaluasi</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->tanggal_evaluasi ? $getData->tanggal_evaluasi  : '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Peserta/NIK</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->user ? $getData->user->employe_name : '-'}} / {{$getData->user ? $getData->user->nik : '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Penilai</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{Auth::user()->company_name}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
                <h5 class="m-0">Evaluasi Penguasaan Materi Terhadap Perilaku Peserta (Pengetahuan, Keterampilan, dan Sikap Kerja)</h5><br>
                <p style="color: #FF3333">Skala Nilai:  Kurang (< 60), Cukup (60 - 69), Baik (70 - 89), Baik Sekali (90 - 100).</p> <br>

                <style>
                    .rating-buttons .btn {
                        border-radius: 8px;
                        width: 19%; /* Increased width */
                        margin-right: 4px; /* Reduced space between buttons */
                    }
                    .btn-check:checked + .btn {
                        background-color: #0d6efd;
                        color: white;
                    }
                </style>

                <input type="hidden" name="company_evaluasi_id" id="company_evaluasi_id" value="{{ $getData->id }}">
                @foreach ($getData->getEvaluasi3->evaluasiDetail as $index => $itemPenilaian)
                    <div class="card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary rounded-circle me-2">{{ $index + 1 }}</div>
                            <h5 class="m-0">{{ $itemPenilaian->pertanyaan }}</h5>
                            <input type="hidden" name="pertanyaan[]" id="pertanyaan" value="{{ $itemPenilaian->pertanyaan }}">
                        </div>

                        <!-- Rating Buttons -->
                        <div class="rating-buttons d-flex mb-3 flex-wrap">
                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori1_{{ $index }}" value="Sangat Baik" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori1_{{ $index }}">Sangat Baik</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori2_{{ $index }}" value="Baik" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori2_{{ $index }}">Baik</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori3_{{ $index }}" value="Cukup" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori3_{{ $index }}">Cukup</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori4_{{ $index }}" value="Kurang" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori4_{{ $index }}">Kurang</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori5_{{ $index }}" value="Sangat Kurang" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori5_{{ $index }}">Sangat Kurang</label>
                        </div>

                        <!-- Nilai (Wajib) -->
                        <div class="mb-3">
                            <label for="nilai_{{ $index }}" class="form-label">Nilai (Wajib)</label>
                            <input type="text" class="form-control nilai-input" id="nilai_{{ $index }}" name="nilai[{{ $index }}]" data-index="{{ $index }}" required>
                        </div>

                        <!-- Catatan (Opsional) -->
                        <div class="mb-3">
                            <label for="catatan_{{ $index }}" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan_{{ $index }}" name="catatan[{{ $index }}]" rows="3"></textarea>
                        </div>
                    </div>
                    <br>
                @endforeach

                <label for="">Catatan (Opsional)</label>
                <div class="form-group">
                    <div class="card p-4">
                        <textarea name="catatan_evaluasi" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <a href="{{url('/company/evaluasi_level_3')}}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </article>


@endsection

@section('js')
{{-- untuk menyimpan data  --}}
<script>
    document.getElementById("form_penilaian_evaluasi").addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent form submission

        // Validasi JavaScript untuk memastikan semua input wajib diisi
        let valid = true;
        document.querySelectorAll("input[required]").forEach(input => {
            if (input.value.trim() === "") {
                valid = false;
                input.classList.add("is-invalid");
            } else {
                input.classList.remove("is-invalid");
            }
        });

        if (!valid) {
            blockUI("Mohon isi semua input wajib.", _.ERROR);
            return;
        }

        // Persiapkan data dari form
        const formData = new FormData(this);
        formData.append('token', getCurrentToken()['token']);

        // Kirim data melalui AJAX
        fetch("/api/company/evaluasi_level_3/store-penilaian", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                blockUI("Data evaluasi berhasil disimpan.");
                setTimeout(() => {
                    window.location.href = '{{ url('/company/evaluasi_level_3') }}';
                }, 1500);
                // location.reload(); // Refresh halaman
            } else {
                blockUI("Gagal menyimpan data evaluasi.", _.ERROR);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            blockUI("Terjadi kesalahan saat menyimpan data.", _.ERROR);
        });
    });

</script>

<script>
    document.querySelectorAll('.nilai-input').forEach(input => {
        input.addEventListener('input', function () {
            const nilai = parseInt(this.value);
            const index = this.dataset.index;

            if (nilai >= 90 && nilai <= 100) {
                document.getElementById(`kategori1_${index}`).checked = true; // Sangat Baik
            } else if (nilai >= 70 && nilai < 90) {
                document.getElementById(`kategori2_${index}`).checked = true; // Baik
            } else if (nilai >= 60 && nilai < 70) {
                document.getElementById(`kategori3_${index}`).checked = true; // Cukup
            } else if (nilai >= 50 && nilai < 60) {
                document.getElementById(`kategori4_${index}`).checked = true; // Kurang
            } else if (nilai < 50 && nilai >= 0) {
                document.getElementById(`kategori5_${index}`).checked = true; // Sangat Kurang
            } else {
                // Jika nilai tidak valid atau di luar rentang, semua radio button direset
                document.getElementById(`kategori1_${index}`).checked = false;
                document.getElementById(`kategori2_${index}`).checked = false;
                document.getElementById(`kategori3_${index}`).checked = false;
                document.getElementById(`kategori4_${index}`).checked = false;
                document.getElementById(`kategori5_${index}`).checked = false;
            }
        });
    });
</script>
@endsection
