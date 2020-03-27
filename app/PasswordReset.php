<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = ['user_id', 'otp', 'reset_token', 'otp_expire_date', 'token_expire_date'];

    protected $dates = ['otp_expire_date', 'token_expire_date'];
}
