<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use RushApp\Core\Models\Action;
use RushApp\Core\Models\Language;
use RushApp\Core\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class BaseFeatureTest extends TestCase
{
    protected Language | null $currentLanguage;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('cache:clear');
        $this->currentLanguage = Language::query()->first();
    }

    protected function signIn(Authenticatable | Collection $user = null, string $guard = null)
    {
        $user = $user ?: User::factory()->create();

        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);

        return $this;
    }

    protected function assignAllActionsForSuperAdminUser()
    {
        /** @var Role $role */
        $role = Role::create([
            'name' => 'Admin',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->roles()->save($role);

        foreach ($this->getBaseActions() as $actionName) {
            $action = Action::create([
                'name' => $actionName,
            ]);

            $role->actions()->attach($action->id, [
                'is_owner' => false,
            ]);
        }

        return $this;
    }

    protected function assignActionForAuthenticatedUser(string $actionName, $isOwner = true)
    {
        /** @var Role $role */
        $role = Role::create([
            'name' => 'User',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->roles()->save($role);

        $action = Action::create([
            'name' => $actionName,
        ]);

        $role->actions()->attach($action->id, [
            'is_owner' => $isOwner,
        ]);

        return $this;
    }

    protected function getTranslateTable($entity): string
    {
        return Str::singular($entity).'_translations';
    }

    private function getBaseActions(): array
    {
        return collect(Route::getRoutes()->getRoutes())
            ->map->getName()
            ->filter(fn (?string $name) => Str::startsWith($name, [
                'employees.', 'coordinates.'
            ]))
            ->toArray();
    }
}
