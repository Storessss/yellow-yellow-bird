<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TSBMGameController extends Controller
{
    public function saveLevel(Request $request) {
        $validator = Validator::make($request->all(), [
            'tiles' => 'required|array',
            'tiles.*.pos_x' => 'required|int',
            'tiles.*.pos_y' => 'required|int',
            'tiles.*.atlas_x' => 'required|int',
            'tiles.*.atlas_y' => 'required|int',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['message' => 'Level saved successfully'], 200);
    }
}
