<?php

namespace App\Http\Controllers;

use App\Models\Labels;
use App\Models\LabelsNotes;
use App\Models\NotesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $label->label_name = $request->input('label_name');
        $label->user_id = Auth::user()->id;

        $duplicateLabel = Labels::where('label_name', $label->label_name)->first();

        if ($duplicateLabel) {
            return response()->json(['status' => 201, 'message' => 'duplicate label name']);
        } else {
            $label->save();
            return response()->json(['status' => 200,  'message' => "Label created"]);
        }
    }
}
