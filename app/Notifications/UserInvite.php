<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class UserInvite extends Notification
{
    private $hostname;

    public function __construct($hostname)
    {
        $this->hostname = $hostname;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $token = Password::broker()->createToken($notifiable);
        $protocol = ($this->hostname->force_https == 1) ? 'https' : 'http';
        $email = urlencode($notifiable->email);
        $resetUrl = "{$protocol}://{$this->hostname->fqdn}/password/reset/{$token}?email=$email&verify=true";

        $app = config('app.name');

        return (new MailMessage())
            ->subject("{$app} Invitation")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been invited to use {$app}!")
            ->line('To get started you need to set a password.')
            ->action('Set password', $resetUrl);
    }
}