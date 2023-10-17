<?php

namespace App\Http\Controllers;

// use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\MailController;


class TrackController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if ($user->branch_id == null || $user->user_type == "S") {
            $originalData = DB::table('applicants_data')
                ->join('visa_data', 'applicants_data.visa_id', '=', 'visa_data.id')
                ->leftJoinSub(
                    DB::table('visa_data')
                        ->select('id', DB::raw('COUNT(*) as application_count'))
                        ->groupBy('id'),
                    'visa_counts',
                    function ($join) {
                        $join->on('visa_counts.id', '=', 'visa_data.id');
                    }
                )
                ->select(
                    'visa_data.*',
                    'visa_data.id',
                    'visa_data.visa_type_selected',
                    DB::raw('COUNT(IFNULL(visa_counts.application_count, 0)) as application_count'),

                    'applicants_data.passport_number as app_number',
                    'applicants_data.surname',
                    'applicants_data.firstname',
                    'applicants_data.nationality',
                    'applicants_data.key_field',
                    'applicants_data.value',
                    // 'applicants_data.id',
                    DB::raw('COUNT(*) as id_count')
                )
                ->groupBy(
                    'visa_data.id',
                    'visa_data.id',
                    'visa_data.user_id',
                    'visa_data.branch_id',
                    'visa_data.nationality',
                    'visa_data.destination',
                    'visa_data.travel_date_from',
                    'visa_data.travel_date_to',
                    'visa_data.visa_offer',
                    'visa_data.offer_id',
                    'visa_data.visa_fees',
                    'visa_data.visa_status',
                    'visa_data.passport_number',
                    'visa_data.application_JSON',
                    'visa_data.files_JSON',
                    'visa_data.submitted_at',
                    'visa_data.created_at',
                    'visa_data.deleted_at',
                    'visa_data.updated_at',
                    'visa_data.visa_type_selected',
                    'visa_data.email_status',
                    'applicants_data.passport_number',
                    'applicants_data.surname',
                    'applicants_data.firstname',
                    'applicants_data.nationality',
                    'applicants_data.key_field',
                    'applicants_data.value',
                    // 'applicants_data.id',
                    // 'applicants_data.visa_id'
                )
                ->orderBy('visa_data.id', 'desc')
                ->get();

            // Restructure the data
            $restructuredData = [];
            foreach ($originalData as $item) {
                $visaId = $item->id;

                if (!isset($restructuredData[$visaId])) {
                    $restructuredData[$visaId] = [
                        'visa_id' => $visaId,
                        'visa_type_selected' => $item->visa_type_selected,
                        'application_count' => $item->application_count,
                        'destination' => $item->destination,
                        'status' => $item->visa_status,
                        'From_date' => $item->travel_date_from,
                        'To_date' => $item->travel_date_to,
                        'created_at' => $item->created_at,
                        'Visa_offer' => $item->visa_offer,
                        'nationality' => $item->nationality,
                        'visa_type' => $item->visa_offer,

                        'applications' => [],
                    ];
                }

                $restructuredData[$visaId]['applications'][] = [
                    'surname' => $item->surname,
                    'firstname' => $item->firstname,
                    'nationality' => $item->nationality,
                    'passport' => $item->app_number,
                    // ... Add other application details as needed
                ];
                $restructuredData[$visaId]['application_count'] = count($restructuredData[$visaId]['applications']);
            }

