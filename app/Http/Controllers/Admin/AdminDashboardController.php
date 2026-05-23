<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with aggregated data
     */
    public function index(): View
    {
        // Get user statistics
        $userStats = $this->getUserStatistics();

        return view('admin.dashboard', compact('userStats'));
    }

    /**
     * Get comprehensive user statistics for dashboard
     */
    private function getUserStatistics(): array
    {
        // Total Users
        $totalUsers = User::count();

        // Total Balance - Sum of all user wallet balances with fallback checks
        $totalBalance = $this->getTotalBalance();

        // Total Bonus - Sum of all user bonus amounts with fallback checks
        $totalBonus = $this->getTotalBonus();

        // Total Cashback - Sum of all user cashback amounts with fallback checks
        $totalCashback = $this->getTotalCashback();

        // Total Orders - Count from orders table if exists, otherwise use a placeholder
        $totalOrders = $this->getTotalOrders();

        // Additional metrics for professional dashboard
        // Since 'is_active' column doesn't exist, we'll use email_verified_at as a proxy for active users
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        $inactiveUsers = User::whereNull('email_verified_at')->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        // Recent registrations (last 30 days)
        $recentRegistrations = User::where('created_at', '>=', now()->subDays(30))->count();

        // Calculate growth percentages
        $previousMonthUsers = User::where('created_at', '>=', now()->subDays(60))
            ->where('created_at', '<', now()->subDays(30))
            ->count();

        $userGrowthPercentage = $previousMonthUsers > 0
            ? round((($recentRegistrations - $previousMonthUsers) / $previousMonthUsers) * 100, 2)
            : 0;

        return [
            'total_users' => $totalUsers,
            'total_balance' => number_format($totalBalance, 2),
            'total_bonus' => number_format($totalBonus, 2),
            'total_cashback' => number_format($totalCashback, 2),
            'total_orders' => $totalOrders,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'verified_users' => $verifiedUsers,
            'unverified_users' => $unverifiedUsers,
            'recent_registrations' => $recentRegistrations,
            'user_growth_percentage' => $userGrowthPercentage,
            'balance_formatted' => '₹'.number_format($totalBalance, 2),
            'bonus_formatted' => '₹'.number_format($totalBonus, 2),
            'cashback_formatted' => '₹'.number_format($totalCashback, 2),
        ];
    }

    /**
     * Get total balance from various possible sources
     */
    private function getTotalBalance(): float
    {
        try {
            // Check if wallet_balance column exists in users table
            if (DB::getSchemaBuilder()->hasColumn('users', 'wallet_balance')) {
                return User::sum('wallet_balance') ?? 0;
            }

            // Check if balance column exists in users table
            if (DB::getSchemaBuilder()->hasColumn('users', 'balance')) {
                return User::sum('balance') ?? 0;
            }

            // Check if wallets table exists
            if (DB::getSchemaBuilder()->hasTable('wallets')) {
                return DB::table('wallets')->sum('balance') ?? 0;
            }

            // Check if user_wallets table exists
            if (DB::getSchemaBuilder()->hasTable('user_wallets')) {
                return DB::table('user_wallets')->sum('balance') ?? 0;
            }
        } catch (\Exception $e) {
            // Return 0 if any error occurs
        }

        return 0;
    }

    /**
     * Get total bonus from various possible sources
     */
    private function getTotalBonus(): float
    {
        try {
            // Check if bonus_amount column exists in users table
            if (DB::getSchemaBuilder()->hasColumn('users', 'bonus_amount')) {
                return User::sum('bonus_amount') ?? 0;
            }

            // Check if bonus column exists in users table
            if (DB::getSchemaBuilder()->hasColumn('users', 'bonus')) {
                return User::sum('bonus') ?? 0;
            }

            // Check if bonuses table exists
            if (DB::getSchemaBuilder()->hasTable('bonuses')) {
                return DB::table('bonuses')->sum('amount') ?? 0;
            }

            // Check if user_bonuses table exists
            if (DB::getSchemaBuilder()->hasTable('user_bonuses')) {
                return DB::table('user_bonuses')->sum('bonus_amount') ?? 0;
            }
        } catch (\Exception $e) {
            // Return 0 if any error occurs
        }

        return 0;
    }

    /**
     * Get total cashback from various possible sources
     */
    private function getTotalCashback(): float
    {
        try {
            // Check if cashback_amount column exists in users table
            if (DB::getSchemaBuilder()->hasColumn('users', 'cashback_amount')) {
                return User::sum('cashback_amount') ?? 0;
            }

            // Check if cashback column exists in users table
            if (DB::getSchemaBuilder()->hasColumn('users', 'cashback')) {
                return User::sum('cashback') ?? 0;
            }

            // Check if cashbacks table exists
            if (DB::getSchemaBuilder()->hasTable('cashbacks')) {
                return DB::table('cashbacks')->sum('amount') ?? 0;
            }

            // Check if user_cashbacks table exists
            if (DB::getSchemaBuilder()->hasTable('user_cashbacks')) {
                return DB::table('user_cashbacks')->sum('cashback_amount') ?? 0;
            }
        } catch (\Exception $e) {
            // Return 0 if any error occurs
        }

        return 0;
    }

    /**
     * Get total orders count
     * This method checks if orders table exists and returns count accordingly
     */
    private function getTotalOrders(): int
    {
        try {
            // Check if orders table exists
            if (DB::getSchemaBuilder()->hasTable('orders')) {
                return DB::table('orders')->count();
            }

            // If orders table doesn't exist, check for other possible order tables
            if (DB::getSchemaBuilder()->hasTable('user_orders')) {
                return DB::table('user_orders')->count();
            }

            // Return 0 if no order table found
            return 0;
        } catch (\Exception $e) {
            // Return 0 if any error occurs
            return 0;
        }
    }

    /**
     * Get dashboard data via AJAX for real-time updates
     */
    public function getDashboardData(): \Illuminate\Http\JsonResponse
    {
        $userStats = $this->getUserStatistics();

        return response()->json([
            'success' => true,
            'data' => $userStats,
        ]);
    }

    /**
     * Get user registration chart data for the last 12 months
     */
    public function getUserRegistrationChart(): \Illuminate\Http\JsonResponse
    {
        $chartData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $chartData[] = [
                'month' => $month->format('M Y'),
                'count' => $count,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $chartData,
        ]);
    }
}
