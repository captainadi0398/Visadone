<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class AssignPermissionController extends Controller
{
    //
    public function index()
    {
        $data['permission'] = DB::table('permissions')->get();
        $data['roles'] = DB::table('roles')
            ->select('roles.*', DB::raw('COUNT(users.id) as user_count'))
            ->leftJoin('users', 'roles.id', '=', 'users.role')
            ->groupBy('roles.id', 'roles.name', 'roles.role_description', 'roles.guard_name', 'roles.created_at', 'roles.updated_at')
            ->get();
        // dd($data);
        return view('layout.roles_permission', $data);
    }

    public function assigned($id)
    {
        $data['permission'] = DB::table('permissions')->get();
        $data['role_id'] = $id;
        $data['assigned_permission'] = DB::table('role_has_permissions')->where('role_id', $id)->get();


        return view('layout.permissions_list', $data);
    }

    public function add()
    {
        $data['permission'] = DB::table('permissions')->get();



        return view('layout.permissions_add', $data);
    }


    public function store(Request $request)
    {
        $requestData = $request->all();

        $role_id = $requestData['role_id'];


        // Remove non-checkbox fields from the data
        unset($requestData['_token']);
        unset($requestData['role_id']);

        // Loop through checkboxes and process them
        foreach ($requestData as $permission_id => $value) {
            // Check if the checkbox was checked (has a value of "on")

            if ($value === 'on') {
                // Perform your database insertion logic here
                DB::table('role_has_permissions')->insert([
                    'role_id' => $role_id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        // Additional code as needed

        return back()->with('message', 'Permissions assigned successfully');
    }

    public function Update(Request $request)
    {
        $requestData = $request->all();

        $role_id = $requestData['role_id'];

        DB::table('role_has_permissions')->where('role_id', $role_id)->delete();


        // Remove non-checkbox fields from the data
        unset($requestData['_token']);
        unset($requestData['role_id']);

        // Loop through checkboxes and process them
        foreach ($requestData as $permission_id => $value) {
            // Check if the checkbox was checked (has a value of "on")

            if ($value === 'on') {
                // Perform your database insertion logic here
                DB::table('role_has_permissions')->insert([
                    'role_id' => $role_id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        // Additional code as needed

        return back()->with('message', 'Permissions assigned successfully');
    }


    public function store_role(Request $request)
    {
        try {
            DB::table('roles')->insert([
                'name' => $request->role_name
            ]);

            return Redirect::route('role.list')->with('message', 'Request processed successfully.');
        } catch (\Exception $e) {
            return('layout.500');
        }
    }
}
