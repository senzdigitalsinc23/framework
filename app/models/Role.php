<?php
namespace App\Models;

use Database\ORM\Model;

class Role extends Model
{
    protected static string $table = 'roles';

    public int $role_id;
    public string $name;

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'id');
    }
}