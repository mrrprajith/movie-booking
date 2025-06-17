@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Book Your Movie Tickets</h1>

    <!-- Show selector -->
    <form id="showForm" method="GET" action="{{ route('book') }}" class="mb-4">
        <div class="mb-3" style="max-width: 300px;">
            <label for="show_id" class="form-label">Select Show:</label>
            <select name="show_id" id="show_id" class="form-select" required>
                <option value="">-- Choose a Show --</option>
                @foreach ($shows as $show)
                <option value="{{ $show->id }}"
                    {{ optional($selectedShow)->id == $show->id ? 'selected' : '' }}>
                    {{ $show->show_date }} at {{ $show->show_time }}
                </option>
                @endforeach
            </select>
        </div>
    </form>

    @if ($selectedShow)
    <h3 class="mb-3">Seats for {{ $selectedShow->show_date }} at {{ $selectedShow->show_time }}</h3>

    <div id="seat-container" class="mb-4">
        @foreach ($seats as $seat)
        <button class="btn btn-outline-primary seat-btn mb-2 me-2" data-id="{{ $seat->id }}" id="seat-{{ $seat->id }}">
            {{ $seat->seat_number }}
        </button>
        @endforeach
    </div>

    <button id="bookBtn" class="btn btn-success">Book Selected Seats</button>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"> </script>
<script>
    $(document).ready(function() {
        
        $('#show_id').on('change', function() {
            
            $('#showForm').submit();
        });

        
        @if ($selectedShow)
            $.get('/api/seats/{{ $selectedShow->id }}', function(bookedSeats) {
            bookedSeats.forEach(function(seatId) {
                $('#seat-' + seatId)
                .removeClass('btn-outline-primary btn-success')
                .addClass('btn-danger')
                .prop('disabled', true);
            });
        });
        @endif

        
        $('.seat-btn').click(function(e) {
            e.preventDefault();
            if (!$(this).hasClass('btn-danger')) {
                $(this).toggleClass('btn-success btn-outline-primary');
            }
        });

        $('#bookBtn').click(function() {
            var selectedSeats = [];
            $('.seat-btn.btn-success').each(function() {
                selectedSeats.push($(this).data('id'));
            });

            if (selectedSeats.length === 0) {
                alert('Please select at least one seat.');
                return;
            }

            $.ajax({
                url: '/book',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    show_id: $('#show_id').val(),
                    seats: selectedSeats
                },
                success: function(res) {
                    alert(res.success);
                    window.location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.error || 'Booking failed!');
                }
            });
        });
    });
</script>
@endsection