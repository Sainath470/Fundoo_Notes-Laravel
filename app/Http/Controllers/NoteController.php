<?php

namespace App\Http\Controllers;

use App\Models\NoteModel;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function getNotes(){
        $userNotes = NoteModel::all();
        return User::find($userNotes->user_id = auth()->id())->noteModel;
    }

    public function createUserNote(Request $request){
        $userNote = new NoteModel();
        $userNote->title = $request->input('title');
        $userNote->notes = $request->input('notes');
        $userNote->id = auth()->id();
        $userNote->save();
    }
}
