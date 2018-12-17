<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimeYear extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year',
        'day',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the decrepted Christmas day.
     *
     * @param  string  $day
     * @return string
     */
    public function getDayAttribute($day)
    {
        return decrypt($day);
    }

    /**
     * Set the encrypted Christmas day.
     *
     * @param  string  $day
     * @return void
     */
    public function setDayAttribute($day)
    {
        $this->attributes['day'] = encrypt($day);
    }
}
