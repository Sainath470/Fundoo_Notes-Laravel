<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotesModel extends Model
{
    use HasFactory;
    protected $table="notes";
    protected $fillable = ['title','description'];
   

    public function user(){
        return $this->belongsTo(User::class);
    }

   public function labels(){
       return $this->hasMany('App\Models\labelsNotes', 'note_id');
   }
}
