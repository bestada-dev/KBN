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
                <a href="{{ url('/vendor/pelatihan_saya') }}">
                    <img src="{{ asset('assets/back.png') }}" alt="Back">
                </a>
                <h5 class="m-0">Detail Pelatihan</h5> <!-- Menghapus margin untuk menyelaraskan dengan tombol -->
                <a href="{{url('/vendor/pelatihan_saya/update/'.$edit_data->id)}}" id="btn_sunting" class="btn btn-primary ms-auto">Sunting</a> <!-- Tombol Sunting -->
            </div>
            <form id="form_detail_pelatihan" class="p-4 pt-3">
                <div class="row">
                    <div class="col-md-4 p-10">
                        <div class="card">
                        <img src="{{ Storage::url($edit_data->foto) }}" class="card-img-top" alt="Deskripsi Gambar" style="">
                        </div>
                    </div>
                    <div class="col-md-8 p-10">
                        <table>
                            <tr>
                                <td><p style="font-size: 16px;font-weight:400">Judul Training</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data ? $edit_data->judul_pelatihan : '' }}</p></td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Tipe Latihan</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data ? $edit_data->type : '' }}</p></td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Tanggal Pelaksanaan</p></td>
                                <td>
                                    <p style="font-size: 16px;font-weight:700">{{ $edit_data ? ($edit_data->tanggal_mulai ? $edit_data->tanggal_mulai : '') . ' - ' . ($edit_data->tanggal_akhir ? $edit_data->tanggal_akhir : '') : '' }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Hari</p></td>
                                <td><p  style="font-size: 16px;font-weight:700"><span class="badge badge-xs badge-primary">{{ $edit_data ? $edit_data->hari : '-' }}</span></p></td>
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Jam Pelaksanaan</p></td>
                                <td>
                                    <p style="font-size: 16px;font-weight:700">{{ $edit_data ? ($edit_data->jam_mulai ? $edit_data->jam_mulai : '') . ' - ' . ($edit_data->jam_akhir ? $edit_data->jam_akhir : '') : '' }}</p>
                                </td>
                            </tr>
                            <tr>
                                @if ($edit_data->type == "Online")
                                    <td ><p style="font-size: 16px;font-weight:400">Url</p></td>
                                    <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data ? $edit_data->link : '' }}</p></td>
                                @else
                                    <td ><p style="font-size: 16px;font-weight:400">Alamat</p></td>
                                    <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data ? $edit_data->alamat_pelatihan : '' }}</p></td>
                                @endif
                            </tr>
                            <tr>
                                <td ><p style="font-size: 16px;font-weight:400">Deskripsi</p></td>
                                <td><p  style="font-size: 16px;font-weight:700">{{ $edit_data ? $edit_data->deskripsi : '' }}</p></td>
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
