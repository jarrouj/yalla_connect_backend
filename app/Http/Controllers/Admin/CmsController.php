<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Specialty;

class CmsController extends Controller
{
     public function dash(Request $request)
    {


        $user = User::latest()->paginate(5); // all users
        $NumberOfUsers = User::where('usertype', 0)->count(); //return customers non-admin users
        $today = Carbon::today();// today date
        $TransactionCount = Transaction::whereDate('created_at', $today)->count();// Count transactions created today
        $revenue_tdy = Transaction::whereDate('created_at', $today)->sum('amount'); // Sum all transaction amounts created today
        $month = Carbon::now()->month; // this months number
        $revenue_this_month = Transaction::whereMonth('created_at', $month)->sum('amount');// Sum all transaction amounts created this month
        $now = Carbon::now();
        $active_promo_codes = PromoCode::where('is_active', true)->count(); //return the active promo codes
        $number_of_prodcuts = Product::all()->count(); // Total number of products
        $completed_orders_tdy = Checkout::whereDate('created_at', $today)
                                ->where('is_completed', true)
                                ->count(); // number of completed orders today
        $active_specialties = Specialty::where('is_active', true)->count(); // Total number of Active Specialties
        $non_finished_orders = Checkout::where('is_completed', false)->count(); // number of non completed orders

        // $NumberOfOrdersConfirmed = Order::where('confirm', 1) // number of confirmed orders
        // ->whereBetween('created_at', [$startDate, $endDate])
        // ->count();

        // $NumberOfOrdersNonConfirmed = Order::whereNull('confirm')// number of order nor confirmed nor canceled
        // ->whereBetween('created_at', [$startDate, $endDate])
        // ->count();

        // $NumberOfActiveOffers = Offer::where('active', 1)->count(); // Total Number of Active Offers
        // $NumberOfProducts = Product::all()->count(); // Total number of products
        // $NumberOfSubscribers = Subscriber::all()->count(); // Total subscribers



    //     $start_date = Carbon::now()->format('d-m-Y');
    //         $end_date = Carbon::now()->format('d-m-Y');

    //         // if ($start_date != Carbon::now()->format('d/m/Y') && $end_date != Carbon::now()->format('d/m/Y'))
    //         // {
    //         //     $start_date = $request->start_date;
    //         //     $end_date = $request->end_date;
    //         // }

    //         if ($request->start_date && $request->end_date)
    //         {
    //             $start_date = $request->start_date;
    //             $end_date = $request->end_date;
    //         }

    //         // $filter = $this->filterDate($start_date , $end_date);


    //      // Calculate revenue
    // $Revenue = 0;

    // if ($startDate && $endDate) {
    //     $orders = Order::where('confirm', 1)
    //         ->where('method', 1)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->get();
    // } else {
    //     // If start_date and end_date are not provided, calculate revenue for today
    //     $orders = Order::where('confirm', 1)
    //         ->where('method', 1)
    //         ->whereDate('created_at', now()->format('Y-m-d'))
    //         ->get();
    // }

    // // Calculate revenue
    // foreach ($orders as $order) {
    //     $Revenue += $order->total_usd;
    // }


    //     // Calculate revenue for each day within the date range
    //     $revenue = [];

    //     if ($startDate && $endDate) {
    //         $startDateObj = Carbon::parse($startDate);
    //         $endDateObj = Carbon::parse($endDate);

    //         // Loop through each day in the date range
    //         while ($startDateObj <= $endDateObj) {
    //             $revenue[$startDateObj->format('Y-m-d')] = Order::where('confirm', 1)
    //                 ->where('method', 1)
    //                 ->whereDate('created_at', $startDateObj->format('Y-m-d'))
    //                 ->sum('total_usd');

    //             $startDateObj->addDay();
    //         }
    //     }

    //     // Calculate total revenue for each month
    //     $monthlyRevenue = Order::where('confirm', 1)
    //         ->where('method', 1)
    //         ->whereBetween('created_at', [$startDate ?? now()->startOfMonth(), $endDate ?? now()->endOfMonth()])
    //         ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m")')
    //         ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_usd) as revenue')
    //         ->groupBy('month')
    //         ->pluck('revenue', 'month');

        // return view('admin.home', compact(
        //     'user',
        //     'NumberOfUsers',
        //     'NumberOfOrdersConfirmed',
        //     'NumberOfOrdersNonConfirmed',
        //     'NumberOfActiveOffers',
        //     'NumberOfProducts',
        //     'NumberOfSubscribers',
        //     'revenue',
        //     'Revenue' ,

        //     'startDate',
        //     'endDate',
        //     'monthlyRevenue',
        //     'start_date',
        //     'end_date',
        // ));

           return view('admin.home' , compact('NumberOfUsers', 'TransactionCount' , 'revenue_this_month' , 'revenue_tdy' , 'active_promo_codes' , 'number_of_prodcuts' , 'completed_orders_tdy' , 'active_specialties' , 'non_finished_orders') );
    }

}
