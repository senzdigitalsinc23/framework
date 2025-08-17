<?php

namespace App\Models;

use Database\ORM\Model;

class User extends Model
{
    protected static string $table = 'users';

    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public ?string $created_at;
    public ?string $updated_at;

    /**
     * Hide password when converting to array.
     */
    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles', 'id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id', 'id');
    }

    /* public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    } */
}
