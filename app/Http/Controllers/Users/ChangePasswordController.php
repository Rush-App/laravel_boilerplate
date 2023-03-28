<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\CustomHttpException;
use App\Http\Requests\Users\ChangeForgottenPasswordRequest;
use App\Models\RecoverPassword;
use App\Services\ForSendingMessages\SendingEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RushApp\Core\Controllers\BaseAuthController;
use RushApp\Core\Services\LoggingService;

class ChangePasswordController extends BaseAuthController
{
    /**
     * the name of the model must be indicated in each controller
     * @var string
     */
    protected string $modelClassController = RecoverPassword::class;
    protected string $guard = 'user';
    protected RecoverPassword $mainModel;

    public function __construct(RecoverPassword $mainModel)
    {
        $this->mainModel = $mainModel;
    }

    public function setNewPassword(Request $request): JsonResponse
    {
        if(!$this->mainModel->validEmail($request->email)) {
            return $this->responseWithError(__('response_messages.email_does_not_exist'), 404);
        }

        try {
            SendingEmailService::sendEmailForUser(
                $request->language,
                'email_messages.reset_password',
                'email_messages.reset_password_title',
                null,
                $request->email,
                'emails.reset-password.reset-password-',
                ['reset_url' => $this->mainModel->generateResetTokenUrl($request->email)]
            );
        } catch (\Exception $e) {
            LoggingService::critical('ChangePasswordController - send message error - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.error_500'));
        }

        return $this->successResponse(['message' => __('response_messages.check_your_email_to_reset_password')]);
    }

    public function passwordResetProcess(Request $request): JsonResponse
    {
        $this->validateRequest($request, ChangeForgottenPasswordRequest::class);

        if ($this->mainModel->updatePasswordRow($request)->count() > 0) {

            $this->mainModel->resetPassword($request);

            return $this->loginAttempt(['email' => $request->email, 'password' => $request->password]);
        }

        return $this->responseWithError(__('response_messages.reset_token_not_found'), 404);
    }
}
