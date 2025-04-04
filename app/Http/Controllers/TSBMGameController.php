<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TSBMGameController extends Controller
{
    public function saveLevel(Request $request) {
        $validator = Validator::make($request->all(), [
            'level_data' => 'required|array',
            'level_data.*.pos_x' => 'required|int',
            'level_data.*.pos_y' => 'required|int',
            'level_data.*.atlas_x' => 'required|int',
            'level_data.*.atlas_y' => 'required|int',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        return response()->json(['message' => 'Level saved successfully'], 200);
    }

    public function registerAndSave(Request $request) {
        $userController = new UserController();
        $response = $userController->register($request);

        if ($response->getStatusCode() === 200) {
            return $this->saveLevel($request);
        }
        return $response;
    }

    public function loginAndSave(Request $request) {
        $userController = new UserController();
        $response = $userController->login($request);

        if ($response->getStatusCode() === 200) {
            return $this->saveLevel($request);
        }
        return $response;
    }
}
