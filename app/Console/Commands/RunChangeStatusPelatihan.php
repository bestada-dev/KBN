<?php

// app/Console/Commands/RunChangeStatusPelatihan.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\PelatihanSayaModel;
use App\Certificate;
use App\Notification;
use App\EmployeTestModel;

class RunChangeStatusPelatihan extends Command
{
    protected $signature = 'task:run-change-status-pelatihan';
    protected $description = 'Run ....................';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Run the task indefinitely, every 10 seconds
        while (true) {
            if (in_array(Carbon::now()->hour, [0, 1, 16])) {
                $this->info("Running at the specified hour!");
                $this->updatePelatihanStatus();
                $this->manageNotification();
            }
            // Wait for 30 minutes (1800 seconds)
            sleep(1800); // 30 seconds * 60 minutes = 1800
        }
        return Command::SUCCESS;
    }

    private function updatePelatihanStatus()
    {
        try {
            $pelatihanList = PelatihanSayaModel::all();
            $now = Carbon::now();
            // Logic to execute if the current time is 00 AM

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
                    \Log::info("Status pelatihan ID {$pelatihan->id} diperbarui menjadi {$pelatihan->status_pelatihan}");
                } catch (\Exception $e) {
                    \Log::error("Gagal memperbarui pelatihan ID {$pelatihan->id}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $th) {
            \Log::error('Error: ' . $th->getMessage());
        }
    }

    
    public function manageNotification()
    {
        // Fetch upcoming training sessions
        $upcomingPelatihan = PelatihanSayaModel::where('status_pelatihan', 'Belum Dimulai')->where('tanggal_mulai', '>', Carbon::now())
                            ->get(['judul_pelatihan', 'tanggal_mulai']);

        // Fetch certificates
        $certificates = Certificate::get();

       // Prepare notifications
        $notifications = [];

        foreach ($upcomingPelatihan as $pelatihan) {
            $getUserYangPunyaAksesKepelatihanIni = EmployeTestModel::where('judul_pelatihan_id', $pelatihan->id)->get();
            if(count($getUserYangPunyaAksesKepelatihanIni)) {
                foreach($getUserYangPunyaAksesKepelatihanIni as $employee) {
                    $notifications[] = [
                        'user_id' => $employee->user_id,
                        'type' => 'PELATIHAN',
                        'message' => "Pelatihan \"{$pelatihan->judul_pelatihan}\" akan dimulai pada " . 
                                    ($pelatihan->tanggal_mulai ? $pelatihan->tanggal_mulai->format('d M Y') : 'tanggal tidak tersedia'),
                        'url' => null, // Add a URL if needed
                        'is_read' => false, // Default to unread
                    ];
                }
    
            }
        }

        foreach ($certificates as $cert) {
            $pelatihanTitle = $cert->pelatihan->judul_pelatihan ?? 'Pelatihan';
            $notifications[] = [
                'user_id' => $cert->user_id,
                'type' => 'CERTIFICATE',
                'message' => "Sertifikat untuk pelatihan \"{$pelatihanTitle}\" sudah diterbitkan.",
                'url' => null, // Add a URL if needed
                'is_read' => false, // Default to unread
            ];
        }

        // Loop through notifications and use updateOrCreate for each one
        foreach ($notifications as $notification) {
            $existingNotification = Notification::where([
                'user_id' => $notification['user_id'],
                'type' => $notification['type'],
                'message' => $notification['message']
            ])->first();

            // If a notification already exists
            if ($existingNotification) {
                // Check if 'is_read' is false, then update only the URL
                if (!$existingNotification->is_read) {
                    // $existingNotification->update([
                    //     'url' => $notification['url'], // Update URL only
                    // ]);
                }
                // If 'is_read' is true, skip updating (do nothing)
            } else {
                // If no existing notification, create a new one
                Notification::create([
                    'user_id' => $notification['user_id'],
                    'type' => $notification['type'],
                    'message' => $notification['message'],
                    'url' => $notification['url'],
                    'is_read' => $notification['is_read'], // Default to false if not already read
                ]);
            }
        }


        // DELETE OLD DATA
        // Mendapatkan tanggal satu tahun yang lalu
        $oneYearAgo = Carbon::now()->subYear();

        // Mengambil dan menghapus data yang 'created_at' lebih dari satu tahun
        Notification::where('created_at', '<', $oneYearAgo)
            ->delete();

        return response()->json([
            'message' => 'Data notification yang lebih dari satu tahun telah dihapus.'
        ]);
    }



}
