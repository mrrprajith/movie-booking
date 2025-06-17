@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 px-4">
        Admin Dashboard
    </h1>

    @foreach ($shows as $show)
    <div class="card mb-4">
        <div class="card-header">
            <strong>Show:</strong> {{ $show->show_date }} at {{ $show->show_time }}
        </div>
        <div class="card-body">
            <div class="row text-center mb-4">
                <div class="col border p-3">
                    <strong>Total Seats</strong><br>
                    {{ $show->seats->count() }}
                </div>
                <div class="col border p-3">
                    <strong>Booked Seats</strong><br>
                    {{ $show->seats->filter(fn($seat) => $seat->bookingSeats->count() > 0)->count() }}
                </div>
                <div class="col border p-3">
                    <strong>Available Seats</strong><br>
                    {{ $show->seats->filter(fn($seat) => $seat->bookingSeats->count() === 0)->count() }}
                </div>
            </div>

            <h5 class="text-xl font-bold text-indigo-600 mb-3">Bookings:</h5>

            <ul class="list-unstyled">
                @foreach ($show->seats->where('bookingSeats', '!=', [])->groupBy(function($seat) {
                return $seat->bookingSeats->first()->booking->user->name ?? 'Unknown';
                }) as $userName => $seatsByUser)
                <li class="mb-2">
                    <strong>{{ $userName !== 'Unknown' ? $userName : 'Unassigned Booking' }}</strong>:
                    @foreach ($seatsByUser as $seat)
                    <span class="badge bg-secondary me-1 mb-1">
                    {{ $seat->seat_number }}
                    </span>
                    @endforeach
                </li>
                @endforeach
            </ul>

<form method="POST" action="{{ route('admin.resetBookings', $show->id) }}" onsubmit="return confirm('Are you sure you want to reset all bookings for this show?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm mt-2">
        Reset Bookings
    </button>
</form>
        </div>
    </div>
    @endforeach
</div>
@endsection