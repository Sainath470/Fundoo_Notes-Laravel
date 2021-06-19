<?php

namespace App\Http\Controllers;

use App\Models\Labels;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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
        $label->user_id = auth()->user()->id;

        $duplicateLabel = Labels::where('label_name', $label->label_name)->first();

        if ($duplicateLabel) {
            return response()->json(['status' => 201, 'message' => 'duplicate label name']);
        } else {
            $label->save();
            return response()->json(['status' => 200,  'message' => "Label created"]);
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
            return response()->json(['status' => 201, 'message' => "label id not available"]);
        }

        if ($label->user_id == auth()->id()) {
            $label->label_name = $request->input('label_name');
            $label->save();
            return response()->json(['status' => 200, 'message' => "label updated!"]);
        }
    }
}
