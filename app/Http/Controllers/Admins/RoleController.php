<?php

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admins\RoleRequest;
use App\Models\CoreModels\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use RushApp\Core\Controllers\BaseCrudController;

class RoleController extends BaseCrudController
{
    protected string $modelClassController = Role::class;
    protected string|null $storeRequestClass = RoleRequest::class;

    public function store(Request $request)
    {
        $result = parent::store($request);

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
