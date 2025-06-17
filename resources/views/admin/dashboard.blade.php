@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>

    @foreach ($shows as $show)
    <div class="card mb-4">
        <div class="card-header">
            <strong>Show:</strong> {{ $show->show_date }} at {{ $show->show_time }}
        </div>
        <div class="card-body">
            <p><strong>Total Seats:</strong> {{ $show->seats->count() }}</p>
            <p><strong>Booked Seats:</strong>
                {{ $show->seats->filter(fn($seat) => $seat->bookingSeats->count() > 0)->count() }}
            </p>
            <p><strong>Available Seats:</strong>
                {{ $show->seats->filter(fn($seat) => $seat->bookingSeats->count() === 0)->count() }}
            </p>

            <h5 class="text-xl font-bold text-indigo-600">Bookings:</h5>
            <ul>
                @foreach ($show->seats->where('bookingSeats', '!=', [])->groupBy(function($seat) {
                return $seat->bookingSeats->first()->booking->user->name ?? 'Unknown';
                }) as $userName => $seatsByUser)
                <li>
                    <strong>{{ $userName }}</strong>:
                    @foreach ($seatsByUser as $seat)
                    {{ $seat->seat_number }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
@endsection
