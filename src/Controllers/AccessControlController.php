<?php

namespace pierresilva\AccessControl\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccessControlController extends Controller
{

    /**
     * @param $roleIdOrSlug
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRole($roleIdOrSlug, $userId)
    {
        $user = \App\User::findOrFail($userId);

        $role = \pierresilva\AccessControl\Models\Role::findOrFail($roleIdOrSlug);

        $user->assignRole($role->id);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @param $roleIdOrSlug
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeRole($roleIdOrSlug, $userId)
    {
        $user = \App\User::findOrFail($userId);

        $role = \pierresilva\AccessControl\Models\Role::findOrFail($roleIdOrSlug);

        $user->revokeRole($role->id);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncRoles(Request $request, $userId)
    {
        $user = \App\User::findOrFail($userId);

        $user->syncRoles($request->get('role_ids'));

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeAllRoles($userId)
    {
        $user = \App\User::findOrFail($userId);

        $user->revokeAllRoles($user);

        return response()->json([
            'user' => $user
        ]);
    }

}
