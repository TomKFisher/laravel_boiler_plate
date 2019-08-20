<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as HasAudits;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasRoles, Notifiable, UsesTenantConnection, SoftDeletes, HasAudits;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'queued_for_deletion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'queued_for_deletion' => 'datetime'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    protected $auditExclude = [
        'queued_for_deletion'
    ];
    
     /**
     * Method that tags the audit trail on a softdelete or a force delete
     * 
     * @return array
     */
    public function generateTags(): array
    {
        if($this->auditEvent == 'deleted')
            return $this->forceDeleting ? ['deleted'] : ['archived'];
        
        return [];
    }

    /**
     * Override the send password reset notification with custom one.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
