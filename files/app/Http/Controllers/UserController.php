<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\File;

use Validator;

class UserController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10|max:10',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => 422,
                'success' => false, 
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 200);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role_id = $request->role_id;
        $user->description = $request->description;
        
        if (request()->hasFile('image')) {

            $image_file = request()->file('image');

            $path = 'upload/profile/';

            File::makeDirectory($path, $mode = 0777, true, true);
            
            $name = time().'.'.$image_file->getClientOriginalExtension();

            $image_file->move($path, $name);

            $user->image = $name;
        }

        $user->save();

        $user_data = User::all();

        return response()->json(['success' => true, 'data' => $user_data]);
    }

}
