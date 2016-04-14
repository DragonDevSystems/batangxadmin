<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Session;
use Auth;
use Carbon\Carbon;
use Config;
class Online extends Model {

    /*======Documentation to use
    Put before request to insure the update data;
    Online::updateCurrent();
    Getting all the Guest users
    $guests = Online::guests()->get();

    Getting the # of Guest users

    $totalGuests = Online::guests()->count();

    Getting the Registered users

    $registered = Online::registered()->get();

    Getting the # of Registered users

    $totalRegistered = Online::registered()->count();*/


    /**
     * {@inheritDoc}
     */
    public $table = 'sessions';

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * Returns all the guest users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGuests($query)
    {
        return $query->whereNull('user_id')->where('last_activity', '>=', strtotime(Carbon::now()->subMinutes(Config::get('custom.activity_limit'))));
    }

    /**
     * Returns all the registered users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegistered($query)
    {
        return $query->whereNotNull('user_id')->where('last_activity', '>=', strtotime(Carbon::now()->subMinutes(Config::get('custom.activity_limit'))))->with('user');
    }

    /**
     * Updates the session of the current user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdateCurrent($query)
    {
            /*$user = Auth::user();
            return $query->where('id', Session::getId())->update(array(
                'user_id' => !empty($user) ? Auth::user()->id : null
            ));*/
            return $query->where('id', Session::getId())->update([
                'user_id' => ! empty(Auth::user()) ? Auth::id() : null
            ]);
    }


     /**
     * Returns the user that belongs to this entry.
     *
     * @return \Cartalyst\Sentry\Users\EloquentUser
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User'); # Sentry 3
        // return $this->belongsTo('Cartalyst\Sentry\Users\Eloquent\User'); # Sentry 2
    }

}