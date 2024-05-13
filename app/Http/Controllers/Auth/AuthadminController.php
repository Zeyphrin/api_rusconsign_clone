<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\MitraResource;
use App\Models\Admin;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class AuthadminController extends Controller
{
    public function registeradmin(Request $request)
    {
        $request->validate([
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed"
          ]);

          Admin::create([
            "email"=> $request->email,
            "password"=> bcrypt($request->password)
          ]);

          return response()-> json([
            "status"=> true,
            "massage"=> "Admin registared succesfully",
            "data"=> []
          ]);
    }

//    public function loginadmin(Request $request)
//    {
//          //validation
//          $request->validate([
//            "email"=>"required|email|string",
//            "password"=>"required"
//        ]);
//
//        $user = Admin::where("email", $request->email)->first();
//
//        if(!empty($user)){
//            if(Hash::check($request->password, $user->password)){
//                $token = $user->createToken("mytoken")->plainTextToken;
//
//                return response()->json([
//                    "status"=> true,
//                    "massage"=>"User logged in",
//                    "token" => $token,
//                    "data"=>[]
//                ]);
//
//            }else{
//                return response()->json([
//                    "status"=> false,
//                    "massage"=> "Invalid password",
//                    "data"=> []
//                ]);
//            }
//
//        }else{
//            return response()->json([
//                "status"=> false,
//                "massage"=> "Email doesn't match with records",
//                "data"=> []
//            ]);
//        }
//    }


    public function accept(Request $request, $id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }

        // Change status to "accepted"
        $mitra->status = 'accepted';
        if ($mitra->save()) {
            return new MitraResource($mitra);
        } else {
            return response()->json(['message' => 'Failed to accept mitra'], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $mitra = Mitra::find($id);
        if (!$mitra) {
            return response()->json(['message' => 'Mitra not found'], 404);
        }

        // Change status to "rejected"
        $mitra->status = 'rejected';
        if ($mitra->save()) {
            return new MitraResource($mitra);
        } else {
            return response()->json(['message' => 'Failed to reject mitra'], 500);
        }
    }

}

