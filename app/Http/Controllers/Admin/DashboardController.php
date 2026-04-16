<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Room;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'rooms_count' => Room::count(),
            'active_bookings' => Booking::active()->count(),
            'completed_bookings' => Booking::where('status', 'confirmed')->where('check_out', '<', now()->toDateString())->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
