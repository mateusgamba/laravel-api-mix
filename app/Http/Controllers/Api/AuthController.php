<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;
use Validator;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Login user and return access token
     *
     * @param  [string] email
     * @param  [string] password
     * @return [string] token
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->credentials($request);

        $credentials['activated'] = 1;

        $token = auth()->attempt($credentials);

        if ($token) {
            $success = true;
            $data = ['token' => $token];
            $message = \Lang::get('messages.authAutentication');
            $status_code = 200;
        } else {
            $success = false;
            $data = [];
            $message = \Lang::get('auth.failed');
            $status_code = 400;
        }

        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message
        ], $status_code);
    }

    /**
     * Log out
     *
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => \Lang::get('messages.loggedOut')
        ], 200);
    }

    /**
     * Register new account.
     *
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        $data = $request->only(
            'name',
            'email',
            'password',
            'password_confirmation'
        );

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:12',
            'password_confirmation' => 'required|same:password'
        ];

        $messages = [
            'password_confirmation.same' => \Lang::get('messages.passwordConfirmationSame')
        ];

        $validator = Validator::make($data, $rules, $messages);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors()->messages(),
                'message' => \Lang::get('messages.validationFields')
            ], 400);
        }

        $newUser = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ];

        $user = User::create($newUser);

    	$response = [
            'success' => true,
            'data' => $user,
            'message' => \Lang::get('messages.userCreated')
        ];

        return response()->json($response, 200);
    }

    /**
     * Activate the user with given activation code.
     *
     * @param string $code
     * @return [string] token
     */
    public function activate(string $code)
    {
        $success = false;
        $data = [];
        $message = '';
        $status_code = 400;

        $user = User::where('activation_code', $code)
            ->select('id', 'activated')
            ->first();

        if (is_null($user)) {
            $message = \Lang::get('messages.userVerifiedInvalid');
        } else {
            if ($user->activated) {
                $message = \Lang::get('messages.userAlreadyVerified');
            } else {
                $user['activated'] = 1;
                $userUpdated = $user->save();
                $token = auth()->login($user);

                $success = true;
                $data = $user;
                $data['token'] = $token;
                $message = \Lang::get('messages.userVerifiedSuccess');
                $status_code = 200;
            }
        }

        $response = [
            'success' => $success,
            'data' => $data,
            'message' => $message
        ];
        return response()->json($response, $status_code);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] User
     */
    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => auth()->user(),
            'message' => "Return the authenticated User"
            ],
            200
        );
    }
}
