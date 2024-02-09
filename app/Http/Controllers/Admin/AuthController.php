<?php

namespace App\Http\Controllers\Admin;

use App\Traits\DataFormController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $createAdmin = Admin::all()->count() > 0 ? '' : Admin::create(['full_name' => 'Master Admin', 'phone' => '0123456789', 'email' => 'sphinx.admin@gmail.com', 'password' => Hash::make('admin'), "role" => "Master"]);

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'please enter your email',
            'password.required' => 'please enter your password',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        $credentials = ['email' => $request->input('email'), 'password' => $request->input('password')];

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard("admin")->user();
            $token = $user->createToken('token')->plainTextToken;
            return $this->jsonData(true, null, 'Successfully Operation', [], ['token' => $token]);
        }

        return $this->jsonData(false, null, 'Faild Operation', ['Email or password is not correct!'], []);
    }

}
