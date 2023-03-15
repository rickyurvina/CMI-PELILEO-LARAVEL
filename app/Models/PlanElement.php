<?php

namespace App\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

class PlanElement extends Model
{
    use Sortable;

    protected $table = 'plan_elements';

    protected $fillable = ['plan_id', 'parent_id', 'description', 'code', 'name', 'type', 'axis_id', 'focus_id'];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function axis(): BelongsTo
    {
        return $this->belongsTo(CatalogDetail::class, 'axis_id');
    }

    public function focus(): BelongsTo
    {
        return $this->belongsTo(CatalogDetail::class, 'focus_id');
    }

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(PlanElement::class, 'links', 'element_id', 'related_id');
    }

    public function linksType(string $type): BelongsToMany
    {
        return $this->belongsToMany(PlanElement::class, 'links', 'element_id', 'related_id')
            ->join('plans', 'plan_elements.plan_id', '=', 'plans.id')
            ->where('plans.type', $type);
    }

    public function indicators(): MorphMany
    {
        return $this->morphMany(Indicator::class, 'indicatorable');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'plan_elements_id');
    }

    public function indicatorsByStatus(): array
    {
        $response = [];
        foreach ($this->indicators as $indicator) {
            $response[$indicator->status()][] = $indicator;
        }

        return $response;
    }

    public function progress(): array
    {
        $progress = [
            'physical' => 0,
            'budget' => 0
        ];

        $physic_advance = 0;
        $executedBudget = 0;
        foreach ($this->projects as $project) {
            $physic_advance += $project->physic_advance;
            $executedBudget += $project->executed_budget;
        }

        $progress['physical'] = count($this->projects) != 0 ? number_format($physic_advance / count($this->projects), 2) : 0;
        $progress['budget'] = count($this->projects) != 0 ? number_format(($executedBudget / count($this->projects)), 2) : 0;

        return $progress;
    }

    public function score()
    {
        return round($this->indicators->filter(function ($item) {
            return $item->hasProgress();
        })->map(function ($item, $key) {
            return $item->score();
        })->avg(), 2);
    }

    public function hasProgress(): bool
    {
        $indicators = $this->indicators->filter(function ($item) {
            return $item->hasProgress();
        });
        return $indicators->count() > 0 ;
    }

}
