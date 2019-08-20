<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Validator;

class RoleController extends Controller
{
    /**
     * RoleController constructor. Calls parent Controller constructor to set / check permissions on methods.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request){
        parent::__construct($request, 'role');
    }
    
    /**
     * Display a listing of Roles, inc. search function on Role name or display_name fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::get();

        return view('admin.roles.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create', [
            'role' => new Role()
        ]); 
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|alpha_dash|unique:roles,name',
            'display_name' => 'required|alpha_spaces',
            'description' => 'required'
        ];
        $messages = [
            'name.alpha_dash' => 'The Role Name may only contain letters, dashes (-) and underscores (_).',
            'name.unique' => 'The Role Name you have entered is already in use. Please enter another one.',
            'display_name.alpha_spaces' => 'The Role Display Name may only contain letters or spaces.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
            return redirect()->back()->with('error', 'There was an error with your form submission, please review below.')->withInput()->withErrors($validator);

        $role = Role::create([
            'name' => strtolower($request->get('name')),
            'display_name' => $request->get('display_name'),
            'description' => $request->get('description'),
        ]);

        return redirect()->route('roles.index')->with('success', $role->display_name . ' has been created successfully.');
    }

    /**
     * Display the specified Role.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->perms = $role->permissions;
        
        return view('admin.roles.show', [
            'role' => $role
        ]);
    }

    /**
     * Show the form for editing the specified Role.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $role->perms = $role->permissions;
        
        return view('admin.roles.edit', [
            'role' => $role,
            'perms' => Permission::whereNotIn('id', $role->perms->pluck('id')->toArray())->get()->pluck('display_name', 'id')->toArray()
        ]);
    }

    /**
     * Update the specified Role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required|alpha_dash|unique:roles,name,' . $role->id,
            'display_name' => 'required|alpha_spaces',
            'description' => 'required'
        ];
        $messages = [
            'name.alpha_dash' => 'The Role Name may only contain letters, dashes (-) and underscores (_).',
            'name.unique' => 'The Role Name you have entered is already in use. Please enter another one.',
            'display_name.alpha_spaces' => 'The Role Display Name may only contain letters or spaces.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
            return redirect()->back()->with('error', 'There was an error with your form submission, please review below.')->withInput()->withErrors($validator);

        $role->update([
            'name' => strtolower($request->get('name')),
            'display_name' => $request->get('display_name'),
            'description' => $request->get('description'),
        ]);

        return redirect()->route('roles.index')->with('success', $role->display_name . ' has been updated successfully.');
    }
    
    /**
     * Add or Remove the specified Permission from a specified Role
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role $role
     * @return array
     */
    public function add_remove_permission(Request $request, Role $role)
    {
        $permission = Permission::find($request->get('permission_id'));

        if ($role === null || $permission === null)
            return ['success' => 'false', 'msg' => 'cannot find role or permission'];

        switch ($request->get('action')) {
            case 'add':
                $role->givePermissionTo($permission);
                break;
            case 'remove':
                $role->revokePermissionTo($permission);
                break;
        }

        return [
            'success' => 'true', 
            'perm' => [
                'id' => $permission->id,
                'display_name' => $permission->display_name
            ]
        ];
    }

    /**
     * Archive / Soft Delete a Role.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $name = $role->display_name;

        if (auth()->user()->hasRole($role->name))
            return redirect()->route('roles.index')->with('message', 'You cannot archive the ' . $name . ' role, as you are a member');

        $role->delete();

        return redirect()->route('roles.index')->with('success', $name . ' has been archived.');
    }

    /**
     * Display a listing of the Archived / Soft Deleted Roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request)
    {
        $roles = Role::onlyTrashed()->get();

        return view('admin.roles.archive', [
            'roles' => $roles,
            'active_roles' => ['' => 'No Role Required'] + Role::get()->pluck('display_name', 'id')->toArray()
        ]);
    }

    /**
     * Un-archive / remove Soft Delete from the chosen Role in storage.
     *
     * @param int $role_id
     * @return \Illuminate\Http\Response
     */
    public function restore($role_id){
        $role = Role::where('id', $role_id)->withTrashed()->first();
        if($role == null)
            return redirect()->back()->with('message', 'The chosen Role cannot be found.');

        $role->restore();

        return redirect()->back()->with('success', $role->display_name.' was successfully restored.');
    }

    /**
     * Permanently delete the chosen Role from storage.
     * Detaches Users from deleted Role
     * Assigns Users to new Role if provided via request->new_role_id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $role_id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $role_id){
        $role = Role::where('id', $role_id)->withTrashed()->first();
        if($role == null)
            return redirect()->back()->with('message', 'That Role cannot be found.');
        
        $name = $role->display_name;
        
        $new_role = Role::where('id', $request->new_role_id)->first();

        $users = User::role($role)->get();
        $users->filter(function($user) use ($role, $new_role){
            $user->removeRole($role);
            if($new_role != null)
                $user->assignRole($new_role);
        });

        $role->forceDelete();

        return redirect()->back()->with('success', $name.' was permanently deleted.');
    }
}
