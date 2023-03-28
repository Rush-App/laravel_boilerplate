<?php

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admins\UserManagement\StoreUserManagementRequest;
use App\Http\Requests\Admins\UserManagement\UpdateUserManagementRequest;
use App\Models\Admins\UserManagement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use RushApp\Core\Controllers\BaseCrudController;

class UserManagementController extends BaseCrudController
{
    protected string $modelClassController = UserManagement::class;
    protected array $withRelationNames = ['user_role'];
    protected string|null $storeRequestClass = StoreUserManagementRequest::class;
    protected string|null $updateRequestClass = UpdateUserManagementRequest::class;

    public function index(Request $request): JsonResponse
    {
        return $this->successResponse($this->baseModel->getUsers($request->all(), $this->withRelationNames));
    }

    public function update(Request $request)
    {
        $result = parent::update($request);
        $this->baseModel->changeUserRoles($request, $this->getEntityId());

        $this->cacheClear();

        return $result;
    }

    public function store(Request $request): JsonResponse
    {
        $this->validateRequest($request, $this->storeRequestClass);
        $requestParameters = $request->all();
        $requestParameters['usage_policy'] = true;

        $result = $this->baseModel->saveUser($requestParameters);

        $this->cacheClear();

        return $this->successResponse($result);
    }

    public function archiveAccount(Request $request): JsonResponse
    {
        return $this->successResponse($this->baseModel->archiveAccount($request->user_id));
    }

    public function restoreAccount(Request $request): JsonResponse
    {
        return $this->successResponse($this->baseModel->restoreAccount($request->user_id));
    }

    public function destroy(Request $request)
    {
        $result = parent::destroy($request);

        $this->cacheClear();

        return $result;
    }

    private function cacheClear(): void
    {
        Artisan::call('optimize');
        Artisan::call('cache:clear');
    }
}
