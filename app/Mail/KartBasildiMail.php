<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Student;

class KartBasildiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

  
    public function __construct(Student $student)
    {
        $this->student = $student;
    }
    
    public function build()
    {
        return $this->subject('Kartınız Basıldı')
                    ->view('emails.kart_basildi')
                    ->with([
                        'adSoyad' => $this->student->ad_soyad,
                        'sicil' => $this->student->sicil,
                    ]);
    }
}
