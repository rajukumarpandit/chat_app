<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->withPivot('role')
            ->withTimestamps();
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_users');
    }
    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }
}