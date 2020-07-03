<?php

namespace pierresilva\AccessControl\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use pierresilva\AccessControl\Models\Role;
use pierresilva\AccessControl\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $permissions = Permission::with('roles')->get();

        return response()->json([
            'message' => 'Permissions list obtained',
            'data' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
            'description' => 'nullable|string',
            'role' => 'nullable|string|exists:roles,slug'
        ]);

        $slug = Str::slug($request->get('name'), '.');
        $request->request->add(['slug' => $slug]);

        if ($validation->fails()) {
            return \response()->json([
                'message' => 'Validation fails',
                'errors' => $validation->errors()
            ], 422);
        }

        $permission = Permission::create($request->all());

        $role = null;
        if ($request->get('role')) {
            $role = Role::where('slug', $request->get('role'))->firstOrFail();
            $permission->assignRole($role->id);
        }

        return response()->json([
            'message' => 'Permission created',
            'data' => [
                'permission' => $permission
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $permissionSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $permissionSlug)
    {
        //
        $permission = Permission::with('roles')->where('slug', $permissionSlug)->firstOrFail();

        return response()->json([
            'message' => 'Permission obtained',
            'data' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $permissionSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $permissionSlug)
    {
        //
        $permission = Permission::with('roles')->where('slug', $permissionSlug)->firstOrFail();

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string',
            'role' => 'nullable|string|exists:roles,slug'
        ]);

        if ($validation->fails()) {
            return \response()->json([
                'message' => 'Validation fails',
                'errors' => $validation->errors()
            ], 422);
        }

        $slug = Str::slug($request->get('name'), '.');
        $request->request->add(['slug' => $slug]);

        $permission->update($request->all());

        $role = null;
        if ($request->get('role')) {
            $role = Role::where('slug', $request->get('role'))->firstOrFail();
            $permission->assignRole($role->id);
        }

        return response()->json([
            'message' => 'Permission updated',
            'data' => [
                'permission' => $permission
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $permissionSlug
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $permissionSlug)
    {
        //
        $permission = Permission::with('permissions')->where('slug', $permissionSlug)->firstOrFail();

        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted',
            'data' => [
                'permission' => $permission,
            ]
        ]);
    }
}
