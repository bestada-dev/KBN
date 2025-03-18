<?php

namespace App\Jobs;

use App\Models\PelatihanSayaModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdatePelatihanStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Mulai memproses UpdatePelatihanStatusJob');

        // $pelatihanList = PelatihanSayaModel::all();
        // $now = Carbon::now();

        // foreach ($pelatihanList as $pelatihan) {
        //     try {
        //         if (is_null($pelatihan->tanggal_mulai) || is_null($pelatihan->tanggal_akhir)) {
        //             $pelatihan->status_pelatihan = 'belum dimulai';
        //         } elseif ($now->greaterThanOrEqualTo($pelatihan->tanggal_mulai) && $now->lessThanOrEqualTo($pelatihan->tanggal_akhir)) {
        //             $pelatihan->status_pelatihan = 'sedang berlangsung';
        //         } elseif ($now->greaterThan($pelatihan->tanggal_akhir)) {
        //             $pelatihan->status_pelatihan = 'selesai';
        //         }

        //         $pelatihan->save();
        //         Log::info("Status pelatihan ID {$pelatihan->id} diperbarui menjadi {$pelatihan->status_pelatihan}");
        //     } catch (\Exception $e) {
        //         Log::error("Gagal memperbarui pelatihan ID {$pelatihan->id}: " . $e->getMessage());
        //     }
        // }

        Log::info('Selesai memproses UpdatePelatihanStatusJob');
    }
}
