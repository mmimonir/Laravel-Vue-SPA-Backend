<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(['message'=>$response]);
    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(['error'=>$response], 422);
    }
}
