@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="card w-100 mt-5">
                <div class="card-body">
                    <h3 class="card-title">
                        <div class="dropdown show">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ request()->is('*/inactive') ? 'Inactieve' : 'Actieve' }}
                            </a>
                            reserveringen
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @if(request()->is('*/inactive'))
                                    <a class="dropdown-item" href="{{ url('reservations') }}">Actief</a>
                                @else
                                    <a class="dropdown-item" href="{{ url('reservations/inactive') }}">Inactief</a>
                                @endif
                            </div>
                        </div>
                    </h3>
                    <form method="post" action="{{ url('reservations/search') }}" class="row">
                        @csrf
                        <input class="offset-lg-6 col-lg-3 form-control" type="text" name="last_name" placeholder="Achternaam" value="{{ session('portal.registrations.last_name') ? session('portal.registrations.last_name') : '' }}">
                        <input class="col-lg-3 form-control" type="text" name="number" placeholder="Reservering nummer" value="{{ session('portal.registrations.number') ? session('portal.registrations.number') : '' }}">
                    </form>
                    <br>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Gast</th>
                                <th>Nummer</th>
                                <th>Tafelnummers</th>
                                <th>Datum</th>
                                <th>Start</th>
                                <th>Eind</th>
                                <th>Dieetwensen</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->user->last_name }}</td>
                                <td>{{ $reservation->number }}</td>
                                <td>{{ implode(', ', $reservation->tables->pluck('id')->toArray()) }}</td>
                                <td>{{ $reservation->date_string }}</td>
                                <td>{{ $reservation->tables->first()->start_time }}</td>
                                <td>{{ $reservation->tables->first()->end_time }}</td>
                                <td class="text-truncate">{{ $reservation->diet }}</td>
                                <td><i class="fa fa-download"></i></td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $('input').on('change', function () {
            $(this).closest("form").submit();
        })
    </script>
@stop