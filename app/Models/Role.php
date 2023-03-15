<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Permission\Models\Role as BaseRole;


class Role extends BaseRole
{
    use Sortable;

    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'guard_name'];


    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeCollect(Builder $query)
    {
        $request = request();

        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->sortable()->paginate($limit);
    }

    /**
     * Scope to not super-admin roles
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeNotSuperAdmin(Builder $query): Builder
    {
        return $query->where('name', '<>', 'super-admin');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($model)
        {
            $model->syncPermissions([]);
        });
    }
}
