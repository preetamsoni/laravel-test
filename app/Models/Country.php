<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Authenticatable {

    use Notifiable,
        HasRoles,
        HasApiTokens,
        SoftDeletes  
            ;
    
    protected $table = 'country'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
      ];

    
    //  public function company() {
    //     return $this->hasMany('App\Models\Company', 'country_id', 'id');
    // }

    // public function users() {
    //     return $this->belongsToMany('App\Models\User', 'company');
    // }

  
    
  }
