@extends('layout')

@section('title', 'Statistic')

@section('content')

    <div class="row">
        <div class="col-md-3 row">
            <div class="col-6">
                <ul class="nav nav-pills flex-column" id="menu">
                    @foreach ($rounds as $round)
                        <li class="round nav-link p-1"><a class="btn btn-outline-primary{{ isset($roundActive) && ($round == $roundActive) ? ' active' : '' }}" href="{{ route('statistic.round.view', ['round' => $round]) }}">{{ $round }}</a></li>
                    @endforeach
                </ul>
            </div>
            @yield('winNumbers')
        </div>

        @yield('tickets')

    </div>

@endsection

