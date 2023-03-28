<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\UserAvatarRequest;
use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RushApp\Core\Controllers\BaseCrudController;

class UserController extends BaseCrudController
{
    /**
     * the name of the model must be indicated in each controller
     * @var string
     */
    protected string $modelClassController = User::class;
    protected string $requestClassController = UserRequest::class;

    public function getPersonalData(): JsonResponse
    {
        return $this->successResponse(User::find(Auth::id()));
    }

    public function updatePersonalData(Request $request): JsonResponse
    {
        $this->validateRequest($request, UserRequest::class);

        return $this->successResponse($this->getBaseModel()->updatePersonalData($request));
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $this->validateRequest($request, UserAvatarRequest::class);

        return $this->successResponse([
            'message' => $this->getBaseModel()->updateAvatar($request->user_avatar_file)
        ]);
    }
}
