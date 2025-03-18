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
            <form id="form_penilaian_evaluasi" class="p-4 pt-3">
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
                    .section-subtitle h5.mb-1:before {
    content: "•"; /* Custom bullet */
    position: absolute;
    left: 0;
    color: black; /* Bullet color */
    font-size: 1.5em; /* Bullet size */
    line-height: 1;
}
.question-group{
    padding-left:1rem
}
.section-subtitle h5.mb-1 {
    font-size: 14px;
    position: relative;
    padding-left: 20px;
    margin-top: 2rem;
}
                </style>
   <div class="container" style="margin-top:-1rem">
   <div class="row">
       <div class="col-md-12">
                @php
        // Initialize variables to track current title and subtitle
        $currentTitle = null;
        $currentSubTitle = null;
    @endphp
                @foreach ($getData->getEvaluasiLv1->getEvaluasiPenilaian as $index => $itemPenilaian)
                @if ($itemPenilaian->title !== '')
            {{-- Check if the title has changed --}}
            @if ($currentTitle !== $itemPenilaian->title)
                @php
                    $currentTitle = $itemPenilaian->title; // Update current title
                    $currentSubTitle = null; // Reset subtitle whenever title changes
                @endphp
                {{-- Display the title --}}
                <div class="section-title mt-4 mb-2" style="{{ $loop->iteration == '20' ? 'display:none;': 'display:block;' }}">
                    <h3 style="font-size:20px"><span class="badge bg-primary ">{{ '•' }} </span> {{ $currentTitle }}</h3>
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
                        <h5 class="mb-1">{{ $currentSubTitle }}</h5>
                    </div>
                @endif
            @endif
        @endif

        {{-- Display the question group --}}
        <div class="question-group mb-2 ml-2" style="
    display: flex;
    flex-direction: column;
    {{ $loop->iteration >= '20' ? 'padding:unset !important;': ''}}
">
            <h5 class="m-0" style="
    font-size: 15px;
    font-weight: normal;
    color: #555;
    margin-bottom: 1rem !important;
    margin-top: 1rem !important;
">
                @if ($itemPenilaian->isQuestionDisplayed)
                    {{ $itemPenilaian->question }}
                @endif
            </h6>

            <p style="font-size: 13px;font-weight:500;">{{$itemPenilaian->kategori ? $itemPenilaian->kategori : '' }} {{$itemPenilaian->kategori ? '/' : ''}} {{$itemPenilaian->nilai ? $itemPenilaian->nilai : ''}}</p>
                        <p style="font-size: 13px;font-weight:500;">Catatan : {{$itemPenilaian->catatan ? $itemPenilaian->catatan : '-'}}</p>

                </div>
            @endforeach
            </div>
            <h5 class="m-0" style="
    font-size: 15px;
    font-weight: normal;
    color: #555;
    margin-bottom: 1rem !important;
    margin-top: 1rem !important;
">Kesimpulan / Remarks / Komentar lain (Opsional)</h5>   
<p style="font-size: 13px;font-weight:500;">
            {{$getData->catatan ? $getData->catatan : '-'}}</h5>  
                <br>
                <br>
                <div class="form-group">
                    <a href="{{url('/employe/evaluasi_level_1')}}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </article>


@endsection

@section('js')

@endsection
