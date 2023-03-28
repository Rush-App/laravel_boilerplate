<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\RegisterRequest;
use App\Models\CoreModels\UserRole;
use App\Models\User;
use App\Services\ForSendingMessages\SendingEmailService;
use App\Services\ForSendingMessages\SendingToTelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use RushApp\Core\Controllers\BaseAuthController;
use RushApp\Core\Models\Role;
use RushApp\Core\Services\LoggingService;

class AuthController extends BaseAuthController
{
    /** the name of the model must be indicated in each controller */
    protected string $modelClassController = User::class;
    protected string $guard = 'user';

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->registerAttempt($request);

        if (property_exists($result->getData(), 'token')) {
            $this->setRegistrationUserRole();

            SendingToTelegramService::sendMessage([
                'SUCCESS' => 'Added a new User',
                'MESSAGE' => 'email - '.$request->email
            ]);

            SendingEmailService::sendEmailForUser(
                $request->language,
                'email_messages.registration',
                'email_messages.registration_title',
            );
        } else {
            SendingToTelegramService::sendMessage([
                'ERROR' => 'Error in User register',
                'MESSAGE' => 'email - '.$request->email
            ]);
        }

        return $result;
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = User::find(Auth::id());

        if (!Auth::guard($this->guard)->attempt(['email' => $user->email, 'password' => $request->old_password])) {
          LoggingService::info(Config::get('response_messages.incorrect_change_password') . $user->email);

          return $this->responseWithError(__('response_messages.incorrect_change_password'), 403);
        }

        $user->password = $request->password;
        $user->save();

        return $this->loginAttempt(['email' => $user->email, 'password' => $request->password]);
    }

    public function refreshToken(): JsonResponse
    {
        try {
            $token = Auth::guard($this->guard)->refresh();
        } catch (\Exception $e) {
            return $this->responseWithError(__('response_messages.token_has_been_blacklisted'), 426);
        }

        return $this->successResponse(['token' => $token]);
    }

    protected function setRegistrationUserRole(): void
    {
        $role = Role::where('is_registration_role', true)->first();

        if (!empty($role)) {
            $userRole = new UserRole();
            $userRole->role_id = $role->id;
            $userRole->user_id = Auth::id();
            $userRole->save();
        }
    }
}
