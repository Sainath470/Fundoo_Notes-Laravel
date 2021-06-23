<?php

namespace App\Http\Controllers;

use App\Models\Labels;
use App\Models\LabelsNotes;
use App\Models\NotesModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabelController extends Controller
{
    /**
     * @OA\Post(
     ** path="/api/auth/makeLabel",
     *   tags={"Make Label"},
     *   summary="Make Label",
     *   operationId="makeLabel",
     * 
     *  @OA\Parameter(
     *      name="Label Name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function createLabel(Request $request)
    {
        $label = new Labels();

        try {
            $label->label_name = $request->input('label_name');
            $label->user_id = auth()->user()->id;
            $label->save();
            return response()->json(['status' => 200,  'message' => "Label created"]);
        } catch (Exception $e) {
            return response()->json(['status' => 201,  'message' => "duplicate label name not allowed"], 201);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/auth/editLabelname",
     *   tags={"Edit Label"},
     *   summary="Edit Label",
     *   operationId="editLabelname",
     * 
     *  @OA\Parameter(
     *      name="Label id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Label Name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
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
     * @OA\Get(
     ** path="/api/auth/getLabels",
     *   tags={"Get Labels"},
     *   summary="Get Labels",
     *   operationId="getLabels",
     * 
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
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
     * @OA\Post(
     ** path="/api/auth/deleteLabel",
     *   tags={"Delete Label"},
     *   summary="Delete Label",
     *   operationId="deleteLabel",
     * 
     *  @OA\Parameter(
     *      name="Label id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
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
     * @OA\Post(
     ** path="/api/auth/addnotetolabel",
     *   tags={"Add Note to Label"},
     *   summary="Adding Note to Label",
     *   operationId="addnotetolabel",
     * 
     *  @OA\Parameter(
     *      name="Note id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Label id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function addNoteToLabel(Request $request)
    {
        $labelsNotes = new LabelsNotes();

        $id = auth()->id();

        $labelsNotes->user_id = User::where('id', $id)->value('id');
        $labelsNotes->label_id = Labels::where('id', $request->input('label_id'))->value('id');
        $labelsNotes->note_id = NotesModel::where('id', $request->input('note_id'))->value('id');

        $userInNotesTable = NotesModel::where('id', $request->input('note_id'))->value('user_id');
        $userInLabelsTable = Labels::where('id', $request->input('label_id'))->value('user_id');

        if ($labelsNotes->user_id != $userInNotesTable) {
            return response()->json(['status' => 201, 'message' => 'note not available for this user!'], 201);
        }
        if ($labelsNotes->user_id != $userInLabelsTable) {
            return response()->json(['status' => 201, 'message' => 'Label not available for this user!'], 201);
        }

        $labelsNotes->save();
        return response()->json(['status' => 200, 'message' => 'note added to label successfully!']);
    }

   /**
     * @OA\Post(
     ** path="/api/auth/deletenotefromlabel",
     *   tags={"Delete Note and Label"},
     *   summary="Deleting Note and Label",
     *   operationId="deletenotefromlabel",
     * 
     *  @OA\Parameter(
     *      name="Note id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Label id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteNoteFromLabel(Request $request)
    {
        $labelsNotes = new LabelsNotes();

        $id = auth()->id();

        $labelsNotes->user_id = User::where('id', $id)->value('id');
        $labelsNotes->label_id = Labels::where('id', $request->input('label_id'))->value('id');
        $labelsNotes->note_id = NotesModel::where('id', $request->input('note_id'))->value('id');

        $userInNotesTable = NotesModel::where('id', $request->input('note_id'))->value('user_id');
        $userInLabelsTable = Labels::where('id', $request->input('label_id'))->value('user_id');

        if ($labelsNotes->user_id != $userInNotesTable) {
            return response()->json(['status' => 201, 'message' => 'note not available for this user!'], 201);
        }
        if ($labelsNotes->user_id != $userInLabelsTable) {
            return response()->json(['status' => 201, 'message' => 'Label not available for this user!'], 201);
        }
        $labelsNotesId = LabelsNotes::where('note_id',  $labelsNotes->note_id)->where('label_id', $labelsNotes->label_id)->first();

        if ($labelsNotesId->delete()) {
            return response()->json(['status' => 200, 'message' => 'note deleted from label successfully!']);
        }
    }
    
      /**
     * @OA\Post(
     ** path="/api/auth/getAllNotesLabels",
     *   tags={"Get All Notes with Labels"},
     *   summary="Get All Notes with Labels",
     *   operationId="getAllNotesLabels",
     * 
     *  @OA\Parameter(
     *      name="Note id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Label id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllNotesInLabels()
    {
        $notes = LabelsNotes::all();
        $notes->user_id = auth()->id();

        $user = User::where('id', $notes->user_id)->value('id');
        if ($notes->user_id == $user) {
            $data = DB::table('labels_notes')
                ->join('labels', 'labels_notes.label_id', '=', 'labels.id')
                ->join('notes', 'labels_notes.note_id', '=', 'notes.id')
                ->select('notes.id as note_id','notes.title', 'notes.description', 'labels.label_name', 'labels.id as label_id')
                ->where('labels_notes.user_id', $notes->user_id)
                ->get();
            return $data;
        } else {
            return response()->json(['status' => 201, 'message' => 'Token is invalid!']);
        }
    }
}
