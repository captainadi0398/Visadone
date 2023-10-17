<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    //
    public function index()
    {
        $data['data'] = DB::table('roles')->get();

        return view('roles.index', $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'role_description' => 'nullable|string',
                'guard_name' => 'required|string',
            ]);

            DB::table('roles')->insert($validatedData);
            return redirect('/user_role');
        } catch (\Exception $e) {
            dd($e);
        }

        // return response()->json(['message' => 'Role created successfully'], 201);

    }
}
