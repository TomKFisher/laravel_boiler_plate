<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class DeleteUser
{
    use Dispatchable, SerializesModels;

    public $user;
    public $new_user;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $new_user)
    {
        $this->user = $user;
        $this->new_user = $new_user;
    }
}
