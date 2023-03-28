<?php

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admins\AccessLevelControl\StoreAccessLevelControlRequest;
use App\Http\Requests\Admins\AccessLevelControl\UpdateAccessLevelControlRequest;
use App\Models\CoreModels\Action;
use App\Models\CoreModels\RoleAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use RushApp\Core\Controllers\BaseCrudController;

class AccessLevelControlController extends BaseCrudController
{
    protected string $modelClassController = RoleAction::class;
    protected string|null $storeRequestClass = StoreAccessLevelControlRequest::class;
    protected string|null $updateRequestClass = UpdateAccessLevelControlRequest::class;

    protected array $withRelationNames = ['action'];

    public function index(Request $request)
    {
        return $this->successResponse(
            $this->baseModel->getRoutesWithActions($request->all(), $this->withRelationNames)
        );
    }

    public function store(Request $request)
    {
        $result = $this->successResponse(
            $this->baseModel->createRoleAction($request->all())
        );

        $this->cacheClear();

        return $result;
    }

    public function update(Request $request)
    {
        $result = parent::update($request);

        $this->cacheClear();

        return $result;
    }

    public function destroy(Request $request)
    {
        $roleAction = RoleAction::find($this->getEntityId());
        $action = Action::find($roleAction->action_id);

        $action->deleteOne($action->id, Auth::id());

        $this->cacheClear();

        return $this->successResponse([
            'message' => __('response_messages.deleted')
        ]);
    }

    private function cacheClear(): void
    {
        Artisan::call('optimize');
        Artisan::call('cache:clear');
    }
}
