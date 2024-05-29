<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\DataFormController;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use App\Traits\PushNotificationTrait;
use Illuminate\Support\Facades\Auth;
use App\Traits\SavePhotoTrait;
use App\Traits\SendEmailTrait;

class UserController extends Controller
{
    use DataFormController, PushNotificationTrait, SavePhotoTrait, SendEmailTrait;

    public function register(Request $request)
    {
        if (!$request->sign_up_type) :
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'unique:users,email', 'email'],
                'phone' => 'required|unique:users,phone',
                'password' => ['required', 'min:8', "confirmed"],
            ], [
                'name.required' => 'Please enter your name.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'phone.required' => 'Please enter your phone number.',
                'email.unique' => 'This email address already exists.',
                'phone.unique' => 'This phone number already exists.',
                'password.required' => 'Please enter a password.',
                'password.min' => 'Password should be at least 8 characters long.',
                'password.confirmed' => 'Password should be at least 8 characters long.',
            ]);

            if ($validator->fails()) {
                return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
            }

            $createUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            if ($createUser) :
                $token = $createUser->createToken('token')->plainTextToken;
                return
                    $this->jsonData(
                        true,
                        null,
                        'Register successfuly',
                        [],
                        [
                            'id' => $createUser->id,
                            'email' => $createUser->email,
                            'phone' => $createUser->phone,
                            'token' => $token
                        ]
                    );
            endif;
        elseif ($request->sign_up_type && $request->sign_up_type === "Google") :
            if ($request->email) {
                $userExistis = User::where("email", $request->email)->where("join_type", "Google")->first();
                if ($userExistis) {
                    if (filter_var( $userExistis->email, FILTER_VALIDATE_EMAIL)) {
                        $credentials = ['email' => $userExistis->email, 'password' => "Google"];
                    } else {
                        $credentials = ['phone' => $userExistis->email, 'password' => "Google"];
                    }

                    if (Auth::attempt($credentials)) {
                        $user = Auth::user();
                        $token = $user->createToken('token')->plainTextToken;
                        return $this->jsonData(true, null, 'Register successfuly', [], ['token' => $token]);
                    }
                }
            }
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'unique:users,email', 'email'],
            ], [
                'name.required' => 'Please enter your name.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address already exists.',
            ]);

            if ($validator->fails()) {
                return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
            }

            $createUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'picture' => $request->picture,
                'password' => Hash::make("Google"),
                "join_type" => "Google"
            ]);

            if ($createUser) :
                $token = $createUser->createToken('token')->plainTextToken;
                return
                    $this->jsonData(
                        true,
                        null,
                        'Register successfuly',
                        [],
                        [
                            'id' => $createUser->id,
                            'email' => $createUser->email,
                            'join_type' => $createUser->join_type,
                            'token' => $token
                        ]
                    );
            endif;
        endif;
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailorphone' => 'required',
            'password' => 'required',
        ], [
            'emailorphone.required' => 'please enter your email or phone number',
            'password.required' => 'please enter your password',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

        if (filter_var($request->input('emailorphone'), FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $request->input('emailorphone'), 'password' => $request->input('password')];
        } else {
            $credentials = ['phone' => $request->input('emailorphone'), 'password' => $request->input('password')];
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return $this->jsonData(true, null, 'Successfully Operation', [], ['token' => $token]);
        }
        return $this->jsonData(false, null, 'Faild Operation', ['Your email/phone number or password are incorrect'], []);
    }


    public function getUser(Request $request)
    {
        if ($request->user()) :
            if ($request->notification_token) :
                $request->user()->notification_token = $request->notification_token;
                $request->user()->save();
            endif;
            return $this->jsonData(true, null, '', [], ['user' => $request->user()]);
            else :
                return $this->jsonData(false, null, 'Account Not Found', [], []);
            endif;
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:8', "confirmed"],
        ], [
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password should be at least 8 characters long.',
            'password.confirmed' => 'Password should be at least 8 characters long.',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Registration failed', [$validator->errors()->first()], []);
        }

        $user = $request->user();
        if($user)
            $user->password = Hash::make($request->password);
        $user->save();

        if ($user)
            return $this->jsonData(true, null, 'Password changed successfuly', [], []);

    }

    public function updateUser(Request $request)
    {
        $user = $request->user();

        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->phone) {
            $user->phone = $request->phone;
        }
        if ($request->photo) {
            $image = $this->saveImg($request->photo, 'images/uploads/UsersProfile', "photo_" . $user->id . time(), 200);
            $user->picture = "/images/uploads/UsersProfile/" . $image;
            $user->isPhotoEdited = true;
        }
        if ($request->email && $user->join_type == "Google") {
            $user->phone = $request->phone;
        }
        $user->save();

        if ($user)
            return $this->jsonData(true, null, 'Profile updated successfuly', [], []);

    }

    public function sendResetEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ], [
            'email.required' => 'please enter your email or phone number',
        ]);

        if ($validator->fails()) {
            return $this->jsondata(false, null, 'Login failed', [$validator->errors()->first()], []);
        }

    }

    public function getUserNotifications(Request $request) {
        $user = $request->user();

        $notifications;

        if ($user)
            $notifications = Notification::latest()->where(function($q) use ($user) {
                $q->where("user_id", $user->id)
                ->orWhere("user_id", null);
            })
            ->where("created_at", '>=', $user->created_at)->take(30)->get();

        return $notifications;
    }

    public function sedToken(Request $request) {
        $this->sendEmail("kotbekareem74@gmail.com", "Hello", $request->token ?? "");
    }
}
