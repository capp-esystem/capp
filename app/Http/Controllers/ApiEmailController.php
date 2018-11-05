<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiEmailController extends Controller
{
    public function sendEmails($courseId, Request $request, \App\Services\EmailService $service)
    {
        $config = $request->input('config');
        return $service->sendEmails($courseId, $config);
    }
}
