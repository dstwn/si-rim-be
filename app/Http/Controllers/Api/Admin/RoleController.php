<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query();
        $roles->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });
        $roles->when($request->is_internal, function ($query) use ($request) {
            return $query->where('is_internal', $request->is_internal);
        });
        $roles = $roles
            ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $roles
        ]);
    }
}
