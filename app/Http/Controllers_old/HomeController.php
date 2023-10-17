<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    //
    public function home()
    {
        $data['users'] = DB::table('users')->count();
        $data['visa_per_month'] = DB::table('visa_data')
            ->select(DB::raw('YEAR(created_at) as year, MONTHNAME(created_at) as month, COUNT(*) as count'))
            ->where('created_at', '<=', now()) // Only count records up to today
            ->groupBy('year', 'month')
            ->orderBy('count', 'asc')
            ->get();

        $data['destination'] = DB::table('visa_data')
            ->select('visa_type_selected', 'destination', DB::raw('COUNT(*) as count'))
            ->where('created_at', '<=', now()) // Only count records up to today
            ->groupBy('destination', 'visa_type_selected')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        $data['destination_current'] = DB::table('visa_data')
            ->select('visa_type_selected', 'destination', DB::raw('COUNT(*) as count'))
            ->whereMonth('created_at', '=', now()->month) // Filter records for the current month
            ->whereYear('created_at', '=', now()->year)   // Filter records for the current year
            ->groupBy('destination', 'visa_type_selected')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        $data['nationality'] = DB::table('visa_data')
            ->select('nationality', DB::raw('COUNT(*) as count'))
            ->where('created_at', '<=', now()) // Only count records up to today
            ->groupBy('nationality')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        $data['user_based'] = DB::table('visa_data')
            ->select('nationality', 'user_id', DB::raw('COUNT(*) as count'))
            ->where('created_at', '<=', now()) // Only count records up to today
            ->groupBy('nationality', 'user_id')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $topDestinations = DB::table('visa_data')
            ->select('destination', DB::raw('COUNT(*) as count'))
            ->where('created_at', '<=', now()) // Only count records up to today
            ->groupBy('destination')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('destination');


        $data['top_destination_visa_type'] = [];

        // Iterate through the top destinations
        foreach ($topDestinations as $destination) {
            // Get the count of each visa_type_selected for the current destination
            $visaTypesForDestination = DB::table('visa_data')
                ->select('destination', 'visa_type_selected', DB::raw('COUNT(*) as count'))
                ->where('destination', $destination)
                ->groupBy('destination', 'visa_type_selected')
                ->get();

            // Append the destination and visa types to the structured data array
            $data['top_destination_visa_type'][$destination] = $visaTypesForDestination;
        }

        $data['visa_status'] = DB::table('visa_data')->where('visa_status', 'active')->count();
        $data['agency'] = DB::table('agency_data')->count();
        $data['categories'] = ['Tourist', 'Student', 'Bussiness', 'Transit', 'Medical'];
        $data['visa_count'] = DB::table('visa_data')->count();



        // return response()->json($data);
        return view('index', $data);
    }
}
