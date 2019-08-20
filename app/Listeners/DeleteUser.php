<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\DeleteUser as Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteUser implements ShouldQueue
{
    use InteractsWithQueue;
    
    private $to_ignore = ['roles', 'permissions'];
    
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
     * @param  \App\Events\DeleteUser  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if(!empty($event->user->queued_for_deletion)){
            $user = User::class;
            $reflector = new \ReflectionClass($user);
            $relations = [];
            foreach ($reflector->getMethods() as $reflectionMethod) {
                if(!in_array($reflectionMethod->getName(), $this->to_ignore)){
                    $returnType = $reflectionMethod->getReturnType();
                    if ($returnType) {
                        if (in_array(class_basename($returnType->getName()), ['HasOne', 'HasMany', 'BelongsTo', 'BelongsToMany', 'MorphToMany', 'MorphTo'])) {
                            $relations[] = $reflectionMethod;
                        }
                    }
                }
            }
            
            $event->user->forceDelete();
        }
    }

    // /**
    //  * Handle a job failure.
    //  *
    //  * @param  \App\Events\DeleteUser  $event
    //  * @param  \Exception  $exception
    //  * @return void
    //  */
    // public function failed(Event $event, $exception)
    // {
    //     dd($exception);
    // }
}
