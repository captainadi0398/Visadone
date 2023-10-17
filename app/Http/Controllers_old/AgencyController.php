<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use DB;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\MailController;

class AgencyController extends Controller
{
    //
    public function index()
    {
        // dd(Auth::user());

        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $index['country'] = DB::table('country_info')->get();
            $index['currency'] = DB::table('currency_configuration_data')->get();
            $index['branch'] = DB::table('branches')->get();
            $index['data'] = Agency::get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Agency list')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $index['data'] = DB::table('users')
                    ->where('branch_id', $user->group_id)
                    ->where('user_type', 'C')
                    ->get();
                $index['country'] = DB::table('country_info')->get();
                $index['currency'] = DB::table('currency_configuration_data')->get();
                $index['branch'] = DB::table('branches')->get();
                $index['data'] = Agency::where('branch_id', $user->branch_id)->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }

        return view("agency.index", $index);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('currency_configuration_data')->get();
            $data['currency'] = DB::table('currency_configuration_data')->get();
            $data['branch'] = DB::table('branches')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Agency add')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('currency_configuration_data')->get();
                $data['currency'] = DB::table('currency_configuration_data')->get();
                $data['branch'] = DB::table('branches')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }


        // dd($data);
        return view("agency.create", $data);
    }

    public function edit($id)
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
            $data['currency'] = DB::table('currency_configuration_data')->get();
            $data['branch'] = DB::table('branches')->get();
            $data['agency'] = DB::table('agency_data')->where('id', $id)->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Agency edit')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
                $data['currency'] = DB::table('currency_configuration_data')->get();
                $data['branch'] = DB::table('branches')->get();
                $data['agency'] = DB::table('agency_data')->where('id', $id)->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }
        // dd($data);
        return view("agency.view", $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        $password = Str::random(6);
        try {
            $id = Agency::create([
                'country' => $request->country,
                'currency' => $request->currency,
                'branch_name' => $request->branch,
                'agent_role' => $request->role,
                'agency_name' => $request->agency_name,
                'address_line' => $request->agency_address,
                'contact_number_branch' => $request->contact_number,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'status' => $request->status,
            ])->id;

            $object = new MailController();
            $object->sendEmail_agency($request->email,$request->agency_name,$password);

            return redirect('/agency');
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function update(Request $request)
    {
        // dd($request);
        try {
            DB::table('agency_data')
                ->where('id', $request->id) // Specify the condition to select the record(s) to update
                ->update([
                    'country' => $request->country,
                    'currency' => $request->currency,
                    'branch_name' => $request->branch,
                    'agent_role' => $request->role,
                    'agency_name' => $request->agency_name,
                    'address_line' => $request->agency_address,
                    'contact_number_branch' => $request->contact_number,
                    'email' => $request->email,
                    'status' => $request->status,

                ]); // Set the new values for the columns

            return redirect('/agency');
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function filter_out(Request $request)
    {
        // dd($request);
        try {
            $index['country'] = DB::table('country_info')->get();
            $index['currency'] = DB::table('currency_configuration_data')->get();
            $index['branch'] = DB::table('branches')->get();
            $index['data'] = Agency::where('country', $request->country)->orWhere('branch_name', $request->branch)->get();

            return view('agency.index', $index);
        } catch (\Exception $e) {
            return view('layout.500');
        }
    }
}
