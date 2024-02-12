@extends('layout')

@section('title', 'Admin')


@section('content')

    <div class="row">
        <ul class="nav nav-pills flex-column col-md-2" id="menu">
            @foreach ($rounds as $round)
                <li class="round nav-link p-1"><a class="btn btn-outline-primary{{ isset($roundActive) && ($round == $roundActive) ? ' active' : '' }}" href="{{ route('admin.round.view', ['round' => $round]) }}">{{ $round }}</a></li>
            @endforeach
        </ul>

        @yield('report')

    </div>

@endsection

