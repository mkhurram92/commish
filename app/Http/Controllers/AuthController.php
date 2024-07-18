<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        session()->remove("use_old_database");
        $db = $request['db'];

        if ($db == "olddata") {
            $db = env('DB_SECOND_DATABASE');
            Config::set('database.default', 'mysql2');
        } else {
            $db = env("DB_DATABASE");
            Config::set('database.default', 'mysql');
        }
        DB::purge('mysql');

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        //  User::where('username', $request['username'])->update(['password'=>Hash::make($request['password'])]);
        $authUser = User::where('username', $request['username'])->first();
        if ($authUser) {

            $checkPass = Hash::check($request['password'], $authUser->password);
            if ($checkPass) {
                $credentials = [
                    'email' => $authUser->email,
                    'password' => $request['password'],
                ];
                Auth::attempt($credentials);
                if ($authUser->role == 'admin' || $authUser->role == 'subadmin') {

                    $db = $request['db'];

                    if ($db == "olddata") {
                        Config::set('database.default', 'mysql2');
                        session()->put("use_old_database", true);
                    } else {
                        \Session::put('selected_database', 'mysql');
                        Config::set('database.default', 'mysql');
                        session()->remove("use_old_database");
                    }
                    DB::purge('mysql');
                    return redirect('admin/dashboard');
                } else {
                    return redirect()->back()->with('error', 'You have not admin access.');
                }
            } else {
                return redirect()->back()->with('error', 'Password is incorrect.');
            }
        } else {
            return redirect()->back()->with('error', 'Username is incorrect.');
        }
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
        ]);
        $emailExist = User::where('email', $request['email'])->first();
        if ($emailExist) {
            $loginRoute = route('reset.password.view', encrypt($emailExist->email));
            $link = "<a href='$loginRoute'>Reset Your Password!</a>";
            $data = [
                'subject' => "Reset Password",
                'to' => $emailExist->email,
                'content' => "This link automatically expire within 24 hours and you can use this just 1 time. " . $link
            ];
            try {
                Mail::send([], $data, function ($message) use ($data) {
                    $message->to($data['to'])
                        ->subject($data['subject'])
                        ->setbody($data['content'], 'text/html');
                });
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            $resetRecord = new ResetPassword();
            $resetRecord->email = $emailExist->email;
            $resetRecord->save();
            return redirect()->back()->with('success', 'Check your email. System will expire your link within 24 hours.');
        } else {
            return redirect()->back()->with('error', 'Email is not in our record. Use registered email address.');
        }
    }

    public function restPasswordView($email)
    {
        $user = User::where('email', decrypt($email))->first();
        if ($user) {
            $resetReq = ResetPassword::where('email', $user->email)->first();
            if ($resetReq) {
                $createDate = $resetReq['created_at'];
                $currentDate = Carbon::now();
                $timeDiff = $currentDate->diffInHours($createDate);
                if ($timeDiff < 24) {
                    return view('reset_password', compact('user'));
                } else {
                    return redirect()->route('login')->with('error', 'Link expired. Max 24 hours required to use this link.');
                }
            } else {
                return redirect()->route('login')->with('error', 'This link is already used for reset password.');
            }
        } else {
            return redirect()->route('login')->with('error', 'Use valid link for forgot password.');
        }
    }

    public function passwordChange(Request $request, $id)
    {
        $user = User::find(decrypt($id));
        if ($user) {
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'same:retype_password'
                ],
                'retype_password' => 'required',
            ]);
            $user->password = Hash::make($request['password']);
            $user->save();
            ResetPassword::where('email', $user->email)->delete();
            return redirect()->route('login')->with('success', 'Password successfully updated. You can login now.');
        } else {
            return redirect()->route('login')->with('error', 'User not found.');
        }
    }
}
