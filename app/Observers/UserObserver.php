<?php

namespace App\Observers;
use Illuminate\Support\Str;
use App\User;
use Uuid;

class UserObserver{

    // Before Saving ==> Before Registered
    public function saving(User $user){
        $user->id       = Uuid::generate(5, $user->email, Uuid::NS_DNS)->string;
        $user->_token   = Str::random(34);
    }
}
