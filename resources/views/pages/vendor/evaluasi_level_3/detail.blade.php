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
            <div class="HEADER d-flex ">
                <a href="{{ url('/superadmin/evaluasi_level_3') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Detail</h5> <!-- Menghapus margin untuk menyelaraskan dengan tombol -->
            </div>
            <form id="form_detail_pelatihan" class="p-4 pt-3">
                <h5 class="m-0">Evaluasi</h5>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Nama Program/Materi</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$data->pelatihan ? $data->pelatihan->judul_pelatihan : ''}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:400">Tanggal Pelaksanaan</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-size:16px;font-weight:500">{{$data->tanggal_pelaksanaan ? $data->tanggal_pelaksanaan : '-'}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div><br>
                    <h5 class="m-0">Daftar Pertanyaan</h5><br>
                    <div class="card p-4">
                        @foreach ($data->evaluasiDetail as $index => $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="badge bg-primary rounded-circle me-2">{{$index + 1}}</div>
                                <h5 class="m-0">{{$item->pertanyaan}}</h5>
                            </div><br>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </article>


@endsection

@section('js')

@endsection
