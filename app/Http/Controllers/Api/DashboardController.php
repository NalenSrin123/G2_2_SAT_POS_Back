<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        $currentYear = now()->year;

        // Total sales all time
        $totalSales = Order::sum('total_price');

        // Total orders all time
        $totalOrders = Order::count();

        // Revenue this month
        $revenueThisMonth = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        // Revenue last month
        $revenueLastMonth = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        // Customers
        $customers = Customer::count();

        // Orders this month
        $ordersThisMonth = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Orders last month
        $ordersLastMonth = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Sales this month
        $salesThisMonth = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        // Sales last month
        $salesLastMonth = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        // Monthly sales chart
        $monthlySales = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        $months = [
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AUG',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DEC',
        ];

        $salesOverview = [];

        foreach ($months as $number => $name) {
            $salesOverview[] = [
                'month' => $name,
                'total' => $monthlySales[$number] ?? 0,
            ];
        }

        return response()->json([
            'success' => true,

            'cards' => [
                'total_sales' => [
                    'value' => $totalSales,
                    'change' => $this->percentageChange($salesThisMonth, $salesLastMonth),
                ],

                'total_orders' => [
                    'value' => $totalOrders,
                    'change' => $this->percentageChange($ordersThisMonth, $ordersLastMonth),
                ],

                'revenue' => [
                    'value' => $revenueThisMonth,
                    'change' => $this->percentageChange($revenueThisMonth, $revenueLastMonth),
                ],

                'customers' => [
                    'value' => $customers,
                    'change' => 0,
                ],
            ],

            'sales_overview' => $salesOverview,

            'summary' => [
                'products' => Product::count(),
                'categories' => Category::count(),
                'orders' => Order::count(),
                'customers' => $customers,
            ],
        ]);
    }

    private function percentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}