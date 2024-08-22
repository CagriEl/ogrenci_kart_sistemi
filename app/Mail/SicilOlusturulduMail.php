<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SicilOlusturulduMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function build()
    {
        return $this->view('emails.sicil_olusturuldu')
                    ->with([
                        'adSoyad' => $this->student->ad_soyad,
                        'sicil' => $this->student->sicil,
                    ]);
    }
}
