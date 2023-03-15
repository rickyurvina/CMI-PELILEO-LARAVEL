<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';

    public $timestamps = true;

    const STATE_EXECUTION = 'execution';
    const STATE_CANCELED = 'canceled';
    const STATE_COMPLETED = 'completed';
    const STATE_CLOSED = 'closed';
    const STATE_SUSPENDED = 'suspended';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'responsible_unit',
        'project_leader',
        'benefits',
        'risks',
        'state',
        'physic_advance',
        'referential_budget',
        'executed_budget',
        'location',
        'components',
        'plan_elements_id'
    ];

    public function objective(): BelongsTo
    {
        return $this->belongsTo(PlanElement::class, 'id');
    }

    public function getStateName()
    {
        switch ($this) {
            case $this->state == Project::STATE_EXECUTION:
                return "Ejecucción";
            case $this->state == Project::STATE_CANCELED:
                return "Cancelado";
            case $this->state == Project::STATE_COMPLETED:
                return "Compeltado";
            case $this->state == Project::STATE_CLOSED:
                return "Cerrado";
            case $this->state == Project::STATE_SUSPENDED:
                return "Suspendido";
        }
        return '';
    }
}
