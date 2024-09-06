<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EksikBelgeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $aciklama;

    public function __construct(Student $student, $aciklama)
    {
        $this->student = $student;
        $this->aciklama = $aciklama;
    }

    public function build()
    {
        return $this->subject('Eksik Belge Bildirimi')
                    ->view('emails.eksik_belge');
    }
}
