<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by'
    ];

    protected $fillable = ['name', 'body', 'image', 'teacher_id', 'created_by'];

    public function studentGroups()
    {
        return $this->hasMany(StudentGroup::class, 'group_id', 'id');
    }

}
