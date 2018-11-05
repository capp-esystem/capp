<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;
use Auth;
use Hash;

class StudentController extends Controller
{
    public function display()
    {
        return view('student');
    }

    public function showResetPasswordForm()
    {
        return view('reset_password');
    }

    public function resetPassword(Request $request, StudentService $service)
    {
        $user = Auth::user();
        $originalPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        if (!Hash::check($originalPassword, $user->getAuthPassword())) {
            return back()->withErrors([
                'auth' => 'Invalid Original Password'
            ]);
        } else {
            $service->changePassword($user->id, $newPassword);
            return back()->with('success', 'Password change confirmed');
        }
    }
}
