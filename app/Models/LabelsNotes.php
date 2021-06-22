<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelsNotes extends Model
{
    protected $table = "labels_notes";
    
    protected $fillable = [
         'note_id', 'label_id'
    ];

    // protected $with = ['label_name'];

    public function labelName(){
        return $this->belongsTo(Labels::class);
    }
}
