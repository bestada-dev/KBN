<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\PelatihanSayaModel;
use App\CompanyEvaluasiModel;
use Carbon\Carbon;

// ===============Running crobjob update status===============
Route::GET('/update-status-pelatihan-realtime', function(){
    try {
        $pelatihanList = PelatihanSayaModel::all();
        $now = Carbon::now();

        foreach ($pelatihanList as $pelatihan) {
            try {
                if (is_null($pelatihan->tanggal_mulai) || is_null($pelatihan->tanggal_akhir)) {
                    $pelatihan->status_pelatihan = 'Belum Dimulai';
                } elseif ($now->greaterThanOrEqualTo($pelatihan->tanggal_mulai) && $now->lessThanOrEqualTo($pelatihan->tanggal_akhir)) {
                    $pelatihan->status_pelatihan = 'Sedang Berlangsung';
                } elseif ($now->greaterThan($pelatihan->tanggal_akhir)) {
                    $pelatihan->status_pelatihan = 'Selesai';
                }

                $pelatihan->save();
                Log::info("Status pelatihan ID {$pelatihan->id} diperbarui menjadi {$pelatihan->status_pelatihan}");
            } catch (\Exception $e) {
                Log::error("Gagal memperbarui pelatihan ID {$pelatihan->id}: " . $e->getMessage());
            }
        }
    } catch (\Throwable $th) {
        dd($th);
    }
});


// ===============Running crobjob update status Evaluasi Untuk Admin===============
Route::GET('/update-status-evaluasi-admin-realtime', function() {
    try {
        $companyEvaluasi = CompanyEvaluasiModel::with('getEvaluasi3')->get();
        $now = Carbon::now();

        foreach ($companyEvaluasi as $evaluasi) {
            try {
                // Cek tanggal pelaksanaan dari relasi getEvaluasi3
                $tanggalPelaksanaan = $evaluasi->getEvaluasi3->tanggal_pelaksanaan ?? null;

                // Logika tambahan untuk status_admin berdasarkan tanggal_pelaksanaan dari relasi getEvaluasi3
                if ($tanggalPelaksanaan) {
                    if ($now->greaterThan($tanggalPelaksanaan)) {
                        $evaluasi->status_admin = 'Dikirim';
                    } else {
                        $evaluasi->status_admin = 'Ditunda';
                    }
                } else {
                    // Jika tanggal pelaksanaan tidak ada, status_admin disetel default, misalnya 'Tidak Diketahui'
                    $evaluasi->status_admin = 'Tidak Diketahui';
                }

                // Simpan perubahan
                $evaluasi->save();
                Log::info("Status pelatihan ID {$evaluasi->id} diperbarui menjadi {$evaluasi->status_pelatihan}, status admin menjadi {$evaluasi->status_admin}");
            } catch (\Exception $e) {
                Log::error("Gagal memperbarui pelatihan ID {$evaluasi->id}: " . $e->getMessage());
            }
        }
    } catch (\Throwable $th) {
        dd($th);
    }
});
