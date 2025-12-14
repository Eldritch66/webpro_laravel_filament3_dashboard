<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $totalBookings = Booking::count();

        $totalSales = Booking::whereIn('status', [
            'confirmed',
            'checked_in',
            'checked_out',
        ])->sum('amount');

        $todayCheckIns = Booking::whereDate('check_in', $today)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();

        $totalRooms = Room::count();
        $occupiedRooms = Booking::whereIn('status', ['checked_in'])
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->count();

        $occupancyRate = $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100, 1)
            : 0;

        return [
            Stat::make('Bookings', $totalBookings)
                ->description('Total bookings')
                ->icon('heroicon-o-calendar'),

            Stat::make('Sales', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->description('Total revenue')
                ->icon('heroicon-o-currency-dollar'),

            // Stat::make('Check Ins Today', $todayCheckIns)
            //     ->description($today->format('d M Y'))
            //     ->icon('heroicon-o-arrow-right-circle'),

            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->description('Today')
                ->icon('heroicon-o-home'),
        ];
    }
}
