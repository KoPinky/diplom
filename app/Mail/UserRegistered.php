<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends EmailLetter
{
    public $subject = 'Регистрация на КОНСТАНТА-С';

    public $btn_url = 'https://academy.smw.tom.ru/six/#/login';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        parent::__construct();
        $this->welcome_text = 'Здравствуйте, '.
            $user->surname.' '.$user->first_name.
            ', ваш пароль на сайте: '.$password;

        $this->text =
            'Вы можете изменить пароль в личном кабинете,
            чтобы перейти на сайт, перейдите по ссылке снизу:';

    }

}
