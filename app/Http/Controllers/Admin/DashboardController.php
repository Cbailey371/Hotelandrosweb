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
            'bookings_count' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
