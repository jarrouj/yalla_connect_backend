<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show_orders(Request $request)
    {
        $orders = Checkout::with('product', 'user')->latest()->paginate(10);
        $users  = User::all();

         $status = $request->query('status', 'all'); // all|completed|pending
        $period = $request->query('period', 'all'); // all|week|month

        $query = Checkout::query()->latest();

         if ($status === 'completed') {
        $query->where('is_completed', 1);
    } elseif ($status === 'pending') {
        $query->where(function ($q) {
            $q->whereNull('is_completed')->orWhere('is_completed', '!=', 1);
        });
    }

    // ----- Date filter (timezone-safe) -----
    $now = Carbon::now();

    $toUtc = fn(Carbon $c) => $c->clone()->setTimezone('UTC');

    if ($period === 'today') {
        $start = $toUtc($now->copy()->startOfDay());
        $end   = $toUtc($now->copy()->endOfDay());
        $query->whereBetween('created_at', [$start, $end]);
    } elseif ($period === 'week') {
        // from Monday 00:00 (or your locale start) until **now**, inclusive
        $start = $toUtc($now->copy()->startOfWeek());
        $end   = $toUtc($now->copy()->endOfDay());
        $query->whereBetween('created_at', [$start, $end]);
    } elseif ($period === 'month') {
        // from 1st 00:00 until **now**, inclusive
        $start = $toUtc($now->copy()->startOfMonth());
        $end   = $toUtc($now->copy()->endOfDay());
        $query->whereBetween('created_at', [$start, $end]);
    }

    $orders = $query->paginate(15)->appends($request->query());
    $users = User::select('id','first_name','last_name','email','phone')->get();

        return view('admin.order.show_order', compact('orders' , 'users'))->with([
                'selectedStatus' => $status,
                'selectedPeriod' => $period,
            ]);
    }

   public function updateStatus(Request $request, $id)
{
    $order = Checkout::findOrFail($id);

    // Handle based on conf value
    if ($request->input('conf') == 1) {
        $order->is_completed = true;
    } elseif ($request->input('conf') == 0) {
        $order->is_completed = false;
    }

    $order->save();

    return redirect()->back()->with('message', 'Order status updated.');
}


    public function delete_order($id)
    {
        $order = Checkout::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('message', 'Order deleted successfully.');
    }
}
