@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Evaluasi 1')

@section('breadcumbSubtitle', 'Evaluasi 1 Create')

@section('content')
<style>
    .p-10 {
        padding: 10px 0px 30px 30px; /* Padding 10px di semua sisi */
    }
    td {
        padding: 5px 9px; /* Menambahkan padding atas-bawah 10px dan kiri-kanan 15px */
    }.section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .question-group {
            margin-bottom: 20px;
            margin-top:1rem;
            margin-left:.6rem;
            padding:1rem;
            border:1px solid #ddd;
            border-radius:15px;

        }
        .question-group label {
            font-weight: bold;
        }
        .form-check {
            display: inline-block;
            margin-right: 15px;
        }
        .form-check-label {
            margin-left: 5px;
        }
        .form-control {
            margin-top: 10px;
        }
        .badge {
            font-size: 1rem;
            margin-right: 10px;
        }
    </style>
    <article>
        <div class="TABLE-WITHOUT-SEARCH-BAR p-0">
            <div class="HEADER d-flex ">
                <a href="{{ url('employe/evaluasi_level_1') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Ulasan Penilaian</h5>
            </div>
            <form id="form_penilaian_evaluasi" class="p-4 pt-3" novalidate>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Vendor</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getPelatihan ? $getData->getPelatihan->getVendor->admin_name : '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Program/Materi</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getPelatihan ? $getData->getPelatihan->judul_pelatihan : ''}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Lokasi</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getPelatihan ? $getData->getPelatihan->alamat_pelatihan : ''}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Tanggal Pelaksanaan</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getPelatihan ? $getData->getPelatihan->tanggal_mulai : '-'}}</p>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Tanggal Evaluasi</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$getData->getEvaluasiLv1 ? $getData->getEvaluasiLv1->tanggal_evaluasi  : '-'}}</p>
                                </div>
                            </div> -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="font-size:16px;font-weight:400">Nama Penilai</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="font-size:16px;font-weight:500">{{$getData->user ? $getData->user->employe_name : '-'}} </p>
                                    </div>
                                </div>
                            <!-- <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Penilai</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{Auth::user()->company_name}}</p>
                                </div>
                            </div> -->
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
                <!-- <h5 class="m-0">Evaluasi Penguasaan Materi Terhadap Perilaku Peserta (Pengetahuan, Keterampilan, dan Sikap Kerja)</h5><br> -->
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
                <input type="hidden" name="employe_test_id" id="employe_test_id" value="{{ $id }}">
                <div class="container" style="margin-top:-2rem">
                @php
        // Initialize variables to track current title and subtitle
        $currentTitle = null;
        $currentSubTitle = null;
    @endphp

    @foreach ($getPertanyaan as $index => $itemPenilaian)
    
        @if ($itemPenilaian->title !== '')
            {{-- Check if the title has changed --}}
            @if ($currentTitle !== $itemPenilaian->title)
                @php
                    $currentTitle = $itemPenilaian->title; // Update current title
                    $currentSubTitle = null; // Reset subtitle whenever title changes
                @endphp
                {{-- Display the title --}}
                <div class="section-title mt-4" style="{{ $loop->iteration == '20' ? 'display:none;': 'display:block;' }}">
                    <h3><span class="badge bg-primary">{{ $loop->iteration }}</span> {{ $currentTitle }}</h3>
                </div>
            @endif

            {{-- Check if the subtitle has changed --}}
            @if ($currentSubTitle !== $itemPenilaian->subtitle)
                @php
                    $currentSubTitle = $itemPenilaian->subtitle; // Update current subtitle
                @endphp
                {{-- Display the subtitle --}}
                @if (!empty($currentSubTitle) && $itemPenilaian->isSubtitleDisplayed)
                    <div class="section-subtitle">
                        <h5 style="font-size:15px" class="mb-1">{{ $currentSubTitle }}</h5>
                    </div>
                @endif
            @endif
        @endif

        {{-- Display the question group --}}
        <div class="question-group mb-4 ml-2">
            <h5 style="font-size:15px" class="m-0">
                @if ($itemPenilaian->isQuestionDisplayed)
                    {{ $itemPenilaian->question }}
                @endif
            </h6>

                <input type="hidden" name="question[]" id="question" value="{{ $itemPenilaian->question }}">

                <input type="hidden" name="title[]" id="" value="{{ $itemPenilaian->title }}">                

                <input type="hidden" name="subtitle[]" id="" value="{{ $itemPenilaian->subtitle }}">      
                
                <input type="hidden" name="isQuestionDisplayed[]" id="" value="{{ $itemPenilaian->isQuestionDisplayed }}">   

                <input type="hidden" name="isSubtitleDisplayed[]" id="" value="{{ $itemPenilaian->isSubtitleDisplayed }}">  

                <!-- Rating Buttons -->
                <div class="rating-buttons d-flex mt-2 mb-3 flex-wrap">
                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori1_{{ $index }}" value="Sangat Baik" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori1_{{ $index }}">Sangat Baik</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori2_{{ $index }}" value="Baik" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori2_{{ $index }}">Baik</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori3_{{ $index }}" value="Cukup" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori3_{{ $index }}">Cukup</label>

                            <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori4_{{ $index }}" value="Kurang" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori4_{{ $index }}">Kurang</label>

                            <!-- <input type="radio" class="btn-check" name="kategori[{{ $index }}]" id="kategori5_{{ $index }}" value="Sangat Kurang" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="kategori5_{{ $index }}">Sangat Kurang</label> -->
                        </div>

                        <!-- Nilai (Wajib) -->
                        <div class="mb-3">
                            <label for="nilai_{{ $index }}" class="form-label">Nilai (Wajib)</label>
                            <input type="number" class="form-control nilai-input" id="nilai_{{ $index }}" name="nilai[{{ $index }}]" data-index="{{ $index }}" required>
                        </div>

                        <!-- Catatan (Opsional) -->
                        <div class="mb-3">
                            <label for="catatan_{{ $index }}" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan_{{ $index }}" name="catatan[{{ $index }}]" rows="3"></textarea>
                        </div>
                    </div>
        @endforeach
    </div>
            <div class="question-group">
                <h5 style="font-size:15px" class="mb-1">Program ini layak Anda rekomendasikan?</h5>

                <input type="hidden" name="question[]" id="" value="Program ini layak Anda rekomendasikan?">
                
            <div class="rating-buttons d-flex mt-2 mb-3 flex-wrap">
                <input class="btn-check" type="radio" name="kategori[{{ count($getPertanyaan) }}]" id="recommendYes" value="Yes">
                <label class="btn btn-outline-secondary" for="recommendYes">Ya</label>
                <input class="btn-check" type="radio" name="kategori[{{ count($getPertanyaan) }}]" id="recommendNo" value="No">
                <label class="btn btn-outline-secondary" for="recommendNo">Tidak</label>
            </div>
            <label for="remarks" class="form-label">Catatan (Opsional)</label>
            <textarea class="form-control" id="remarks" name="catatan[{{ count($getPertanyaan) }}]" rows="3"></textarea>
        </div>

        <div class="question-group mt-3">
        <h5 style="font-size:15px" class="">Perlukah program pelatihan lanjutan setelah ini?</h5>

        <input type="hidden" name="question[]" id="" value="Perlukah program pelatihan lanjutan setelah ini?">

            <div class="rating-buttons d-flex mt-2 mb-3 flex-wrap">
                <input class="btn-check" type="radio" name="kategori[{{ count($getPertanyaan) + 1 }}]" id="trainingYes" value="Yes">
                <label class="btn btn-outline-secondary" for="trainingYes">Ya</label>
                <input class="btn-check" type="radio" name="kategori[{{ count($getPertanyaan) + 1 }}]" id="trainingNo" value="No">
                <label class="btn btn-outline-secondary" for="trainingNo">Tidak</label>
            </div>
            <label for="remarks" class="form-label">Catatan (Opsional)</label>
            <textarea class="form-control" id="remarks" name="catatan[{{ count($getPertanyaan) + 1 }}]" rows="3"></textarea>
        </div>

        <div class="question-group mt-3">
        <h5 style="font-size:15px" class="mb-1">Kesimpulan / Remarks / Komentar lain (Opsional)</h5>
            <textarea class="form-control" rows="5"name="catatan_evaluasi" placeholder="Kesimpulan / Remarks / Komentar lain (Opsional)"></textarea>
        </div>


        <br>
                <div class="form-group">
                    <a href="{{url('/employe/evaluasi_level_1')}}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
        </div>

 
                <!-- <label for="">Catatan (Opsional)</label>
                <div class="form-group">
                    <div class="card p-4">
                        <textarea name="catatan_evaluasi" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div> -->
                <br>
                <div class="form-group">
                    <a href="{{url('/employe/evaluasi_level_1')}}" class="btn btn-secondary">Kembali</a>
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
                input.focus()
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
        fetch("/api/employe/evaluasi_level_1/store-penilaian", {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    },
    body: formData
})
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const contentType = response.headers.get("content-type");
    if (contentType && contentType.includes("application/json")) {
        return response.json();
    } else {
        throw new Error("Invalid JSON response");
    }
})
.then(data => {
    if (data.success) {
        blockUI("Data evaluasi berhasil disimpan.");
        setTimeout(() => {
            window.location.href = "{{ url('/employe/evaluasi_level_1') }}";
        }, 1500);
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
            // document.getElementById(`kategori5_${index}`).checked = true; // Sangat Kurang
        } else {
            // Reset radio buttons if input is invalid
            document.getElementById(`kategori1_${index}`).checked = false;
            document.getElementById(`kategori2_${index}`).checked = false;
            document.getElementById(`kategori3_${index}`).checked = false;
            document.getElementById(`kategori4_${index}`).checked = false;
            // document.getElementById(`kategori5_${index}`).checked = false;
        }
    });
});

// Add event listeners to radio buttons
document.querySelectorAll('.btn-check').forEach(radio => {
    radio.addEventListener('change', function () {
        const value = this.value; // Get the selected radio button's value
        const index = this.id.split('_')[1]; // Extract the index from the radio button's ID
        const nilaiInput = document.getElementById(`nilai_${index}`);

        if (value === "Sangat Baik") {
            nilaiInput.value = 95; // Example value for "Sangat Baik"
        } else if (value === "Baik") {
            nilaiInput.value = 80; // Example value for "Baik"
        } else if (value === "Cukup") {
            nilaiInput.value = 65; // Example value for "Cukup"
        } else if (value === "Kurang") {
            nilaiInput.value = 55; // Example value for "Kurang"
        } else if (value === "Sangat Kurang") {
            nilaiInput.value = 40; // Example value for "Sangat Kurang"
        }
    });
});

</script>
@endsection