<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function adminsIndex()
    {
        $admins = Admin::with('roles')
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'Super Admin'))
            ->get();

        return view('backend.users.index', compact('admins'));
    }

    public function staffIndex()
    {
        $users = User::with('roles')->latest()->get();

        return view('backend.users.staff', compact('users'));
    }

    public function createAdmin()
    {
        $adminRoles = Role::where('guard_name', 'admin')->get();

        return view('backend.users.create_admin', compact('adminRoles'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $account = Admin::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (! empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $account->assignRole($roles);
        }

        ActivityLog::log('staff_created', "Registered staff account: {$validated['email']}");

        return redirect()->route('admin.users.admins')->with('success', 'Staff account registered successfully.');
    }

    public function editAdmin($id)
    {
        $account = Admin::findOrFail($id);
        $adminRoles = Role::where('guard_name', 'admin')->get();

        return view('backend.users.edit_admin', compact('account', 'adminRoles'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $account = Admin::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:admins,email,'.$id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $data = ['email' => $validated['email']];
        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $account->update($data);

        $roles = Role::whereIn('id', $request->roles ?? [])->get();
        $account->syncRoles($roles);

        ActivityLog::log('staff_updated', "Updated staff account: {$validated['email']}");

        return redirect()->route('admin.users.admins')->with('success', 'Staff account updated successfully.');
    }

    public function create()
    {
        $userRoles = Role::where('guard_name', 'web')->get();

        return view('backend.users.create', compact('userRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $account = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (! empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $account->assignRole($roles);
        }

        ActivityLog::log('customer_created', "Registered customer: {$validated['email']}");

        return redirect()->route('admin.users.staff')->with('success', 'Customer registered successfully.');
    }

    public function edit($type, $id)
    {
        // Only customer (user) accounts are editable from the panel
        $account = User::findOrFail($id);
        $roles = Role::where('guard_name', 'web')->get();
        $type = 'user';

        return view('backend.users.edit', compact('account', 'type', 'roles'));
    }

    public function update(Request $request, $type, $id)
    {
        // Only customer (user) accounts are updatable from the panel
        $account = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $account->update($data);

        $roles = Role::whereIn('id', $request->roles ?? [])->get();
        $account->syncRoles($roles);

        ActivityLog::log('customer_updated', "Updated customer: {$validated['email']}");

        return redirect()->route('admin.users.staff')->with('success', 'Customer updated successfully.');
    }

    public function destroy($type, $id)
    {
        // Only customer (user) accounts can be deleted from the panel
        $account = User::findOrFail($id);
        $email = $account->email;
        $account->delete();

        ActivityLog::log('customer_deleted', "Deleted customer: {$email}");

        return redirect()->route('admin.users.staff')->with('success', 'Customer deleted successfully.');
    }
}
