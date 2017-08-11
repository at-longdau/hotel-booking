<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'full_name', 'password', 'email', 'phone', 'is_admin', 'is_active'
     ];

    /**
     * Value paginate of row
     */
    const ROW_LIMIT = 10;

    /**
     * Get all of the user's ratingcomment.
     *
     * @return array
     */
    public function ratingComments()
    {
        return $this->hasMany('App\Model\RatingComment');
    }

    /**
     * Get all of the user's reservation.
     *
     * @return array
     */
    public function reservations()
    {
        return $this->morphMany('App\Model\Reservation', 'reservable', 'target', 'target_id');
    }

    /**
     * This is a recommended way to declare event handlers
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
             $user->ratingComments()->delete();
             $user->reservations()->delete();
        });

        static::saving(function ($user) {
            if (Hash::needsRehash($user->password)) {
                $user->password = bcrypt($user->password);
            }
        });
    }
}