            // Convert the restructured data to JSON
            $restructuredJson = json_encode(array_values($restructuredData));
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Track list')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $originalData = DB::table('applicants_data')
                    ->join('visa_data', 'applicants_data.visa_id', '=', 'visa_data.id')
                    ->leftJoinSub(
                        DB::table('visa_data')
                            ->select('id', DB::raw('COUNT(*) as application_count'))
                            ->groupBy('id'),
                        'visa_counts',
                        function ($join) {
                            $join->on('visa_counts.id', '=', 'visa_data.id');
                        }
                    )
                    ->select(
                        'visa_data.*',
                        'visa_data.id',
                        'visa_data.visa_type_selected',
                        DB::raw('COUNT(IFNULL(visa_counts.application_count, 0)) as application_count'),

                        'applicants_data.passport_number as app_number',
                        'applicants_data.surname',
                        'applicants_data.firstname',
                        'applicants_data.nationality',
                        'applicants_data.key_field',
                        'applicants_data.value',
                        // 'applicants_data.id',
                        DB::raw('COUNT(*) as id_count')
                    )
                    ->groupBy(
                        'visa_data.id',
                        'visa_data.id',
                        'visa_data.user_id',
                        'visa_data.branch_id',
                        'visa_data.nationality',
                        'visa_data.destination',
                        'visa_data.travel_date_from',
                        'visa_data.travel_date_to',
                        'visa_data.visa_offer',
                        'visa_data.offer_id',
                        'visa_data.visa_fees',
                        'visa_data.visa_status',
                        'visa_data.passport_number',
                        'visa_data.application_JSON',
                        'visa_data.files_JSON',
                        'visa_data.submitted_at',
                        'visa_data.created_at',
                        'visa_data.deleted_at',
                        'visa_data.updated_at',
                        'visa_data.visa_type_selected',
                        'visa_data.email_status',
                        'applicants_data.passport_number',
                        'applicants_data.surname',
                        'applicants_data.firstname',
                        'applicants_data.nationality',
                        'applicants_data.key_field',
                        'applicants_data.value',
                        // 'applicants_data.id',
                        // 'applicants_data.visa_id'
                    )
                    ->orderBy('visa_data.id', 'desc')
                    ->paginate();


                // Restructure the data
                $restructuredData = [];
                foreach ($originalData as $item) {
                    $visaId = $item->id;

                    if (!isset($restructuredData[$visaId])) {
                        $restructuredData[$visaId] = [
                            'visa_id' => $visaId,
                            'visa_type_selected' => $item->visa_type_selected,
                            'application_count' => $item->application_count,
                            'destination' => $item->destination,
                            'status' => $item->visa_status,
                            'From_date' => $item->travel_date_from,
                            'To_date' => $item->travel_date_to,
                            'created_at' => $item->created_at,
                            'Visa_offer' => $item->visa_offer,
                            'nationality' => $item->nationality,
                            'visa_type' => $item->visa_offer,

                            'applications' => [],
                        ];
                    }

                    $restructuredData[$visaId]['applications'][] = [
                        'surname' => $item->surname,
                        'firstname' => $item->firstname,
                        'nationality' => $item->nationality,
                        'passport' => $item->app_number,
                        // ... Add other application details as needed
                    ];
                    $restructuredData[$visaId]['application_count'] = count($restructuredData[$visaId]['applications']);
                }

