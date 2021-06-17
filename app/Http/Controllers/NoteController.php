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

        return response()->json(['status' => 200, 'message' => 'Note created']);
    }

    /**
     * function to get all the notes of the user
     * 
     * @return notes tables data with respective to the authorization
     */
    public function getNotes()
    {
        $notes = NotesModel::all();
        return User::find($notes->user_id = auth()->id())->NotesModel;
    }

    /**
     * function used to update the particular note based on authorization and note id
     * @param $id input from user
     * 
     * @return note updated message or error based on request
     */
    public function updateNote(Request $request)
    {
        $id = $request->input('id');
        $note = NotesModel::findOrFail($id);

        if ($note->user_id == auth()->id()) {
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->save();
            return response()->json(['status' => 200, "message" => "Noted Updated!"]);
        } else {
            return response()->json(['status' => 201, "message" => "Notes are not available with that id"]);
        }
    }

    /**
     * function used to delete particular note based on authorization and request
     * 
     * @return note deleted message or exception based on request
     */
    public function deleteNote(Request $request)
    {
        $id = $request->input('id');

        $note = NotesModel::findOrFail($id);

        if ($note->user_id == auth()->id()) {
            if ($note->delete()) {
                return response()->json(['status' => 201, 'messaged' => 'Deleted!']);
            }
        } else {
            return response()->json(['status' => 422, 'message' => "Invalid note id"]);
        }
    }
}
