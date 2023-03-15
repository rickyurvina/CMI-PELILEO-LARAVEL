<?php

namespace App\Models;

use App\Abstracts\Model;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Clase PlanIndicator
 *
 * @property string type
 * @property float base_line
 * @property Collection planIndicatorGoals
 *
 * @package App\Models\Business
 */
class Indicator extends Model
{

    use SoftDeletes;

    const TYPE_ASCENDING = 'ascending';
    const TYPE_GOAL_ONLY = 'goal_only';

    const FREQUENCIES = [
        12 => [
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic',
        ],
        4 => [
            1 => 'TRIM I',
            2 => 'TRIM II',
            3 => 'TRIM III',
            4 => 'TRIM IV',
        ],
        3 => [
            1 => 'CUAT I',
            2 => 'CUAT II',
            3 => 'CUAT III'
        ],
        2 => [
            1 => 'Sem I',
            2 => 'Sem II',
        ],
        1 => [
            1 => 'Año'
        ]
    ];

    const PERIODS = [
        12 => 'MESES',
        4 => 'TRIMESTRES',
        3 => 'CUATRIMESTRES',
        2 => 'SEMESTRES',
        1 => 'AÑOS',
    ];

    protected $table = 'indicators';

    protected $fillable = [
        'name',
        'information',
        'calculation_formula',
        'goal_description',
        'source',
        'type',
        'frequency',
        'measure_unit_id',
        'start_date',
        'end_date',
        'f_start_date',
        'f_end_date',
        'responsible',
        'is_higher_values_best',
    ];

    public $timestamps = true;

    public function indicatorable()
    {
        return $this->morphTo();
    }

    public function objective(): BelongsTo
    {
        return $this->belongsTo(PlanElement::class, 'indicatorable_id')
            ->where('indicators.indicatorable_type', PlanElement::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(IndicatorGoal::class, 'indicator_id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->goals()->delete();
        });
    }

    public function hasProgress(): bool
    {
        $goals = $this->goals->filter(function ($item) {
            return $item->reportDay();
        });

        return $goals->count() > 0;
    }

    public function getLastGoalStatus()
    {
        $goals = $this->goals->filter(function ($item) {
            return $item->reportDay();
        });

        return $goals->last();
    }

    public function measureUnit(): BelongsTo
    {
        return $this->belongsTo(CatalogDetail::class, 'measure_unit_id');
    }

    public function status()
    {
        if ($this->hasProgress()) {
            if ($this->type == Indicator::TYPE_GOAL_ONLY) {
                return $this->getLastGoalStatus()->status();
            } else {
                $threshold = Catalog::name('threshold')->with('details')->first();
                $thresholds = json_decode($threshold->details->first()->value, true);
                $progress = $this->progress();
                foreach ($thresholds as $item) {
                    if ($item['type'] == $this->type && ($progress >= $item['min'] && $progress <= $item['max'])) {
                        return $item['status'];
                    }
                }
            }
        }
        return IndicatorGoal::STATUS_PENDING;
    }

    public function progress(): float
    {
        if ($this->hasProgress() && $this->type == Indicator::TYPE_GOAL_ONLY) {
            return $this->getLastGoalStatus()->status() == IndicatorGoal::STATUS_SUCCESS ? 100 : 0;
        }

        $total_goal_values = 0;
        $total_actual_values = 0;

        foreach ($this->goals as $indicatorGoal) {
            if ($indicatorGoal->reportDay()) {
                $total_goal_values += $indicatorGoal->goal;
                $total_actual_values += $indicatorGoal->actual;
            }
        }

        return $total_goal_values != 0 ? (float)number_format((($total_actual_values * 100) / $total_goal_values), 2) : 0.00;
    }

