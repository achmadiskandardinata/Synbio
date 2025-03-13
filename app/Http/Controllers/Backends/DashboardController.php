<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        //Count order
        $process = Order::where('status', 'process')->count();
        $pending = Order::where('status', 'pending')->count();
        $success = Order::where('status', 'success')->count();

        // Count User
        $customers = User::count();

        //Year and month
        $year = date('Y');
        $month = date('m');

        //statistic revenue
        $revenueMonth = Order::where('status', 'success')->whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('total_price');
        $revenueYear = Order::where('status', 'success')->whereYear('created_at', $year)->sum('total_price');
        $revenueAll = Order::where('status', 'success')->sum('total_price');


        return view('backends.dashboard', compact('process', 'pending', 'success', 'customers', 'revenueMonth', 'revenueYear', 'revenueAll'));
    }
}
