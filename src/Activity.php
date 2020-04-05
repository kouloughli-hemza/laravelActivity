<?php

namespace Kouloughli\UserActivity;

use Kouloughli\User;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    const UPDATED_AT = null;

    const CREATED_AT = 'activ_date';

    protected $table = 'users_activity';
    protected $primaryKey = 'ref_activ';

    protected $fillable = ['activ_desc', 'ref_user', 'ip_address','mac_address', 'user_agent','activ_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'ref_user');
    }
}
