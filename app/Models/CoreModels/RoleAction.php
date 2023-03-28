<?php

namespace App\Models\CoreModels;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use RushApp\Core\Models\Action;
use RushApp\Core\Models\BaseModel;

class RoleAction extends BaseModel
{
    public $table = 'role_action';

    protected $fillable = ['action_id', 'role_id', 'is_owner'];

    public $timestamps = false;

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @param array $requestParameters
     * @param array $withRelationNames
     * @return Collection
     *
     */
    public function getRoutesWithActions(array $requestParameters, array $withRelationNames): Collection
    {
        $allRoutes = Route::getRoutes()->getRoutes();
        $allRoleAction = $this->getQueryBuilder($requestParameters, $withRelationNames)->get();

        $routesWithActions = collect();

        foreach ($allRoutes as $route) {
            $routeMiddleware = $route->middleware();
            if (!empty($routeMiddleware) && in_array('core.check-user-action', $routeMiddleware)) {
                $routeAction = $route->getAction();
                $action = $allRoleAction->where('action.name', $routeAction['as'])->first();

                if (!empty($action)) {
                    $model = $route->getController()->getBaseModel();
                    $action->{'is_owner_key'} = in_array($this->getOwnerKey(), $model->getFillable());
                } else {
                    $action = false;
                }

                $actionName = substr($routeAction['as'], strpos($routeAction['as'], ".") + 1);
                $routeName = strstr($routeAction['as'], '.', true);

                $routeWithAction = $routesWithActions->where('route_name', $routeName)->first();
                if (!empty($routeWithAction)) {
                    $routeWithAction->put($actionName, $action);
                } else {
                    $routesWithActions->push(collect([
                        'route_name' => $routeName,
                        $actionName => $action,
                    ]));
                }
            }
        }

        return $routesWithActions;
    }

    /**
     * @param array $requestParameters
     * @return mixed
     */
    public function createRoleAction(array $requestParameters)
    {
        $action = Action::create([
            'name' => $requestParameters['action_name'],
        ]);

        $requestParameters['action_id'] = $action->id;
        $requestParameters['is_owner'] = false;

        return $this->create($requestParameters);
    }
}
