<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('layouts.admin.role.index', compact('roles'));  // sửa roles -> role
    }

    public function create()
    {
        return view('layouts.admin.role.create'); // sửa roles -> role
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('layouts.admin.role.edit', compact('role'));  // sửa roles -> role
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        Role::destroy($id);
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
