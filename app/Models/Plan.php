<?php

namespace App\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

class Plan extends Model
{
    use Sortable;

    protected $table = 'plans';

    protected $fillable = ['name', 'vision', 'mission', 'principles', 'values', 'type', 'start_year', 'end_year'];

    public function elements(): HasMany
    {
        return $this->hasMany(PlanElement::class, 'plan_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'principles' => 'array',
        'values' => 'array',
    ];

    /**
     * Scope to only include strategic.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStrategic(Builder $query): Builder
    {
        return $query->where('type', 'PEI');
    }

    /**
     * Scope to only include ods.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOds(Builder $query): Builder
    {
        return $query->where('type', 'ODS');
    }

    /**
     * Scope to only include ods.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePdot(Builder $query): Builder
    {
        return $query->where('type', 'PDOT');
    }

    public function score()
    {
        return round($this->elements->filter(function ($item) {
            return $item->hasProgress();
        })->map(function($item, $key) {
            return $item->score();
        })->avg(), 2);
    }
}
