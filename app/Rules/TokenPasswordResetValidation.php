<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use Carbon\Carbon;

use App\PasswordReset;

class TokenPasswordResetValidation implements Rule
{
    public $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = '';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $password = PasswordReset::where('token', $value)
            ->first();
        if (is_null($password)) {
            $this->message = 'passwords.token';
            return false;
        } elseif (Carbon::parse($password->updated_at)->addMinutes(720)->isPast()) {
            $this->message = 'messages.tokenExpired';
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return \Lang::get($this->message);
    }
}
