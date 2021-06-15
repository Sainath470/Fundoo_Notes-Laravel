<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteModel extends Model
{
    use HasFactory;

    protected $fillable = ['id','title', 'notes'];
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
