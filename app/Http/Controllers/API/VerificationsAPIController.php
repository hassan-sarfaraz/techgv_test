<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\APIGenOtpEmailPostRequest;
use App\Http\Requests\APIGenOtpPhonePostRequest;
use App\Http\Requests\APIVerifyOtpEmailPostRequest;
use App\Http\Requests\APIVerifyOtpPhonePostRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class VerificationsAPIController extends BaseController
{

    function emailOtp(APIGenOtpEmailPostRequest $request)
    {
        $language_id           = 1;
        $email                 = $request->get('email');
        $response              = [
            'status' => 0,
            'message' => 'An error occurred. Please try again!'
        ];

        try {
            $user = User::where('email', '=', $email)->first();

            // Already Verified
            if ($user->email_verified == 1) {
                $response['message'] = 'This email address is already verified.';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }

            $otp = rand(1000, 9999);
            User::where('email', '=', $email)->update(['email_verified' => $otp]);
            $user = User::where('email', '=', $email)->first();

            Mail::send('/mail/otpEmail', ['userData' => $user], function ($m) use ($user) {
                $m->to($user->email)->subject('OTP for Email Verification')->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            $response              = [
                'status' => 1,
                'message' => 'OTP generated and emailed successfully.'
            ];
        } catch (\Exception $exception) {
        }
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function verify_emailOtp(APIVerifyOtpEmailPostRequest $request)
    {

        $language_id           = 1;
        $email                 = $request->get('email');
        $otp                   = $request->get('otp');
        $response              = [
            'status' => 0,
            'message' => 'An error occurred. Please try again!'
        ];

        try {
            $user = User::where('email', '=', $email)->first();

            // Already Verified
            if ($user->email_verified == 1) {
                $response['message'] = 'This email address is already verified.';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }

            // Verify provided OTP
            if ($user->email_verified != $otp) {
                $response['message'] = 'Invalid OTP provided. Please try again!';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }

            User::where('email', '=', $email)->update(['email_verified' => 1]);

            $response              = [
                'status' => 1,
                'message' => 'Email verified successfully.'
            ];
        } catch (\Exception $exception) {
        }
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function phoneOtp(APIGenOtpPhonePostRequest $request)
    {
        $language_id           = 1;
        $email                 = $request->get('email');
        $phone                 = $request->get('phone');
        $otp                   = $request->get('otp');
        $response              = [
            'status' => 0,
            'message' => 'An error occurred. Please try again!'
        ];

        try {
            $user = User::where('email', '=', $email)->where('phone', '=', $phone)->first();

            // Already Verified
            if ($user->phone_verified == 1) {
                $response['message'] = 'This phone is already verified.';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }

            User::where('email', '=', $email)->where('phone', '=', $phone)->update(['phone_verified' => $otp]);

            $response              = [
                'status' => 1,
                'message' => 'OTP updated successfully.'
            ];
        } catch (\Exception $exception) {
        }
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    function verify_phoneOtp(APIVerifyOtpPhonePostRequest $request)
    {

        $language_id           = 1;
        $email                 = $request->get('email');
        $phone                 = $request->get('phone');
        $otp                   = $request->get('otp');
        $response              = [
            'status' => 0,
            'message' => 'An error occurred. Please try again!'
        ];

        try {
            $user = User::where('email', '=', $email)->where('phone', '=', $phone)->first();

            // Already Verified
            if ($user->phone_verified == 1) {
                $response['message'] = 'This phone is already verified.';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }

            /*// Verify provided OTP
            if($user->phone_verified != $otp) {
                $response['message'] = 'Invalid OTP provided. Please try again!';
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            }*/

            User::where('email', '=', $email)->where('phone', '=', $phone)->update(['phone_verified' => 1]);

            $response              = [
                'status' => 1,
                'message' => 'Phone verified successfully.'
            ];
        } catch (\Exception $exception) {
        }
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }
}
