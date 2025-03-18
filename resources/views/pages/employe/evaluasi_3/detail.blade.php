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
                <a href="{{ url('employe/evaluasi_level_3_employe') }}">
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
                @foreach ($getData->getEvaluasiPenilaian as $index => $itemPenilaian)
                    <div class="card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary rounded-circle me-2">{{ $index + 1 }}</div>
                            <h5 class="m-0">{{ $itemPenilaian->pertanyaan }}</h5>
                        </div>
                        <p style="font-size: 15px;font-weight:500">{{$itemPenilaian->kategori ? $itemPenilaian->kategori : '-'}} / {{$itemPenilaian->nilai ? $itemPenilaian->nilai : '-'}}</p>
                        <p style="font-size: 15px;font-weight:500">Catatan</p>
                        <p style="font-size: 15px;font-weight:500">{{$itemPenilaian->catatan ? $itemPenilaian->catatan : '-'}}</p>
                    </div>
                    <br>
                @endforeach

                <label for="">Catatan (Opsional)</label>
                <div class="form-group">
                    <div class="card p-4">
                        <textarea name="catatan_evaluasi" class="form-control" id="" cols="30" rows="10" disabled>{{ $getData->catatan }}</textarea>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <a href="{{url('employe/evaluasi_level_3_employe')}}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </article>


@endsection

@section('js')

@endsection
