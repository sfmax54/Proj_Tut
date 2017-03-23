<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $incrementing = false;

    protected $fillable = [
        'name', 'email', 'password', 'firstName', 'birthdate'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Commentaires sur des Evenements
     */
	public function comments() {
		return $this->hasMany('\app\Comment', 'idUser');
	}
    
    /**
     * Evenements ou l'utilisateur est organisateur
     */
	public function eventsOrganization() {
		return $this->belongsToMany('\app\Event', 'organizer', 'idEvent', 'idEvent');
	}
    
    /**
     * Evenements ou l'utilisateur est invite
     */
	public function eventsInvitations() {
		return $this->belongsToMany('\app\Event', 'invitation', 'idUser', 'idActivity');
	}
    
    /**
     * Evenements auxuqles l'utilisateur participe
     */
	public function eventsParticipations() {
		return $this->belongsToMany('\app\User', 'participation', 'idUser', 'idActivity');
	}
}
