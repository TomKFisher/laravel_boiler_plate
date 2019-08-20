<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as HasAudits;

class Role extends BaseRole implements Auditable
{
    use UsesTenantConnection, SoftDeletes, HasAudits;
}
