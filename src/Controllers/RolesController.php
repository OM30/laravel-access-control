<?php

namespace pierresilva\AccessControl\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use pierresilva\AccessControl\Models\Role;
use pierresilva\AccessControl\Models\Permission;


class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'message' => 'Role list obtained',
            'data' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles',
            'description' => 'nullable|string',
            'special' => 'nullable|string|in:all-access,no-access'
        ]);

        $slug = Str::slug($request->get('name'), '.');
        $request->request->add(['slug' => $slug]);

        if ($validation->fails()) {
            return \response()->json([
                'message' => 'Validation fails',
                'errors' => $validation->errors()
            ], 422);
        }

        $role = Role::create($request->all());

        $defaultPermissions = [
            'list', 'view', 'create', 'update', 'delete'
        ];

        if ($request->get('create_default_permissions') === true) {
            $createdPermissions = [];
            foreach ($defaultPermissions as $permission) {
                $thisPermission = Permission::create([
                    'name' => $role->name . ' ' . $permission,
                    'slug' => $role->slug . ':' . $permission
                ]);

                $createdPermissions[] = $thisPermission->id;
            }
            $role->syncPermissions($createdPermissions);
        }

        return response()->json([
            'message' => 'Role created',
            'data' => [
                'role' => $role,
                'permissions' => $role->getPermissions()
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $roleSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($roleSlug)
    {
        $role = Role::with('permissions')->where('slug', $roleSlug)->firstOrFail();

        return response()->json([
            'message' => 'Role obtained',
            'data' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $roleSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $roleSlug)
    {
        $role = Role::with('permissions')->where('slug', $roleSlug)->firstOrFail();

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'special' => 'nullable|string|in:all-access,no-access'
        ]);

        if ($validation->fails()) {
            return \response()->json([
                'message' => 'Validation fails',
                'errors' => $validation->errors()
            ], 422);
        }

        $slug = Str::slug($request->get('name'), '.');
        $request->request->add(['slug' => $slug]);

        $role->update($request->all());

        if ($request->get('permissions') && is_array($request->get('permissions'))) {
            foreach ($request->get('permissions') as $permission) {
                if (isset($permission['id'])){
                    $role->assignPermission($permission['id']);
                }
            }
        }

        return response()->json([
            'message' => 'Role updated',
            'data' => [
                'role' => $role,
                'permissions' => $role->getPermissions()
            ]
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $roleSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($roleSlug)
    {
        //
        $role = Role::with('permissions')->where('slug', $roleSlug)->firstOrFail();

        $role->delete();

        foreach ($role->permissions as $permission) {
            Permission::find($permission->id)->delete();
        }

        return response()->json([
            'message' => 'Role deleted',
            'data' => [
                'role' => $role,
            ]
        ]);

    }

    /**
     * @param $roleId
     * @param $permissionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignPermission($roleId, $permissionId)
    {
        $role = Role::where('slug', $roleId)->firstOrFail();
        $role->assignPermission($permissionId);

        return response()->json([
            'message' => 'Permission assigned to role',
            'data' => [
                'role' => $role,
            ]
        ]);
    }
}
