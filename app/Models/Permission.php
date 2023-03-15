<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{

    use Sortable;

    protected $table = 'permissions';
}
