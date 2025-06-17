<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request) {
        // Get all shows
        $shows = Show::all();

        // Get selected show from query param ?show_id=
        $selectedShow = null;
        $seats = [];

        if ($request->has('show_id')) {
            
            $selectedShow = Show::with('seats')->find($request->show_id);
            if ($selectedShow) {
                $seats = $selectedShow->seats;
            }
        }

        return view('book.index', compact('shows', 'selectedShow', 'seats'));
    }

    /**
    * Return booked seat IDs for a show (AJAX).
    */
    public function getSeats(Show $show) {
        // Get booked seat IDs for this show
        $bookedSeatIds = Seat::where('show_id', $show->id)
        ->whereHas('bookingSeats')
        ->pluck('id');

        return response()->json($bookedSeatIds);
    }

    /**
    * Store the booking.
    */
    public function store(Request $request) {
        $request->validate([
            'show_id' => 'required|exists:shows,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id',
        ]);

        $user = Auth::user();
        $showId = $request->show_id;
        $seatIds = $request->seats;

        // Check that none of the seats are already booked for this show
        $alreadyBooked = Seat::whereIn('id', $seatIds)
        ->whereHas('bookingSeats')
        ->exists();

        if ($alreadyBooked) {
            return response()->json(['error' => 'Some seats have already been booked.'], 409);
        }

        DB::transaction(function () use ($user, $showId, $seatIds) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'show_id' => $showId,
            ]);

            foreach ($seatIds as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                ]);
            }
        });

        return response()->json(['success' => 'Booking successful!']);
    }
}
