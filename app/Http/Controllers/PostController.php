<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1. Validate request
        $request->validate([
            'body' => 'required|string',
        ]);

        //2. Validate User
        $user = User::firstWhere('id',  $request->author);
        
        if($user){
            // 3. Validate _token
            if($user->_token === $request->header('_token')){
                //4. Save Post
                $post           = new Post();
                $post->body     = $request->body;
                $post->user_id  = $user->id;

                $res = $post->save();
                
                if($res)
                    return response()->json(['status' => '200', 'post' => $post, 'user' => $user], 200);
                else
                    return response()->json(['status' => '500', 'message' => 'un expected error occurred'], 500);
            }

            // 5. Invalid token
            return response()->json(['status' => '404', 'message' => 'Timed Out'], 404);
        }

        // 6. Invalid User
        return response()->json(['status' => '404', 'message' => 'Invalid User. Timed Out'], 404);
    }
    
    
    /**
     * Return all posts [Home page].
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        //1. Request from valid user
        if(User::where('_token', $request->header('_token'))->count() > 0)        
            return response()->json(['status' => '200', 'data' => Post::with('user')->orderBy('created_at', 'desc')->get()], 200);
        
        return response()->json(['status' => '404', 'message' => 'Invalid User. Timed Out'], 404);
    }


    /**
     * Return only my posts 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function my_posts(Request $request)
    {
        $user = User::firstWhere('id',  $request->header('id'));

        //1. Request from valid user
        if($user && $user->_token == $request->header('_token'))
            return response()->json(['status' => '200', 'data' => Post::where('user_id', $user->id)->with('user')->orderBy('created_at', 'desc')->get()], 200);
        
        
        return response()->json(['status' => '404', 'message' => 'Invalid User. Timed Out'], 404);
    }
}
