<?php

namespace App\Observers;

use App\Post;
use Uuid;
use Carbon\Carbon;


class PostObserver
{
    // Before Saving post
    public function saving(Post $post){
        $post->id = Uuid::generate(5, Carbon::now() . $post->user_id , Uuid::NS_DNS)->string;
    }
}
