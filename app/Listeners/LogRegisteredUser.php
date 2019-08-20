<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Role;
use Log;

class LogRegisteredUser
{
    private $default_role_name = 'user';
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if(sizeof($event->user->getRoleNames()) > 0){
            Log::info($event->user->name.' already has at least one role');
            return;
        }
        
        $role = Role::where('name', $this->default_role_name)->first();
        if($role == null)
            return;
        
        $event->user->assignRole($role);
        Log::info('Assigned User role to '.$event->user->name);
    }
}