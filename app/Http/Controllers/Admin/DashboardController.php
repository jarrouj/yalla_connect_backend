<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\PromoCode;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getAllUsers()
    { //returns the users number

        $usersCount = User::count();

        return view('admin.home' , compact('usersCount'));
    }

    public function getNumberOfTransactionsTdy()
    { //returns the number of transactions made today
        // Get today's date
        $today = Carbon::today();

        // Count transactions created today
        $TransactionCount = Transaction::whereDate('created_at', $today)->count();

        return view('admin.home' , compact('TransactionCount'));
    }

    public function getRevenueToday() //return the revenue that was made today
    {
        $today = Carbon::today();

        // Sum all transaction amounts created today
        $revenue = Transaction::whereDate('created_at', $today)->sum('amount');

        return view('admin.home' , compact('revenue'));
    }

    public function getRevenueThisMonth()
    { //return the revenue that was made this month
        $month = Carbon::now()->month;

        // Sum all transaction amounts created this month
        $revenue = Transaction::whereMonth('created_at', $month)->sum('amount');

        return view('admin.home')->with('revenue_this_month', $revenue);
    }

    public function getActivePromoCodes()
    { //return the active promo codes

         $now = Carbon::now();

    return PromoCode::where('is_active', true)
        ->where(function ($query) use ($now) {
            $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
        })
        ->get();
    }

        public function getNumberOfOffers() {
            $offers = Offer::count();

            return view('admin.home')->with('number_of_offers', $offers);
        }
}
