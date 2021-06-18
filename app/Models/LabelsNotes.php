<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelsNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'note_id', 'label_id'
    ];

    protected $with = ['labelName'];

    public function labelName(){
        return $this->belongsTo('App\Http\Models\Labels' , 'label_id');
    }
}