    public function thresholds(): array
    {

        if ($this->type == Indicator::TYPE_GOAL_ONLY) {
            return [
                [
                    'title' => "Inaceptable",
                    'color' => "#ee1f25",
                    'lowScore' => 0,
                    'highScore' => 49.99
                ],
                [
                    'title' => "Aceptable",
                    'color' => "#0f9747",
                    'lowScore' => 50,
                    'highScore' => 100
                ]
            ];
        }

        $response = [
            [
                'title' => "Inaceptable",
                'color' => "#ee1f25",
                'lowScore' => 0,
                'highScore' => 70
            ],
            [
                'title' => "Alerta",
                'color' => "#fdae19",
                'lowScore' => 71,
                'highScore' => 85
            ],
            [
                'title' => "Aceptable",
                'color' => "#0f9747",
                'lowScore' => 85,
                'highScore' => 100
            ]
        ];

        $threshold = Catalog::name('threshold')->with('details')->first();
        $thresholds = json_decode($threshold->details->first()->value, true);

        foreach ($thresholds as $item) {
            if ($item['type'] == $this->type) {
                switch ($item['status']) {
                    case 'Danger':
                        $response[0]['lowScore'] = $item['min'];
                        $response[0]['highScore'] = $item['max'];
                        break;
                    case 'Warning':
                        $response[1]['lowScore'] = $item['min'];
                        $response[1]['highScore'] = $item['max'];
                        break;
                    case 'Success':
                        $response[2]['lowScore'] = $item['min'];
                        $response[2]['highScore'] = $item['max'];
                }
            }
        }
        return $response;
    }

    public function score()
    {
        return round($this->goals->filter(function ($item) {
            return $item->reportDay();
        })->map(function ($item, $key) {
            return $item->score();
        })->avg(), 2);
    }

    /**
     * Obtiene las fechas de los goals del indicador
     *
     * @param null $frequency
     * @param null $modify
     *
     * @return array
     * @throws Exception
     */

    function numberOfPeriods($frequency = null, $modify = null)
    {
        $begin = new DateTime($this->f_start_date);
        $end = new DateTime($this->f_end_date);
        $end = $end->modify($modify);
        $interval = new DateInterval($frequency);
        $daterange = new DatePeriod($begin, $interval, $end);
        $result = array();
        $i = 0;
        foreach ($daterange as $date) {
            $result[$i] = $date->format("d-m-Y");
            $i++;
        }
        return $result;
    }

