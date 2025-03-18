<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

use App\Mail\VerifyMail;
use App\Mail\OrderMail;
use App\Mail\CheckoutMail;
use App\Mail\ResetMail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function handle()
    {
        $data  = $this->details;
        $email = null;

        switch($data['type']){

            case 'verify':
                $email = new VerifyMail($data);
                break;

            case 'order':
                $email = new OrderMail($data);
                break;

            case 'checkout':
                $email = new CheckoutMail($data);
                break;

            case 'reset':
                $email = new ResetMail($data);
                break;

            default:
                return null;

        }

        Mail::to($data['email'])->send($email);
    }
}
