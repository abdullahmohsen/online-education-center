<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'group_id', 'count', 'price'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'student_id',
        'group_id'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

}
