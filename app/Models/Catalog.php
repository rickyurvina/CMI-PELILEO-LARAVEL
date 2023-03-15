<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

class Catalog extends Model
{
    use Sortable;

    protected $table = 'catalogs';

    protected $fillable = ['name', 'description', 'enabled'];

    public function details(): HasMany
    {
        return $this->hasMany(CatalogDetail::class, 'catalog_id');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }
}
