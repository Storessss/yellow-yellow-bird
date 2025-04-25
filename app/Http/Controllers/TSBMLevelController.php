<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TSBMLevelModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TSBMLevelController extends Controller
{
    public function saveLevel(Request $request) {
        $validator = Validator::make($request->all(), [
            'level_name' => ['required', 'string', 'max:75'],
            'level_data' => 'required', 'json'
        ], [
            'level_name.required' => 'The level name is required.',
            'level_name.string' => 'The level name must be a string.',
            'level_name.max' => 'The level name must not exceed 75 characters.',
            'level_data.required' => 'The level data is required.',
            'level_data.json' => 'The level data must be a valid JSON string.',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Level validation failed', 'errors' => $validator->errors()], 422);
        }

        $levelData = json_decode($request->input('level_data'), true);
        $levelDataValidator = Validator::make($levelData, [
            'tiles' => 'required|array',
            'tiles.*.pos_x' => 'required|integer',
            'tiles.*.pos_y' => 'required|integer',
            'tiles.*.atlas_x' => 'required|integer',
            'tiles.*.atlas_y' => 'required|integer',
            'tiles.*.rot' => 'required|integer',
        ]);
    
        if ($levelDataValidator->fails()) {
            return response()->json([
                'message' => 'Invalid level data.',
                'errors' => $levelDataValidator->errors()
            ], 422);
        }

        $incomingFields = $validator->validated();

        $level_code = $this->generateUniqueCode();

        $incomingFields['level_code'] = $level_code;

        TSBMLevelModel::create($incomingFields);
        return response()->json([
            'message' => 'Level saved successfully.',
            'level_code' => $level_code,
        ], 200);
    }

    function generateUniqueCode() {
        do {
            $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        } while (TSBMLevelModel::where('level_code', $code)->exists());

        return $code;
    }

    public function getRandomLevel() {
        $level = TSBMLevelModel::inRandomOrder()->first();
    
        if ($level) {
            $user = $level->user;
            return response()->json([
                'message' => 'Random level retrieved successfully.',
                'level_name' => $level->level_name,
                'level_data' => $level->level_data,
                'level_code' => $level->level_code,
            ], 200);
        } 
        else {
            return response()->json([
                'message' => 'No levels found.'
            ], 404);
        }
    }

    public function getLevelByCode($code) {
        $level = TSBMLevelModel::where('level_code', $code)->first();
    
        if ($level) {
            $user = $level->user;
            return response()->json([
                'message' => 'Level retrieved successfully by code.',
                'level_name' => $level->level_name,
                'level_data' => $level->level_data,
                'level_code' => $level->level_code,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Level not found.'
            ], 404);
        }
    }
}
