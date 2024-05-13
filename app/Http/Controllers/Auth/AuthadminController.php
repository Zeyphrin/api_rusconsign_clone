<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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


    public function acceptMitra($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['message' => 'Mitra tidak ditemukan'], 404);
        }

        // Lakukan tindakan yang sesuai untuk menerima mitra (contoh: ubah status mitra menjadi diterima)
        $mitra->status = 'accepted';
        $mitra->save();

        return response()->json(['message' => 'Mitra berhasil diterima']);
    }

    public function rejectMitra($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['message' => 'Mitra tidak ditemukan'], 404);
        }

        // Lakukan tindakan yang sesuai untuk menolak mitra (contoh: hapus mitra dari basis data)
        $mitra->delete();

        return response()->json(['message' => 'Mitra berhasil ditolak']);
    }

}

