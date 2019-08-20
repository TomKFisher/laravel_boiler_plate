<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use UsesTenantConnection;

    public function getDisplayNameAttribute(){
        return ucwords(str_replace('-', ' ', str_plural($this->name)));
    }
}
