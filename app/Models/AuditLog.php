<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Database\Connection;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Audit as AuditTrait;

class AuditLog extends Model implements Audit
{
    use AuditTrait;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values'   => 'json',
        'new_values'   => 'json',
        'auditable_id' => 'integer',
    ];

    /**
     * {@inheritdoc}
     */
    public function auditable()
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * Have to override the AuditTrait's getConnectionName method to ensure 
     * that the system uses the Hyn MultiTenant method to point at the tenant 
     * database as opposed to the system DB
     *
     * @return void
     */
    public function getConnectionName()
    {
        return app(Connection::class)->tenantName();
    }

    /**
     * Uses the Audit record tag field to detect what type of delete was performed via the tag.
     * Used in conjunction with SoftDeletes trait on Models.
     * The generateTags() method must be implemented to ensure that the audit record is tagged correctly.
     *
     * @return string
     */
    public function getActualEventAttribute(){
        $tags = explode(',', $this->tags);
        switch($this->event){
            case 'deleted':
                if(in_array('archived', $tags))
                    return 'Archived';
            default:
                return $this->event;
        }
    }
}
