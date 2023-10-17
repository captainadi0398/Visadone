<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use DB;
use Illuminate\Http\Request;
use Auth;

class BranchController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $index['data'] = Branch::get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Branch list')
                ->exists();

            // dd($hasPermission);

            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $index['data'] = Branch::where('id', $user->branch_id)->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }

        // dd($index);
        return view("branchs.index", $index);
    }

    public function create()
    {
        $user = Auth::user();
        if ( $user->user_type == "S") {
            $data['country'] = DB::table('currency_configuration_data')->get();
            $data['currency'] = DB::table('currency_configuration_data')->get();
            $data['branch'] = DB::table('branches')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Branch add')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('currency_configuration_data')->get();
                $data['currency'] = DB::table('currency_configuration_data')->get();
                $data['branch'] = DB::table('branches')->where('id', $user->branch_id)->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }

        // dd($index);
        return view("branchs.create", $data);
    }

    public function edit($id)
    {
        $data['country'] = DB::table('country_info')->get();
        $data['currency'] = DB::table('currency_configuration_data')->get();
        $data['branch'] = DB::table('branches')->where('id', $id)->get();
        $data['branch_bank'] = DB::table('branch_bank_data')->where('branch_id', $id)->get();

        // dd($data);
        return view("branchs.view", $data);
    }


    public function store(Request $request)
    {
        // dd($request);
        try {
            $branch = DB::table('branches')->insertGetId([
                'country_name' => $request->country,
                'name' => $request->branch_name,
                'contact_number_branch' => $request->contact_number,
                'branch_address' => $request->branch_address,
                'active' => $request->status,
            ]);

            // dd($branch);

            $bank_details = DB::table('branch_bank_data')->insertGetId([
                'branch_id' => $request->branch,
                'branch_bank_number' => $request->branch_bank,
                'branch_bank_city' => $request->country_code,
            ]);

            return redirect('/branch');
        } catch (\Exception $e) {
            // Handle the exception appropriately, e.g., log it or show an error page.
            // dd($e);
            return view("layout.500");
        }
    }


    public function update(Request $request, $id)
    {
        // dd($request);
        try {
            DB::table('branches')->where('id', $id)->update([
                'country_name' => $request->country,
                'name' => $request->branch_name,
                'contact_number_branch' => $request->contact_number,
                'branch_address' => $request->branch_address,
                'active' => $request->status,
            ]);

            DB::table('branch_bank_data')->where('branch_id', $id)->update([
                'branch_bank_number' => $request->branch_bank,
                'branch_bank_city' => $request->country_code,
            ]);

            return redirect('/branch');
        } catch (\Exception $e) {
            // Handle the exception appropriately, e.g., log it or show an error page.
            // dd($e);
            return view("layout.500");
        }
    }
}
