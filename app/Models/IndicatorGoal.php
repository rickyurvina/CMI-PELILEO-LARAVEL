<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Clase PlanIndicatorGoal
 * @property float goal_value
 * @property float actual_value
 * @property float min
 * @property float max
 * @property Indicator planIndicator
 * @package App\Models\Business
 */
class IndicatorGoal extends Model
{


    protected $table = 'indicator_goals';

    /**
     * @var bool
     */
    public $timestamps = true;

    const STATUS_SUCCESS = 'Success';
    const STATUS_WARNING = 'Warning';
    const STATUS_DANGER = 'Danger';
    const STATUS_PENDING = 'Pending';

    const RED_FLAG = 3.33;
    const GOAL_FLAG = 6.67;
    const BEST_FLAG = 10;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'indicator_id',
        'goal',
        'actual',
        'period',
        'year',
        'start_date',
        'end_date'
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    public function progress(): float
    {
        if ($this->indicator->type == Indicator::TYPE_GOAL_ONLY) {
            if ($this->indicator->is_higher_values_best && $this->actual >= $this->goal) {
                return 100;
            }

            if (!$this->indicator->is_higher_values_best && $this->actual <= $this->goal) {
                return 100;
            }
            return 0;
        }
        return $this->goal != 0 ? (float)number_format((($this->actual * 100) / $this->goal), 2) : 0.00;
    }

    public function status()
    {
        if ($this->reportDay()) {
            if ($this->indicator->type == Indicator::TYPE_GOAL_ONLY) {
                if ($this->indicator->is_higher_values_best && $this->actual >= $this->goal) {
                    return IndicatorGoal::STATUS_SUCCESS;
                }

                if (!$this->indicator->is_higher_values_best && $this->actual <= $this->goal) {
                    return IndicatorGoal::STATUS_SUCCESS;
                }
                return IndicatorGoal::STATUS_DANGER;
            } else {
                $threshold = Catalog::name('threshold')->with('details')->first();
                $thresholds = json_decode($threshold->details->first()->value, true);
                $progress = $this->progress();
                foreach ($thresholds as $item) {
                    if ($item['type'] == $this->indicator->type && ($progress >= $item['min'] && $progress <= $item['max'])) {
                        return $item['status'];
                    }
                }
            }
        }
        return IndicatorGoal::STATUS_PENDING;
    }

    public function reportDay(): bool
    {
        return Carbon::createFromFormat('Y-m-d', $this->end_date)->addDays(5) < now() || $this->actual != 0;
    }

    public function canUpdateActual(): bool
    {
        return Carbon::createFromFormat('Y-m-d', $this->start_date) <= now();
    }

    public function colorChart(): string
    {
        $status = $this->status();
        switch ($status) {
            case 'Danger':
                return '#ee1f25';
            case 'Warning':
                return '#fdae19';
            case 'Success':
                return '#0f9747';
            case 'Pending':
                return '#535353';
        }
        return '#000000';
    }

    public function score()
    {
        if ($this->indicator->type == Indicator::TYPE_GOAL_ONLY) {

            return $this->status() == IndicatorGoal::STATUS_SUCCESS ? 10 : 0;

        } else {
            $thresholds = json_decode(Catalog::name('threshold')->with('details')->first()->details->first()->value, true);
            $goalPercent = 0;

            foreach ($thresholds as $item) {
                if ($item['type'] == Indicator::TYPE_ASCENDING && $item['status'] == IndicatorGoal::STATUS_SUCCESS) {
                    $goalPercent = $item['min'] / 100 * $this->goal;
                    break;
                }
            }

            $ra = $this->goal - $goalPercent;
            $rb = ($this->actual ?? 0) - $goalPercent;
            $r3 = $ra != 0 ? (($rb * self::RED_FLAG) / $ra) : 0;
            $score = self::BEST_FLAG + $r3;

            if ($score < 0) {
                return 0;
            }

            if ($score > 10) {
                return 10;
            }
        }
        return round($score, 2);
    }
}