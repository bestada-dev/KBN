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
            <div class="HEADER d-flex  align-items-center">
                <a href="{{ url('company/training') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Pelatihan Publik</h5> <!-- Menghapus margin untuk menyelaraskan dengan tombol -->
                {{-- <a href="{{url('company/training/update/'.$edit_data->id)}}" id="btn_sunting" class="btn btn-primary ms-auto">Sunting</a> <!-- Tombol Sunting --> --}}
            </div>
            <form id="form_detail_pelatihan" class="p-4 pt-3">
                <div class="row">
                    <div class="col-md-4 p-10">
                        <div class="card">
                            <img src="https://via.placeholder.com/201x240" class="card-img-top" alt="Deskripsi Gambar" style="width: 201px; height: 240px;">
                        </div>
                    </div>
                    <div class="col-md-8 p-10">
                        <table>
                            <tr>
                                <td><p style="font-size: 16px;font-weight:400">Judul Training</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data->getPelatihanDanPengembangan ? $edit_data->getPelatihanDanPengembangan->judul_pelatihan : '' }}</p></td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Tipe Latihan</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data->getPelatihanDanPengembangan ? $edit_data->getPelatihanDanPengembangan->type : '' }}</p></td>
                            </tr>
                            <tr>
                                <td><p style="font-size: 16px;font-weight:400">Tanggal Pelaksanaan</p></td>
                                <td>
                                    <p style="font-size: 16px;font-weight:700">
                                        {{ $edit_data->getPelatihanDanPengembangan ?
                                            ($edit_data->getPelatihanDanPengembangan->tanggal_mulai ? \Carbon\Carbon::parse($edit_data->getPelatihanDanPengembangan->tanggal_mulai)->format('d/m/Y') : '') .
                                            ' - ' .
                                            ($edit_data->getPelatihanDanPengembangan->tanggal_akhir ? \Carbon\Carbon::parse($edit_data->getPelatihanDanPengembangan->tanggal_akhir)->format('d/m/Y') : '')
                                            : '' }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Hari</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data->getPelatihanDanPengembangan ? $edit_data->getPelatihanDanPengembangan->hari : '-' }} Hari</p></td>
                            </tr>
                            <tr>
                                <td><p style="font-size: 16px;font-weight:400">Jam Pelaksanaan</p></td>
                                <td>
                                    <p style="font-size: 16px;font-weight:700">
                                        {{ $edit_data->getPelatihanDanPengembangan ?
                                            ($edit_data->getPelatihanDanPengembangan->jam_mulai ? \Carbon\Carbon::parse($edit_data->getPelatihanDanPengembangan->jam_mulai)->format('H:i') : '') .
                                            ' - ' .
                                            ($edit_data->getPelatihanDanPengembangan->jam_akhir ? \Carbon\Carbon::parse($edit_data->getPelatihanDanPengembangan->jam_akhir)->format('H:i') : '')
                                            : '' }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Url</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data->getPelatihanDanPengembangan ? $edit_data->getPelatihanDanPengembangan->link : '' }}</p></td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Deskripsi</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data->getPelatihanDanPengembangan ? $edit_data->getPelatihanDanPengembangan->deskripsi : '' }}</p></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </article>


@endsection

@section('js')

@endsection
