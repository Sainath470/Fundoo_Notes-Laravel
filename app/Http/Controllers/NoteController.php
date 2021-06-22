<?php

namespace App\Http\Controllers;

use App\Models\Labels;
use App\Models\LabelsNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\NotesModel;
use Exception;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    /**
     * function to create new note based on authentication
     * 
     * @return response
     */
    public function createNote(Request $request)
    {
        try {
            $note = new NotesModel;
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->user_id = Auth::user()->id;
            $note->save();
        } catch (Exception $e) {
            return response()->json(['status' => 404, 'message' => 'Invalid authorization token is invalid'], 404);
        }
        return response()->json(['status' => 200, 'message' => 'Note created']);
    }

    /**
     * function to get all the notes of the user
     * 
     * @return notes tables data with respective to the authorization
     */
    public function getNotes()
    {
        $notes = LabelsNotes::all();
        $notes->user_id = auth()->id();

        if ($notes->user_id != auth()->id()) {
            return response()->json(['status' => 201, 'message' => 'No notes and labels are available!'], 201);
        }
        return  DB::table('labels_notes')->select('note_id', 'label_id')->where('user_id', $notes->user_id)->get();
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

        try {
            $note = NotesModel::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['status' => 422, 'message' => "Notes are not available with that id"], 422);
        }

        if ($note->user_id == auth()->id()) {
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->save();
            return response()->json(['status' => 200, "message" => "Note Updated!"]);
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

        try {
            $note = NotesModel::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['status' => 422, 'message' => "Invalid note id"], 422);
        }

        if ($note->user_id == auth()->id()) {
            if ($note->delete()) {
                return response()->json(['status' => 200, 'message' => 'Note Deleted!']);
            }
        }
    }
}
