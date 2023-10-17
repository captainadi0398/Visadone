<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Branch;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\MailController;

class UserController extends Controller
{
    //
    public function __construct()
    {

        // $this->middleware(['role:Admin']);
        // $this->middleware('permission:Users add',['only' => ['create']]);
        // $this->middleware('permission:Users edit',['only' => ['edit']]);
        // $this->middleware('permission:Users delete',['only' => ['bulk_delete', 'destroy']]);
        // $this->middleware('permission:Users list');
    }

    public function index()
    {
        $user = Auth::user();
        // dd($user);
        if ($user->branch_id == null || $user->user_type == "S") {
            // $index['data'] = Users::whereUser_type("C")->orWhere('user_type', 'S')->get();
            $index['data'] = Users::get();
            $index['branch'] = Branch::get();
            $index['roles'] = DB::table('roles')->get();
            $index['country'] = DB::table('country_info')->get();
            $index['currency'] = DB::table('currency_configuration_data')->get();
            $index['branch'] = DB::table('branches')->get();
            $index['agency'] = Agency::get();
        } else {

            // Check for permission based on role_id
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Users list')
                ->exists();



            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $index['data'] = DB::table('users')
                    ->where('branch_id', $user->group_id)
                    // ->where('user_type', 'C')
                    ->get();
                $index['branch'] = Branch::get();
                $index['country'] = DB::table('country_info')->get();
                $index['currency'] = DB::table('currency_configuration_data')->get();
                $index['branch'] = DB::table('branches')->get();
                $index['agency'] = Agency::get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }

            // Fetch data based on user's group_id and user_type 'O'

        }
        // dd($index);
        return view("users.index", $index);
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();

        } else {

            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Users add')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'

                $data['country'] = DB::table('country_info')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }

        // dd($data);
        $index['branch'] = Branch::get();
            $data['roles'] = DB::table('roles')->get();
            $data['country'] = DB::table('country_info')->get();
            $data['currency'] = DB::table('currency_configuration_data')->get();
            $data['branches'] = DB::table('branches')->get();
            $data['agency'] = Agency::get();
        return view("users.create", $data);
    }

    public function edit($id)
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
            $data['user'] = DB::table('users')->where('id', $id)->get();
            $data['branch'] = DB::table('branches')->get();
            $data['user_type_data'] = DB::table('user_type_data')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Users edit')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
                $data['user'] = DB::table('users')->where('id', $id)->get();
                $data['branch'] = DB::table('branches')->get();
                $data['user_type_data'] = DB::table('user_type_data')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }
        // dd($data);
        return view("users.view", $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $password = bcrypt(bin2hex(random_bytes(6)));
            $id = Users::create(['first_name' => $request->first_name . " " . $request->last_name, 'email' => $request->email, 'password' => $password, 'user_type' => 'C', 'status' => 1])->id;
            $user = Users::find($id);

            // $user->login_status = 1;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_number = $request->mobno;
            $user->Country_of_residences = $request->nam;
            $user->country = $request->country;
            $user->api_token = bin2hex(random_bytes(60));
            $user->status = 1;
            $user->save();

            $data['country'] = DB::table('country_info')->get();

            $object = new MailController();
            $object->sendEmail($request->email,$request->agency_name,$password);

            return redirect('/users');
        } catch (\Expection $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function update(Request $request)
    {
        // dd($request);
        try {
            DB::table('users')
                ->where('id', $request->id) // Specify the condition to select the record(s) to update
                ->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'mobile_number' => $request->mobile_number,
                    'Country_of_residences' => $request->Country_of_residences,
                    'role' => $request->user_role,
                    'branch_id' => $request->branch,
                    'status' => $request->status
                ]); // Set the new values for the columns

            return redirect('/users');
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }
}
