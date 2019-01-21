<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\TokenPasswordResetValidation;

use App\Notifications\PasswordResetSuccess;

use App\User;
use App\PasswordReset;

use Validator;

class PasswordResetController extends Controller
{
     /**
     * Create token password reset and send email
     *
     * @param  [string] email
     * @return [string] message
     */
    public function forgot(Request $request)
    {
        $data = $request->only('email');

        $rules = ['email' => 'required|string|email|exists:users,email'];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()->messages(),
                'message' => \Lang::get('messages.validationFields')
            ], 400);
        }

        $user = User::where('email', $data['email'])->first();

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
            ]
        );

        $response = [
            'success' => true,
            'data' => [],
            'message' => \Lang::get('passwords.sent')
        ];
        return response()->json($response, 200);
    }

    /**
     * Check token password reset
     *
     * @param  [string] $token
     * @return [string] message
     *
     */
    public function verification(string $token)
    {
        $data = ['token' => $token];

        $rules = [
            'token' => [
                'required',
                'string',
                new TokenPasswordResetValidation
            ]
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()->messages(),
                'message' => \Lang::get('messages.validationFields')
            ], 400);
        }

        return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Token is valid.'
            ],
            200
        );
    }

     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     */
    public function reset(Request $request)
    {
        $data = $request->only(
            'token',
            'password',
            'password_confirmation'
        );

        $rules = [
            'password' => 'required|string|min:6|max:12',
            'password_confirmation' => 'required|same:password',
            'token' => [
                'required',
                'string',
                new TokenPasswordResetValidation
            ]
        ];

        $messages = ['password_confirmation.same' => \Lang::get('messages.passwordConfirmationSame')];

        $validator = Validator::make($data, $rules, $messages);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()->messages(),
                'message' => \Lang::get('messages.validationFields')
            ], 400);
        }

        $password = PasswordReset::where('token', $data['token'])->first();

        $user = User::where('email', $password->email)
            ->update(
                [
                    'password' => bcrypt($data['password']),
                    'activated' => 1
                ]
            );

        $password->notify(new PasswordResetSuccess());

        $password->delete();

        return response()->json([
                'success' => true,
                'data' => [],
                'message' => \Lang::get('messages.reset')
            ],
            200
        );
    }


}
