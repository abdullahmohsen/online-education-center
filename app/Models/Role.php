<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_staff', 'is_teacher'];
    protected $hidden = ['created_at', 'updated_at', 'is_staff', 'is_teacher'];

    public function userRole()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
