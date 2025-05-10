<?php

namespace App\Http\Controllers;

use App\Models\User;

class Validator extends Controller
{
    public static function validateUsername($username){
        $user = User::where('username', $username)->first();
        if($user){
            return false;
        }
        return true;
    }

    public static function validateEmail($email){
        $user = User::where('email', $email)->first();
        if($user){
            return false;
        }
        return true;
    }

    public static function validatepassword($password){
        if(strlen($password) < 8){
            return false;
        }
        return true;
    }

}