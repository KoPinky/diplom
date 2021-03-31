<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLetter extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'КОНСТАНТА-С';

    public $title = 'КОНСТАНТА-С';
    public $welcome_text = 'Здравствуйте.';
    public $text = '';
    public $btn_text = 'Перейти на сайт';
    public $btn_url = 'https://academy.smw.tom.ru/six/#';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($text = null, $title = null)
    {
        if (isset($text)) $this->text = $text;
        if (isset($title)) $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('layouts.mail_letter');
    }
}
