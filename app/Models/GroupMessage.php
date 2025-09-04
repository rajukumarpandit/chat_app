<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model {
    use HasFactory;
    protected $fillable = [ 'group_id', 'sender_id', 'message', 'file_path', 'message_type' ];

    public function sender() {
        return $this->belongsTo( User::class, 'sender_id' );
    }

    public function group() {
        return $this->belongsTo( Group::class );
    }
}