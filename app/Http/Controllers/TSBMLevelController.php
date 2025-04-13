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
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'No token provided. Unauthorized'], 401);
        }

        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid token. Unauthorized'], 401);
        }

        $userId = $user->id;


        $validator = Validator::make($request->all(), [
            'level_name' => 'required',
            'level_data' => 'required',
        ], [
            'level_name.required' => 'The level name is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Level validation failed', 'errors' => $validator->errors()], 422);
        }

        $incomingFields = $validator->validated();

        $token = $request->bearerToken();

        $user = Auth::guard('sanctum')->user();
        $incomingFields['level_code'] = $this->generateUniqueCode();
        $incomingFields['user_id'] = $userId;

        TSBMLevelModel::create($incomingFields);
        return response()->json(['message' => 'Level saved successfully.'], 200);
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
                'user' => $user->name,
                'created_at' => $level->created_at,
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
                'user' => $user->name,
                'created_at' => $level->created_at,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Level not found.'
            ], 404);
        }
    }
}