                // Convert the restructured data to JSON
                $restructuredJson = json_encode(array_values($restructuredData));
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
            // $index['data'] = User::where('group_id',$user->group_id)->whereUser_type("O")->get();

        }

        // dd(gettype($restructuredData));

        // return view('Track_Application.index', compact('restructuredJson'));
        return view("Track_Application.index", ['restructuredJson' => $restructuredJson]);
    }

    public function search_name(Request $request)
    {
        // $user = Auth::user();
        // if ($user->group_id == null || $user->user_type == "S") {
        // $index['data'] = DB::table('applicants_data')->get();
        $name = $request->search_name;

        $originalData = DB::table('applicants_data')
            ->join('visa_data', 'applicants_data.visa_id', '=', 'visa_data.id')
            ->leftJoinSub(
                DB::table('visa_data')
                    ->select('id', DB::raw('COUNT(*) as application_count'))
                    ->groupBy('id'),
                'visa_counts',
                function ($join) {
                    $join->on('visa_counts.id', '=', 'visa_data.id');
                }
            )
            ->select(
                'visa_data.*',
                'visa_data.id',
                'visa_data.visa_type_selected',
                DB::raw('COUNT(IFNULL(visa_counts.application_count, 0)) as application_count'),

                'applicants_data.passport_number as app_number',
                'applicants_data.surname',
                'applicants_data.firstname',
                'applicants_data.nationality',
                'applicants_data.key_field',
                'applicants_data.value',
                // 'applicants_data.id',
                DB::raw('COUNT(*) as id_count')
            )
            ->where(function ($query) use ($name) {
                $query->where('applicants_data.surname', 'LIKE', '%' . $name . '%')
                    ->orWhere('applicants_data.firstname', 'LIKE', '%' . $name . '%')
                    ->orWhere('applicants_data.passport_number', 'LIKE', '%' . $name . '%')
                    ->orWhere('visa_data.destination', 'LIKE', '%' . $name . '%')
                    ->orWhere('visa_data.id', 'LIKE', '%' . $name . '%')
                    ->orWhere('visa_data.nationality', 'LIKE', '%' . $name . '%');
            })
            ->groupBy(
                'visa_data.id',
                'visa_data.id',
                'visa_data.user_id',
                'visa_data.branch_id',
                'visa_data.nationality',
                'visa_data.destination',
                'visa_data.travel_date_from',
                'visa_data.travel_date_to',
                'visa_data.visa_offer',
                'visa_data.offer_id',
                'visa_data.visa_fees',
                'visa_data.visa_status',
                'visa_data.passport_number',
                'visa_data.application_JSON',
                'visa_data.files_JSON',
                'visa_data.submitted_at',
                'visa_data.created_at',
                'visa_data.deleted_at',
                'visa_data.updated_at',
                'visa_data.visa_type_selected',
                'visa_data.email_status',
                'applicants_data.passport_number',
                'applicants_data.surname',
                'applicants_data.firstname',
                'applicants_data.nationality',
                'applicants_data.key_field',
                'applicants_data.value',
                // 'applicants_data.id',
                // 'applicants_data.visa_id'
            )
            ->get();

        // Restructure the data
        $restructuredData = [];
        foreach ($originalData as $item) {
            $visaId = $item->id;

            if (!isset($restructuredData[$visaId])) {
                $restructuredData[$visaId] = [
                    'visa_id' => $visaId,
                    'visa_type_selected' => $item->visa_type_selected,
                    'application_count' => $item->application_count,
                    'destination' => $item->destination,
                    'status' => $item->visa_status,
                    'From_date' => $item->travel_date_from,
                    'To_date' => $item->travel_date_to,
                    'created_at' => $item->created_at,
                    'Visa_offer' => $item->visa_offer,
                    'nationality' => $item->nationality,
                    'visa_type' => $item->visa_offer,

                    'applications' => [],
                ];
            }

            $restructuredData[$visaId]['applications'][] = [
                'surname' => $item->surname,
                'firstname' => $item->firstname,
                'nationality' => $item->nationality,
                'passport' => $item->app_number,
                // ... Add other application details as needed
            ];
            $restructuredData[$visaId]['application_count'] = count($restructuredData[$visaId]['applications']);
        }

        // Convert the restructured data to JSON
        $restructuredJson = json_encode(array_values($restructuredData));

        // dd($restructuredJson);
        return view("Track_Application.index", ['restructuredJson' => $restructuredJson]);
    }

    public function date_range(Request $request)
    {
        // $user = Auth::user();
        // if ($user->group_id == null || $user->user_type == "S") {
        // $index['data'] = DB::table('applicants_data')->get();

        $dateFrom = $request->from;
        $dateTo = $request->to;

        $originalData = DB::table('applicants_data')
            ->join('visa_data', 'applicants_data.visa_id', '=', 'visa_data.id')
            ->leftJoinSub(
                DB::table('visa_data')
                    ->select('id', DB::raw('COUNT(*) as application_count'))
                    ->groupBy('id'),
                'visa_counts',
                function ($join) {
                    $join->on('visa_counts.id', '=', 'visa_data.id');
                }
            )
            ->select(
                'visa_data.*',
                'visa_data.id',
                'visa_data.visa_type_selected',
                DB::raw('COUNT(IFNULL(visa_counts.application_count, 0)) as application_count'),

                'applicants_data.passport_number as app_number',
                'applicants_data.surname',
                'applicants_data.firstname',
                'applicants_data.nationality',
                'applicants_data.key_field',
                'applicants_data.value',
                // 'applicants_data.id',
                DB::raw('COUNT(*) as id_count')
            )
            ->where(function ($query) use ($dateFrom, $dateTo) {
                $query
                    ->whereBetween('visa_data.created_at', [$dateFrom, $dateTo]);
            })
            ->groupBy(
                'visa_data.id',
                'visa_data.id',
                'visa_data.user_id',
                'visa_data.branch_id',
                'visa_data.nationality',
                'visa_data.destination',
                'visa_data.travel_date_from',
                'visa_data.travel_date_to',
                'visa_data.visa_offer',
                'visa_data.offer_id',
                'visa_data.visa_fees',
                'visa_data.visa_status',
                'visa_data.passport_number',
                'visa_data.application_JSON',
                'visa_data.files_JSON',
                'visa_data.submitted_at',
                'visa_data.created_at',
                'visa_data.deleted_at',
                'visa_data.updated_at',
                'visa_data.visa_type_selected',
                'visa_data.email_status',
                'applicants_data.passport_number',
                'applicants_data.surname',
                'applicants_data.firstname',
                'applicants_data.nationality',
                'applicants_data.key_field',
                'applicants_data.value',
                // 'applicants_data.id',
                // 'applicants_data.visa_id'
            )
            ->get();

        // Restructure the data
        $restructuredData = [];
        foreach ($originalData as $item) {
            $visaId = $item->id;

            if (!isset($restructuredData[$visaId])) {
                $restructuredData[$visaId] = [
                    'visa_id' => $visaId,
                    'visa_type_selected' => $item->visa_type_selected,
                    'application_count' => $item->application_count,
                    'destination' => $item->destination,
                    'status' => $item->visa_status,
                    'From_date' => $item->travel_date_from,
                    'To_date' => $item->travel_date_to,
                    'created_at' => $item->created_at,
                    'Visa_offer' => $item->visa_offer,
                    'nationality' => $item->nationality,
                    'visa_type' => $item->visa_offer,

                    'applications' => [],
                ];
            }

            $restructuredData[$visaId]['applications'][] = [
                'surname' => $item->surname,
                'firstname' => $item->firstname,
                'nationality' => $item->nationality,
                'passport' => $item->app_number,
                // ... Add other application details as needed
            ];
            $restructuredData[$visaId]['application_count'] = count($restructuredData[$visaId]['applications']);
        }

        // Convert the restructured data to JSON
        $restructuredJson = json_encode(array_values($restructuredData));

        // dd($restructuredJson);
        return view("Track_Application.index", ['restructuredJson' => $restructuredJson]);
    }

    public function status_applications($status)
    {

        DB::enableQueryLog(); // Or $connection->enableQueryLog();
        $name = $status;
        $originalData = DB::table('applicants_data')
            ->join('visa_data', 'applicants_data.visa_id', '=', 'visa_data.id')
            ->leftJoinSub(
                DB::table('visa_data')
                    ->select('id', DB::raw('COUNT(*) as application_count'))
                    ->groupBy('id'),
                'visa_counts',
                function ($join) {
                    $join->on('visa_counts.id', '=', 'visa_data.id');
                }
            )

            ->select(
                'visa_data.*',
                'visa_data.id',
                'visa_data.visa_type_selected',
                DB::raw('COUNT(IFNULL(visa_counts.application_count, 0)) as application_count'),

                'applicants_data.passport_number as app_number',
                'applicants_data.surname',
                'applicants_data.firstname',
                'applicants_data.nationality',
                'applicants_data.key_field',
                'applicants_data.value',
                // 'applicants_data.id',
                DB::raw('COUNT(*) as id_count')
            )

            ->where(function ($query) use ($name) {
                $query->where('visa_data.visa_status', 'LIKE', '%' . $name . '%');

                // ->orWhere('visa_data.nationality', 'LIKE', '%' . $name . '%');
            })
            ->groupBy(
                'visa_data.id',
                'visa_data.id',
                'visa_data.user_id',
                'visa_data.branch_id',
                'visa_data.nationality',
                'visa_data.destination',
                'visa_data.travel_date_from',
                'visa_data.travel_date_to',
                'visa_data.visa_offer',
                'visa_data.offer_id',
                'visa_data.visa_fees',
                'visa_data.visa_status',
                'visa_data.passport_number',
                'visa_data.application_JSON',
                'visa_data.files_JSON',
                'visa_data.submitted_at',
                'visa_data.created_at',
                'visa_data.deleted_at',
                'visa_data.updated_at',
                'visa_data.visa_type_selected',
                'visa_data.email_status',
                'applicants_data.passport_number',
                'applicants_data.surname',
                'applicants_data.firstname',
                'applicants_data.nationality',
                'applicants_data.key_field',
                'applicants_data.value',
                // 'applicants_data.id',
                // 'applicants_data.visa_id'
            )
            ->get();
        // dd(DB::getQueryLog());

        // Restructure the data
        $restructuredData = [];
        foreach ($originalData as $item) {
            $visaId = $item->id;

            if (!isset($restructuredData[$visaId])) {
                $restructuredData[$visaId] = [
                    'visa_id' => $visaId,
                    'visa_type_selected' => $item->visa_type_selected,
                    'application_count' => $item->application_count,
                    'destination' => $item->destination,
                    'status' => $item->visa_status,
                    'From_date' => $item->travel_date_from,
                    'To_date' => $item->travel_date_to,
                    'created_at' => $item->created_at,
                    'Visa_offer' => $item->visa_offer,
                    'nationality' => $item->nationality,
                    'visa_type' => $item->visa_offer,

                    'applications' => [],
                ];
            }

            $restructuredData[$visaId]['applications'][] = [
                'surname' => $item->surname,
                'firstname' => $item->firstname,
                'nationality' => $item->nationality,
                'passport' => $item->app_number,
                // ... Add other application details as needed
            ];
            $restructuredData[$visaId]['application_count'] = count($restructuredData[$visaId]['applications']);
        }

        // Convert the restructured data to JSON
        $restructuredJson = json_encode(array_values($restructuredData));

        // dd($restructuredJson);
        return view("Track_Application.index", ['restructuredJson' => $restructuredJson]);
    }


    public function update_status($id, $status)
    {

        $data = DB::table('visa_data')->where('id',$id)->pluck('user_id');
        $user = DB::table('users')->where('id',$data[0])->get();

        $fullname = $user[0]->first_name.' '.$user[0]->last_name;

        try {
            switch ($status) {
                case 'Ready To Submit':
                    // Logic to assign admin role to the user
                    DB::table('visa_data')
                        ->where('id', $id) // Specify the condition to select the record(s) to update
                        ->update(['visa_status' => 'In Process']); // Set the new values for the columns

                    $object = new MailController();
                    $object->sendEmail_application($user[0]->email,$fullname,$id,'In Process');
                    break;

                case 'In Process':
                    DB::table('visa_data')
                        ->where('id', $id) // Specify the condition to select the record(s) to update
                        ->update(['visa_status' => 'Completed Application']); // Set the new values for the columns
                    // Logic to assign moderator role to the user

                    $object = new MailController();
                    $object->sendEmail_application($user[0]->email,$fullname,$id,'Completed Application');
                    break;

                case 'Completed Application':
                    DB::table('visa_data')
                        ->where('id', $id) // Specify the condition to select the record(s) to update
                        ->update(['visa_status' => 'Held Applications']); // Set the new values for the columns

                        $object = new MailController();
                        $object->sendEmail_application($user[0]->email,$fullname,$id,'Held Applications');
                    break;

                case 'Held Applications':
                    DB::table('visa_data')
                        ->where('id', $id) // Specify the condition to select the record(s) to update
                        ->update(['visa_status' => 'Download Visa']); // Set the new values for the columns

                        $object = new MailController();
                        $object->sendEmail_application($user[0]->email,$fullname,$id,'Download Visa');
                    break;

                case 'Archived':
                    DB::table('visa_data')
                        ->where('id', $id) // Specify the condition to select the record(s) to update
                        ->update(['visa_status' => 'Archived']); // Set the new values for the columns

                        $object = new MailController();
                        $object->sendEmail_application($user[0]->email,$fullname,$id,'Archived');
                    break;

                case 'active':
                    DB::table('visa_data')
                        ->where('id', $id) // Specify the condition to select the record(s) to update
                        ->update(['visa_status' => 'Ready To Submit']); // Set the new values for the columns

                        $object = new MailController();
                        $object->sendEmail_application($user[0]->email,$fullname,$id,'Ready To Submit');
                    break;


                    // default:
                    //     // Handle the case where $newRole doesn't match any expected value
                    //     return response()->json(['message' => 'Invalid role specified'], 400);
                    //     break;

            }

            return redirect('/track');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
