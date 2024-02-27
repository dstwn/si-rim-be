<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::query();
        $groups->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });
        $groups->when($request->is_internal, function ($query) use ($request) {
            return $query->where('is_internal', $request->is_internal);
        });
        $groups->withCount('users');
        $groups = $groups
            ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $groups
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:groups'],
            'is_internal' => ['required', 'boolean']
        ]);
        $group = Group::create([
            'name' => $request->name,
            'is_internal' => $request->is_internal,
        ]);
        return response()->json([
            'status' => true,
            'data' => $group
        ]);
    }

    public function show($id)
    {
        $group = Group::where('id', $id)->with('users')->first();
        if (!$group) {
            return response()->json([
                'status' => false,
                'data' => [
                    'message' => 'Group not found'
                ]
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $group
        ]);
    }

    public function update(Request $request, $id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                'status' => false,
                'data' => [
                    'message' => 'Group not found'
                ]
            ], 404);
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:groups,name,' . $id],
            'is_internal' => ['required', 'boolean']
        ]);
        $group->name = $request->name;
        $group->is_internal = $request->is_internal;
        $group->save();
        return response()->json([
            'status' => true,
            'data' => $group
        ]);
    }

    public function destroy($id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                'status' => false,
                'data' => [
                    'message' => 'Group not found'
                ]
            ], 404);
        }
        $group->delete();
        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'Group deleted'
            ]
        ]);
    }
}
