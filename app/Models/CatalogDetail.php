<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogDetail extends Model
{
    protected $table = 'catalog_details';

    protected $fillable = ['catalog_id', 'code', 'value', 'enabled'];


    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_id');
    }

    /**
     * Scope to only include one catalog.
     *
     * @param Builder $query
     * @param string $name
     *
     * @return Builder
     */
    public function scopeCatalogByName(Builder $query, string $name): Builder
    {
        return $query->whereHas('catalog', function (Builder $query) use ($name){
            $query->where('name', $name);
        });
    }

}
