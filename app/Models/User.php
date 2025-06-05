<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject; 

class User extends Authenticatable implements JWTSubject 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'userName',
        'email',
        'password',
        'keyWord',
        'state'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ======= MÉTODOS NUEVOS REQUERIDOS PARA JWT ======= //
    
    /**
     * Get the identifier that will be stored in the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retorna el ID del usuario (generalmente la primary key)
    }

    /**
     * Return additional claims to be stored in the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            // Aquí puedes añadir datos extras al token si lo necesitas
            // Ejemplo:
            // 'role' => $this->roles->first()->name,
            // 'email' => $this->email
        ];
    }
}