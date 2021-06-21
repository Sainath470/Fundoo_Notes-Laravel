<?php

namespace App\Http\Controllers;

use App\Models\Labels;
use App\Models\LabelsNotes;
use App\Models\NotesModel;
use App\Models\User;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class LabelController extends Controller
{
    /**
     * Function to make a new label for the user in the data base
     * 
     * @param Request
     * @return success message or error message
     */
    public function createLabel(Request $request)
    {
        $label = new Labels();

        try{
        $label->label_name = $request->input('label_name');
        $label->user_id = auth()->user()->id;
        $label->save();
        return response()->json(['status' => 200,  'message' => "Label created"]);
        }catch(Exception $e){
            return response()->json(['status' => 201,  'message' => "duplicate label name not allowed"], 201);
        } 
    }

    /**
     * function to update the label name
     * 
     * @param Request
     * 
     * @return success message or error message based on validation
     */
    public function updateLabel(Request $request)
    {
        $id = $request->input('id');

        $label = new Labels();

        try {
            $label = Labels::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['status' => 201, 'message' => "label id not available"], 201);
        }

        if ($label->user_id == auth()->id()) {
            $label->label_name = $request->input('label_name');
            $label->save();
            return response()->json(['status' => 200, 'message' => "label updated!"]);
        }
    }

    /**
     * function to get all labels based on authorization
     */
    public function getLabels()
    {
        try {
            $label = new Labels();
            $table = $label->user_id = auth()->id();
            return response()->json([User::find($table)->labelsModel]);
        } catch (Exception $e) {
            return response()->json(['status' => 201, 'message' => "token is invalid"], 201);
        }
    }

    /**
     * function to delete label 
     * 
     * @param Request 
     * @param id that will be deleted
     * 
     * @return response 
     */
    public function deleteLabel(Request $request)
    {
        $id = $request->input('id');

        try {
            $label = Labels::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(['status' => 422, 'message' => "Invalid label id"], 422);
        }

        if ($label->user_id == auth()->id()) {
            if ($label->delete()) {
                return response()->json(['status' => 200, 'messaged' => 'label Deleted!']);
            }
        }
    }

    /**
     * function to add note to label
     */
    public function addNoteToLabel(Request $request)
    {

        $labelNotes = new LabelsNotes();
        
        $labelNotes->user_id = auth()->id();
        $labelNotes->note_id = $request->input('note_id');
        $labelNotes->label_id = $request->input('label_id');


        $labelNotesUser = NotesModel::where('user_id' ,  $labelNotes->user_id)->first();

        // $note = NotesModel::where('id', $request->input('note_id'))->first();
        
        return response()->json([$labelNotesUser]);

        // if($labelNotes->user_id == auth()->id()){
            
        // }

      

        // //if validator fails means that the label is already added to the note
        // if ($validator->fails()) {
        //     $err = $validator->errors();
        //     //the it returns the error in the response 
        //     return response()->json(['message' => $err], 210);
        // }
        //  //or map the label to the note 
        //  LabelsNotes::create($labelNotes);
        
        // //fetching the newly added note from the database
         
        
        $labelNotes->save();
        // return response()->json(['status' => 200, 'message' => 'note added to label successfully!']);
       


    }

    /**
     * delete note from the label
     */
    public function deleteNoteFromLabel(Request $request){
        $labelNotes = new LabelsNotes();

        $labelNotes->label_id = $request->get('label_id');
        $labelNotes->note_id = $request->get('note_id');

        if($labelNotes->user_id == auth()->id()){
            $labelNotes->note_id->delete();
            return response()->json(['status' => 200, 'message' => 'Note deleted from the label succesfully!']);
        }else{
            return response()->json(['status' => 201, 'message' => 'invalid credentials']);
        }
    }
}
