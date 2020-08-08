<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{

    /**
     * Register a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //1. Validate request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'gender' => 'required|string|in:f,m',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
        ]);

        //2. Check existance
        if(User::where('email', $request->email)->count() > 0)
            return response()->json(['status' => '204', 'message' => 'This user already exist before'], 204);

        //3. Check password confirmation
        if($request->password !== $request->confirm_password)
            return response()->json(['status' => '204', 'message' => 'password and confirm password must be the same'], 204);

        //4. Create user object
        $user                   = new User();
        $user->first_name       = $request->first_name;
        $user->last_name        = $request->last_name;
        $user->email            = $request->email;
        $user->gender           = $request->gender;
        $user->password         = Hash::make($request->password);

        $res = $user->save();
        
        //5. Return success data
        if($res)
            return response()->json(['status' => '200', 'message' => 'Successfully Registered', 'data' => $user], 200);
        
        //6. Error occurred
        return response()->json(['status' => '500', 'message' => 'un expected error occurred'], 500);
    }


    /**
     * Login a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //1. Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //2. Check existance
        $user = User::firstWhere('email',  $request->email);
        
        if($user){
            // 3. Check Password Correctness
            if(Hash::check($request->password, $user->password)){
                $user->status = 1; // user become active
                $user->_token = Str::random(34);
                $user->save();

                return response()->json(['status' => '200', 'data' => $user], 200);
            }

            // 4. Password InCorrect
            return response()->json(['status' => '404', 'message' => 'username or password not valid'], 404);
        }

        //5. User not exist
        return response()->json(['status' => '404', 'message' => 'User does not exist. You must register first'], 404);
    }

    /**
     * Logout a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $_token = $request->header('_token');

        $logout = User::where('id',  $request->id)->where('_token', $_token)->update(['status' => 0, '_token' => null]);

        if($logout){
            return response()->json([ "status" => "200", 'data' => 'successfully loged out'], 200);
        }

        return response()->json([ "status" => "401", 'message' => "Invalid Data"], 401);
    }


    /**
     * Active Users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        //1. Request from valid user
        $_token = $request->header('_token');

        $active_users = User::where('status', 1)->get();

        //2. token is valid, not timed out
        if(User::where('_token', $_token)->count() > 0){
            return response()->json([ "status" => "200", 'data' => $active_users], 200);
        }

        //3. Invalid token
        return response()->json([ "status" => "404", 'message' => "Invalid Data. Timed Out"], 404);
    }
}
