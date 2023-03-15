<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements HasMedia
{
    use Notifiable, SoftDeletes, HasRoles, Sortable, InteractsWithMedia;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'locale', 'enabled'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_logged_in_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Always capitalize the name when we retrieve it
     *
     * @param $value
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Always return a valid picture when we retrieve it
     *
     * @param $value
     *
     * @return false|mixed
     */
    public function getPictureAttribute($value)
    {

        if (!empty($value) && !$this->hasMedia('picture')) {
            return $value;
        } elseif (!$this->hasMedia('picture')) {
            return false;
        }

        return $this->getMedia('picture')->last();
    }

    /**
     * Always return a valid picture when we retrieve it
     *
     * @param $value
     *
     * @return array|Application|Translator|string|null
     */
    public function getLastLoggedInAtAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::create($value)->diffForHumans();
        } else {
            return trans('auth.never');
        }
    }

    /**
     * Always capitalize the name when we save it to the database
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    /**
     * Always hash the password when we save it to the database
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Scope to only include active currencies.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($model)
        {
            $model->roles()->sync([]);
        });
    }

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
}
