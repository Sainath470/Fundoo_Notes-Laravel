<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\NotesModel;

class NoteController extends Controller
{
 
    /**
     * function to create new note based on authentication
     * 
     * @return response
     */
    public function createNote(Request $request)
    {
        $note = new NotesModel;
        $note->title = $request->input('title');
        $note->description = $request->input('description');
        $note->user_id = Auth::user()->id;
        $note->save();

        return response()->json(['status' => 200, 'id' => $note->user_id , 'message' => 'Note created']);
    }

    /**
     * function to get all the notes of the user
     */
    public function getNotes(){
        $notes = NotesModel::all();
        return User::find($notes->user_id = auth()->id())->NotesModel;
    }


}
