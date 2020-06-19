<?php

namespace pierresilva\AccessControl;

use Illuminate\Http\Request;
use pierresilva\AccessControl\Models\Role;
use Illuminate\Contracts\Auth\Guard;

class AccessControl
{
    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new UserHasPermission instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Checks if user has the given permissions.
     *
     * @param array|string $permissions
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function can($permissions)
    {
        $request = request();

        $hasPermission = false;

        if ($this->auth->check()) {
            $hasPermission = $this->auth->user()->can($permissions);
        }

        if (! $hasPermission && $request->ajax()) {
            return response()->json([
                'message' => 'Not authorized'
            ], 401);
        }

        return $hasPermission;
    }

    /**
     * Checks if user has the given permissions.
     *
     * @param array|string $permissions
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function userCan($permissions)
    {
        $request = request();

        $hasPermission = false;

        if ($this->auth->check()) {
            $hasPermission = $this->auth->user()->canAtLeast($permissions);
        }

        if (! $hasPermission && $request->ajax()) {
            return response()->json([
                'message' => 'Not authorized'
            ], 401);
        }

        return $hasPermission;
    }

    /**
     * Checks if user has at least one of the given permissions.
     *
     * @param Request $request
     * @param array $permissions
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function canAtLeast($permissions)
    {
        $request = request();

        $hasPermission = false;

        if ($this->auth->check()) {
            $hasPermission = $this->auth->user()->canAtLeast($permissions);
        }

        if (! $hasPermission && $request->ajax()) {
            return response()->json([
                'message' => 'Not authorized'
            ], 401);
        }

        return false;
    }

    /**
     * Checks if user is assigned the given role.
     *
     * @param Request $request
     * @param $role
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function isRole($role)
    {
        $request = request();

        $hasRole = false;

        if ($this->auth->check()) {
            $hasRole = $this->auth->user()->isRole($role);
        }

        if (! $hasRole && $request->ajax()) {
            return response()->json([
                'message' => 'Not authorized'
            ], 401);
        }

        return $hasRole;
    }

    /**
     * Checks if user is assigned the given role.
     *
     * @param Request $request
     * @param $role
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function userIs($role)
    {
        $request = request();

        $hasRole = false;

        if ($this->auth->check()) {
            $hasRole = $this->auth->user()->isRole($role);
        }

        if (! $hasRole && $request->ajax()) {
            return response()->json([
                'message' => 'Not authorized'
            ], 401);
        }

        return $hasRole;
    }
}
