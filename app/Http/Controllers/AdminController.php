<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        // Get all shows with seats and bookings
        $shows = Show::with(['seats.bookingSeats.booking.user'])->get();

        return view('admin.dashboard', compact('shows'));
    }

    public function resetBookings(Show $show) {
        foreach ($show->seats as $seat) {
            $seat->bookingSeats()->delete();
        }

        return back()->with('status', 'All bookings for this show have been reset.');
    }
}
