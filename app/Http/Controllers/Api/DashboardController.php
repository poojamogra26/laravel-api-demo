<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Administrator;

class DashboardController extends Controller
{
    public function counters()
    {
        try {
            $administratorsCount = Administrator::all()->count();
            $usersCount = User::all()->count();

            return response()->json([
                "status" => true,
                "message" => "Get users and administrators count successfully.",
                "payload" => [
                    'users_count' =>  $usersCount, 
                    'administrators_count' => $administratorsCount,
                ],
            ], 200);
        } catch(Exception $e) {

            return response()->json(['status' => false,'error' => $e->getMessage()], 500);
        }
    }
}
