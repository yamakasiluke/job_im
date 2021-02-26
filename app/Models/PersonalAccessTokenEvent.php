<?php

namespace App\Models;


use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenEvent extends PersonalAccessToken
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personal_access_tokens';

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'token',
        'abilities',
    ];


}
