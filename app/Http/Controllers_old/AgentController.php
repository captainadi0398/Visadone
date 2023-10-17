<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use DB;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\MailController;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $index['data'] = Agent::get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Agent list')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $index['data'] = Agent::where('branch_id', $user->branch_id)->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }

        // dd($index);
        return view("agent.index", $index);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
            $data['agency'] = DB::table('agency_data')->get();
            $data['branch'] = DB::table('branches')->get();
            $data['agency'] = DB::table('agency_data')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Agent add')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
                $data['agency'] = DB::table('agency_data')->get();
                $data['branch'] = DB::table('branches')->get();
                $data['agency'] = DB::table('agency_data')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }
        //  $data['agent'] = DB::table('agency_data')->where('id', $id)->get();
        // dd($data);
        return view("agent.create", $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $password = Str::random(6);
            $id = Agent::create([
                'country' => $request->country,
                'branch_id' => $request->branch,
                'agency_id' => $request->agency,
                'agent_firstname' => $request->firstname,
                'agent_lastname' => $request->firstname,
                'Email' => $request->email,
                'contact_number' => $request->contact_number,
                'password'=>bcrypt($password),
                'role' => $request->role,
                'status' => $request->status,
            ])->id;

            $object = new MailController();
            $object->sendEmail_agent($request->email,$request->firstname,$password);

            return redirect('/agents');
        } catch (\Exception $e) {
            dd($e);
            return view("layout.500");
        }
    }

    public function edit($id)
    {
        try {
            $data['country'] = DB::table('country_info')->get();
            $data['currency'] = DB::table('currency_configuration_data')->get();
            $data['branch'] = DB::table('branches')->get();
            $data['agency'] = DB::table('agency_data')->get();
            $data['agent'] = DB::table('agent_data')->where('id', $id)->get();
            // dd($data);
            return view("agent.view", $data);
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function update(Request $request)
    {
        // dd($request);
        try {
            DB::table('agent_data')
                ->where('id', $request->id) // Specify the condition to select the record(s) to update
                ->update([
                    'country' => $request->country,
                    'branch_id' => $request->branch,
                    'agency_id' => $request->agency,
                    'agent_firstname' => $request->firstname,
                    'agent_lastname' => $request->firstname,
                    'Email' => $request->email,
                    'contact_number' => $request->contact_number,
                    'role' => $request->role,
                    'status' => $request->status,

                ]); // Set the new values for the columns

            return redirect('/agents');
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }
}
