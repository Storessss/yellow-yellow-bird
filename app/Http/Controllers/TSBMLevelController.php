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
            $code = Str::upper(Str::random(6));
        } while (TSBMLevelModel::where('level_code', $code)->exists());

        return $code;
    }
}
