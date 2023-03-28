<?php

namespace App\Models;

use App\Exceptions\CustomHttpException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use RushApp\Core\Services\LoggingService;
use RushApp\Core\Models\BaseModel;

class RecoverPassword extends BaseModel
{
    protected $fillable = ['id', 'email', 'token'];

    /**
     * @param string $email
     * @return mixed
     */
    public function validEmail(string $email)
    {
        return !!User::where('email', $email)->first();
    }

    /**
     * @param string $email
     * @return string
     */
    public function generateResetTokenUrl(string $email): string
    {
        return Config::get('constants.env.user_front_url').'/change-forgotten-password?token='.$this->generateToken($email);
    }

    /**
     * @param $email
     * @return string
     */
    public function generateToken($email): string
    {
        if($isOtherToken = $this->where('email', $email)->first()) {
            return $isOtherToken->token;
        }

        $token = Str::random(80);
        $this->createOne(['email' => $email, 'token' => $token]);

        return $token;
    }

    /**
     * @param $request
     * @return void
     */
    public function resetPassword($request): void
    {
        try {
            $user = User::whereEmail($request->email)->first();
            $user->password = $request->password;
            $user->save();
            // remove verification data from db
            $this->updatePasswordRow($request)->delete();
        } catch (\Exception $e) {
            LoggingService::critical('resetPassword error' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.save_error'));
        }
    }

    public function updatePasswordRow($request)
    {
        return $this->where([
            'email' => $request->email,
            'token' => $request->passwordToken
        ]);
    }
}
