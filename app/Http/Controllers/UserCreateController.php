<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserCreateController extends Controller
{
    public function create(String $id)
    {
        $user = User::find($id);
        if ($user == null) {
            $user = new User();
            $user->id = $id;
            $user->name = '';
            $user->email = '';
            $user->password = bcrypt('12345678');
            $user->save();
        }

        return $user;
    }
}
