<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Users;
use DB;

class BackEndController extends Controller
{
    //
    public function user_login(Request $request)
    {
        $credentials = $request->only('mobile_number', 'password');
        // dd($credentials);
        if (auth()->attempt($credentials)) {
            // Authentication Passed
            $user = auth()->user(); // Retrieve the authenticated user

            // Create session
            session([
                'id' => $user->id,
                'name' => $user->first_name . " " . $user->last_name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'country_origin' => $user->country,
                'branch_id' => $user->branch_id,
            ]);
            return redirect('/');
        } else {
            // Authentication failed
            return view("layout/login");
        }
    }


    public function user_register()
    {
        return view("layout/register");
    }

    public function user_profile()
    {
        $data['user_details'] = DB::table('users')
            ->where('id', session('id'))
            ->get();
        $data['visa_details_count'] = DB::table('visa_data')
            ->where('user_id', session('id'))
            ->count();
        $data['visa_details'] = DB::table('visa_data')
            ->where('user_id', session('id'))
            ->get();
        $data['visa_last_10'] = DB::table('visa_data')
            ->where('user_id', session('id'))
            ->orderByDesc('id') // Use orderByDesc for descending order
            ->take(10) // Limit the result to the last 10 rows
            ->get();
        $data['visa_today'] = DB::table('visa_data')
            ->where('user_id', session('id'))
            ->whereDate('created_at', now()->toDateString()) // Compare date part
            ->orderByDesc('created_at') // Use orderByDesc for descending order of created_at
            ->get();


        $data['visa_done'] = DB::table('visa_data')->where('user_id', session('id'))->where('visa_status', 'Completed Application')->count();
        $data['visa_archive'] = DB::table('visa_data')->where('user_id', session('id'))->where('visa_status', 'Archive')->count();

        // dd($data);


        return view("layout/user_profile",$data);
    }

    public function logout()
    {
        Auth::logout();

        // Destroy the session
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }


    public function user_register_store(Request $request)
    {
        // dd($request);
        try {
            $messages = [
                'email.unique' => 'User already exists.',
                'mobile_number.unique' => 'User already exists.',
            ];


            $id = Users::create(['first_name' => $request->first_name . " " . $request->last_name, 'email' => $request->email, 'password' => bcrypt($request->password), 'user_type' => 'C', 'status' => 1])->id;
            $user = Users::find($id);

            // $user->login_status = 1;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_number = $request->mobno;
            $user->Country_of_residences = $request->country;
            $user->country = $request->country;
            $user->api_token = bin2hex(random_bytes(60));
            $user->status = 1;
            $user->save();
            $data['success'] = "1";
            $data['message'] = "You have Registered Successfully!";
            $data['userinfo'] = array('user_id' => "$user->id", 'api_token' => $user->api_token, 'user_name' => $user->first_name . " " . $user->last_name, 'mobno' => $user->mobile_number, 'emailid' => $user->email, 'country' => "$user->country", 'password' => $user->password, 'status' => "$user->status", 'timestamp' => date('Y-m-d H:i:s', strtotime($user->created_at)), 'Country of Residences' => $user->country);



            $data1 = array('name' => "Cars In Africa");
            $message = $data['message'];
            $to = $request->emailid;
            $name = $request->first_name . $request->last_name;

            //For Sending Mail After register.
            // $obj = new MailController();
            // $obj->basic_email($to, $name, $message);

            return view('layout.login');
        } catch (\Exception $e) {
            //if Exception Happens 500 Error.
            // dd($e);
            $data['success'] = "0";
            $data['message'] = "Some Thing Went Wrong Try Again";
            $data['userinfo'] = "null";
            return response()->json($data);
        }
    }
}
