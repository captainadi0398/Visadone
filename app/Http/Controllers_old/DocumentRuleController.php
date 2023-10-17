<?php

namespace App\Http\Controllers;

use App\Models\DocumentRules;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;


class DocumentRuleController extends Controller
{
    //

    // function __construct()
    // {
    //      $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
    //      $this->middleware('permission:product-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    // }

    public function index()
    {
        $user = Auth::user();
        // dd($user);
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Documents list')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }
        // dd($data);
        return view("Document_rule.index", $data);
    }

    public function create()
    {

        $user = Auth::user();
        // dd($user);
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
            $data['document'] = DB::table('document_list')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Documents add')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
                $data['document'] = DB::table('document_list')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }
        // dd($data);
        return view("Document_rule.create", $data);
    }

    public function edit($id)
    {
        $data['country'] = DB::table('country_info')->get();
        $data['document'] = DB::table('document_list')->get();
        $data['rules'] = DB::table('document_rule_data')->where('id', $id)->get();

        // dd($data['rules'][0]->document_description);
        return view("Document_rule.edit", $data);
    }

    public function getDocuments($id)
    {
        $data['country'] = DocumentRules::whereIn('offer_id', $id)->get();
        // dd($data);
        return view("Document_rule.index", $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $group_array = collect($request->offer_id)->implode(',');
            $id = DocumentRules::create([
                'nationality' => $request->nationality,
                'destination' => $request->destination,
                'offer_id' => $group_array,
                'document_type' => $request->document_type,
                'document_name' => $request->document_name
            ])->id;

            $user = DocumentRules::find($id);

            $user->document_needed = $request->document_mandatory;
            $user->document_description = $request->message;
            $user->visa_type = $request->document_requiredments;

            $user->save();

            $data['success'] = "1";
            $data['message'] = "Offer Created";
            $data['offerinfo'] = [
                'Nationality' => $request->nationality,
                'Destination' => $request->destination,
                'Visa Type' => $request->visa_type,
                'Visa Category' => $request->visa_category,
                'Visa Validity' => $request->visa_validity,
                'offer_id' => $id,
            ];
            $data['country'] = DB::table('country_info')->get();
            return view("Document_rule.index", $data);
        } catch (\Expection $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function update(Request $request)
    {
        // dd($request);
        try {
            $group_array = collect($request->offer_id)->implode(',');
            $user = DocumentRules::find($request->id);

            $user->document_needed = $request->document_mandatory;
            $user->document_description = $request->message;
            $user->visa_type = $request->document_requiredments;
            $user->nationality = $request->nationality;
            $user->destination = $request->destination;
            // $user->offer_id = $group_array;
            $user->document_type = $request->document_type;
            $user->document_name = $request->document_name;
            $user->save();
            return redirect('/offer/rule/get');
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function update_offers(Request $request)
    {
        try {
            $offer_id = $request->input('offer_id');
            $id = $request->input('document_id');
            $offer_ids = DocumentRules::where('id', $id)->get();

            $offer_ids_array = explode(',', $offer_ids[0]->offer_id);
            // Find the index of the value to remove
            $index = array_search($offer_id, $offer_ids_array);
            // dd($index);
            // Check if the value exists in the array before removing
            if ($index !== false) {
                // Remove the value from the array
                unset($offer_ids_array[$index]);

                // Convert the array back to a comma-separated string
                $updated_offer_ids = implode(',', $offer_ids_array);

                // Update the 'offer_id' field in the database
                $updated = DocumentRules::where('id', $id)->update(['offer_id' => $updated_offer_ids]);
                $data['data'] = "Updated Successfully";
            } else {
                $data['data'] = "Document Not Found in Offer";
            }
        } catch (\Exception $e) {
            $data['data'] = "Something went Wrong";
        }

        return response()->json($data);
        // return response()->json($data);
    }
}
