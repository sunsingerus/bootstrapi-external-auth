<?php

namespace BAEAuth\Model;

use Illuminate\Database\Eloquent\Model;
//use App\Model\User;

/**
 * Class External User
 *
 * @property integer        $id
 * @property string         $access_token
 * @property integer        $user_id
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Model
 */

class UserExternal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_external';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source',
        'source_user_id',
        'user_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
//    public function user()
//    {
//        return $this->hasOne(User::class);
//    }
}