    function numberOfPeriodsF($startDate, $endDate, $frequency = null, $modify = null)
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->modify($modify);
        $interval = new DateInterval($frequency);
        $daterange = new DatePeriod($begin, $interval, $end);
        $result = array();
        $i = 0;
        foreach ($daterange as $date) {
            $result[$i] = $date->format("d-m-Y");
            $i++;
        }
        return $result;
    }

    public function calcStartEndDateF($startDate_, $endDate_, $frequency)
    {
        $ts1 = strtotime($startDate_);
        $ts2 = strtotime($endDate_);
        $month1 = intval(date('m', $ts1));
        $month2 = intval(date('m', $ts2));
        $startDate = Carbon::parse($startDate_)->startOfMonth()->toDateString();
        $lastDayofMonth = Carbon::parse($endDate_)->endOfMonth()->toDateString();
        $endDate = $lastDayofMonth;
        //Si es semesral
        if ($frequency == 2) {
            if ($month1 <= 6) {
                $mount = $month1 - 1;
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            } else {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 7 . " month"));
            }
            if ($month2 <= 6) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 6 . " month"));
            } else {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
            }
        }

        if ($frequency == 1) {//si es anual
            $mount = $month1 - 1;
            $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
            $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
        }

        if ($frequency == 4) {//Si es trimtestral
            if ($month1 <= 3) {
                $mount = $month1 - 1;
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            }
            if ($month1 > 3 && $month1 <= 6) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 4 . " month"));
            }
            if ($month1 > 6 && $month1 <= 9) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 7 . " month"));
            }
            if ($month1 > 9 && $month1 <= 12) {
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                $startDate = date("Y-m-d", strtotime($startDate . "+ " . 10 . " month"));
            }

            if ($month2 <= 3) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 3 . " month"));
            }
            if ($month2 > 3 && $month2 <= 6) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 6 . " month"));
            }
            if ($month2 > 6 && $month2 <= 9) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 9 . " month"));
            }
            if ($month2 > 9 && $month2 <= 12) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
            }
        }
        if ($frequency == 3) {//Si es cuatrimetstal
            if ($month1 <= 4) {
                $mount = $month1 - 1;
                $startDate = date("Y-m-d", strtotime($startDate . "- " . $mount . " month"));
            } else {
                if ($month1 > 4 && $month1 <= 8) {
                    $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                    $startDate = date("Y-m-d", strtotime($startDate . "+ " . 5 . " month"));

                } else {
                    if ($month1 > 9) {
                        $startDate = date("Y-m-d", strtotime($startDate . "- " . $month1 . " month"));
                        $startDate = date("Y-m-d", strtotime($startDate . "+ " . 9 . " month"));
                    }
                }
            }
            if ($month2 <= 4) {
                $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                $endDate = date("Y-m-d", strtotime($endDate . "+ " . 4 . " month"));
            } else {
                if ($month2 > 4 && $month2 <= 9) {
                    $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                    $endDate = date("Y-m-d", strtotime($endDate . "+ " . 8 . " month"));
                } else {
                    if ($month2 > 9) {
                        $endDate = date("Y-m-d", strtotime($endDate . "- " . $month2 . " month"));
                        $endDate = date("Y-m-d", strtotime($endDate . "+ " . 12 . " month"));
                    }
                }
            }
        }

        return [
            'f_start_date' => $startDate,
            'f_end_date' => $endDate
        ];
    }


    public function calcNumberOfPeriods($value, $starDate, $endDate)
    {
        if ($value == 1) {//si es anual
            return $this->numberOfPeriodsF($starDate, $endDate, 'P1Y', '+0 year');
        }
        if ($value == 2) {//si es semestral
            return $this->numberOfPeriodsF($starDate, $endDate, 'P6M', '+0 month');
        }
        if ($value == 3) {//si es cuatrimestral
            return $this->numberOfPeriodsF($starDate, $endDate, 'P4M', '+0 month');
        }
        if ($value == 4) {//si es trimestral
            return $this->numberOfPeriodsF($starDate, $endDate, 'P3M', '+0 month');
        }
        if ($value == 12) {
            return $this->numberOfPeriodsF($starDate, $endDate, 'P1M', '+0 month');
        }
    }

    public function calcNumberOfPeriodStart()
    {
        $ts1 = strtotime($this->f_start_date);
        $month1 = intval(date('m', $ts1));

        if ($this->frequency == 2) {
            return $month1 == 1 ? $month1 : 2;
        }
        if ($this->frequency == 4) {
            return $month1 < 10 ? $month1 : 10;
        }
        if ($this->frequency == 3) {
            if ($month1 <= 4) {
                return 1;
            } else {
                if ($month1 >= 5 && $month1 < 8) {
                    return 5;
                } else {
                    return 9;
                }
            }
        }
        if ($this->frequency == 12) {
            return $month1;
        }
    }

    public function calcNumberOfPeriodStartC($startDate, $endDate, $frequency)
    {
        $ts1 = strtotime($startDate);
        $month1 = intval(date('m', $ts1));

        if ($frequency == 2) {
            return $month1 <= 6 ? 1 : 2;
        }
        if ($frequency == 4) {

            if ($month1 <= 4) {
                return 1;
            }
            if ($month1 >= 4 && $month1 < 7) {
                return 4;
            }
            if ($month1 >= 7 && $month1 < 10) {
                return 7;
            } else {
                return 10;
            }
        }
        if ($frequency == 3) {
            if ($month1 <= 4) {
                return 1;
            } else {
                if ($month1 >= 5 || $month1 < 9) {
                    return 5;
                } else {
                    return 9;
                }
            }
        }
        if ($frequency == 12) {
            return $month1;
        }
    }
}