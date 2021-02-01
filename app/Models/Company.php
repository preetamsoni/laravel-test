<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Authenticatable {

    use Notifiable,
        HasRoles,
        HasApiTokens,
        SoftDeletes  
            ;
    protected $table = 'company'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country_id',
        'user_id'
      ];

    
    //  public function user() {
    //     return $this->hasMany('App\Models\User', 'company_id', 'id');
    // }

     // public function country() {
     //    return $this->belongsTo('App\Models\Country','country_id','id');
     // }

     // public function users() {
     //    return $this->belongsTo('App\Models\User','user_id','id');
     // }
  
    
  }
