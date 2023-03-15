<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

abstract class Model extends Eloquent
{
    use SoftDeletes, Sortable;

    protected $dates = ['deleted_at'];


    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param Builder $query
     * @param $sort
     *
     * @return Builder
     */
    public function scopeCollect($query, $sort = 'name')
    {
        $request = request();

        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->sortable($sort)->paginate($limit);
    }

    /**
     * Scope to only include active models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    /**
     * Scope to only include passive models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }
}
