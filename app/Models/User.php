<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
        'nivel_acesso',
        'ativo',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Role principal do usuário
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Todos os roles do usuário (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Verifica se o usuário tem um role específico
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists() || 
                   ($this->role && $this->role->name === $role);
        }
        
        return $this->roles()->where('role_id', $role->id)->exists() ||
               ($this->role && $this->role->id === $role->id);
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    public function hasPermission($permission)
    {
        // Verifica através do role principal
        if ($this->role) {
            if ($this->role->hasPermission($permission)) {
                return true;
            }
        }

        // Verifica através dos roles adicionais
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->hasRole('administrador');
    }
}
