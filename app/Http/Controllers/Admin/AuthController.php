<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Admin\ForgotPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginGet()
    {
        if (Auth::check()) {
                return redirect()->route('admin.dashboard')->with('message', 'Admin Login Successfully');
        } else {
            return view('admin.auth.login');
        }
    }
    public function loginSave(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email,status,1,is_verified,1',
            'password' => 'required|min:6',
        ]);
        if (!Auth::check()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_verified' => 1, 'status' => 1])) {
                    return redirect()->route('admin.dashboard')->with('message', 'Admin Login Successfully');
            } else {

                return redirect()->back()->with('error', 'Invalid Credantials');
            }
        } else {
                return redirect()->route('admin.dashboard')->with('message', 'Admin Login Successfully');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('admin.login.get')->with('message', 'Admin Logout Successfully');
    }

    public function passwordForgotGet()
    {
        return view('admin.auth.forgot-password');
    }
    public function passwordForgotSave(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email,status,1,is_verified,1',
        ]);
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now('Asia/Kolkata'),
        ]);
        $data = [
            'token' => $token,
        ];
        $mail = Mail::to($request->email)->send(new ForgotPassword($data));
        return redirect()->route('admin.login.get')->with('message', 'Password Reset Link send Successfully To Your Email..');
    }

    public function passwordResetGet($token)
    {
        if (isset($token) && $token != '') {
            return view('admin.auth.showresetpasswordform', ['token' => $token]);
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }
    public function passwordResetSave(Request $request)
    {
        $request->validate([
            'newpassword' => 'required|min:6',
            'confirmnewpassword' => 'required|same:newpassword|min:6',
        ]);
        if (!isset($request->token) || $request->token == '') {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }

        $updatePassword = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $user = User::where('email', $updatePassword->email)
            ->update(['password' => Hash::make($request->newpassword)]);
        DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();
        if ($user) {
            return redirect()->route('admin.login.get')->with('message', 'Your Password Has Been Updated!');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }
}
