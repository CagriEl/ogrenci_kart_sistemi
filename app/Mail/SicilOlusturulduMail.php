<?php

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Sicil Numarası Oluşturuldu')
                    ->view('emails.sicil_olusturuldu')
                    ->with('student', $this->student);
    }
}
