<?php

namespace App\Models\Admins;

use App\Exceptions\CustomHttpException;
use App\Models\CoreModels\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RushApp\Core\Enums\ModelRequestParameters;
use RushApp\Core\Models\BaseModel;
use RushApp\Core\Services\LoggingService;

class UserManagement extends BaseModel
{
    public $table = 'users';

    protected $fillable = ['name', 'usage_policy', 'password', 'email', 'is_deleted'];

    /** @var string[] - for WHERE_LIKE */
    protected array $searchable = ['name', 'email'];

    protected static string $userArchiveSeparatorForEmail = '___';

    public function user_role(): HasMany
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    public function getUsers(array $requestParameters, array $withRelationNames)
    {
        $query = $this->getQueryBuilder($requestParameters, $withRelationNames);

        $query->whereRelation('user_role', function ($query) use ($requestParameters) {
            if (!empty($requestParameters['role_ids'])) {
                $query->whereIn('role_id', explode(',', $requestParameters['role_ids']));
            }
        });

        if (array_key_exists(ModelRequestParameters::PAGINATE, $requestParameters)) {
            $users = $query->paginate($requestParameters[ModelRequestParameters::PAGINATE]);
            $this->addRoleIdsToUsers($users->items());

            return $users;
        }

        return $this->addRoleIdsToUsers($query->get());
    }

    private function addRoleIdsToUsers(Collection|array $users): Collection|array
    {
        foreach ($users as &$user) {
            $user->{'user_roles_ids'} = $user->user_role->pluck('role_id')->toArray();
        }

        return $users;
    }

    /**
     * @param array $requestParameters
     * @return mixed
     */
    public function saveUser(array $requestParameters): mixed
    {
        try {
            $requestParameters['password'] = Hash::make($requestParameters['password']);
            $user = $this->create($requestParameters);
            foreach ($requestParameters['user_roles_ids'] as $role_id) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role_id,
                ]);
            }
            return $user;
        } catch (\Exception $e) {
            LoggingService::critical('Cant save a new user - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.save_error'));
        }
    }

    /**
     * @param int $userId
     * @return string
     */
    public function archiveAccount(int $userId): string
    {
        $user = User::find($userId);

        try {
            $user->update([
                'email' => uniqid() . self::$userArchiveSeparatorForEmail . $user->email,
                'is_deleted' => true,
            ]);
            return __('response_messages.user_account_archived');
        } catch (\Exception $e) {
            LoggingService::critical('Cant archive user account - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.delete_error'));
        }
    }

    /**
     * @param int $userId
     * @return string
     */
    public function restoreAccount(int $userId): string
    {
        $user = User::find($userId);
        $originalEmail = substr(
            strstr($user->email, self::$userArchiveSeparatorForEmail),
            strlen(self::$userArchiveSeparatorForEmail),
            strlen($user->email)
        );

        try {
            $user->update([
                'email' => $originalEmail,
                'is_deleted' => false,
            ]);
            return __('response_messages.user_account_unarchived');
        } catch (\Exception $e) {
            LoggingService::critical('Cant unarchived user account - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.save_error'));
        }
    }

    /**
     * Adding or deleting new user roles (depends on roleIds from frontend)
     * @param Request $request
     * @param int $firstRoutParameter
     */
    public function changeUserRoles(Request $request, int $firstRoutParameter): void
    {
        $entityId = $request->route($this->getTableSingularName());
        $entityId = !empty($entityId) ? $entityId : $firstRoutParameter;

        $roleIdsToDelete = [];
        $roleIdsToAdd = [];

        $oldUserRoles = UserRole::where('user_id', $entityId)->get();
        $oldUserRolesIds = $oldUserRoles->pluck('role_id')->toArray();
        $newUserRoleIds = $request->user_roles_ids;

        foreach ($oldUserRolesIds as $oldUserRoleId) {
            if (!in_array($oldUserRoleId, $newUserRoleIds)) {
                $roleIdsToDelete[] = $oldUserRoleId;
            }
        }
        foreach ($newUserRoleIds as $newUserRoleId) {
            if (!in_array($newUserRoleId, $oldUserRolesIds)) {
                $roleIdsToAdd[] = $newUserRoleId;
            }
        }

        try {
            foreach ($roleIdsToDelete as $roleIdToDelete) {
                $oldUserRole = $oldUserRoles->where('role_id', $roleIdToDelete)->first();
                $oldUserRole->delete();
            }
            foreach ($roleIdsToAdd as $roleIdToAdd) {
                UserRole::create([
                    'user_id' => $entityId,
                    'role_id' => $roleIdToAdd,
                ]);
            }
        } catch (\Exception $e) {
            LoggingService::critical('Cant save new user roles - ' . $e->getMessage());
        }
    }
}
