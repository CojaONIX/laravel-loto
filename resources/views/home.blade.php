@extends('layout')

@section('title', 'Home')

@section('content')

    <h3>Name: {{ Auth::user()->name }}</h3>
    <h3>Email: {{ Auth::user()->email }}</h3>

    <form method="post" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <table class="table">
        <tr>
            <th>id</th>
            <th>type</th>
            <th>amount</th>
            <th>created_at</th>
        </tr>

        @foreach($credits as $credit)
            <tr>
                <th>{{ $credit->id }}</th>
                <th>{{ $credit->type }}</th>
                <th>{{ $credit->amount }}</th>
                <th>{{ $credit->created_at }}</th>
            </tr>
        @endforeach
    </table>

    <h3>Kredit: {{ $credits->pluck('amount')->sum() }}</h3>

    <nav>
        <ul>
            <li><a href="{{ route('kredit.uplata.view') }}">Uplata kredita</a></li>
            <li><a href="{{ route('kredit.isplata.view') }}">Isplata sa kredita</a></li>
            <li><a href="{{ route('tiket.dobitak.view') }}">Isplata dobitka</a></li>
        </ul>
    </nav>

    <h3>Kolo: {{ $round }} - {{ $ticketsNumber }} tiketa</h3>
    <h3>Vreme: {{ $date }}</h3>
    <form method="POST" action="{{ route('tiket.uplata') }}">
        @csrf

        <button type="submit">Uplati 1 tiket sa random brojevima</button>
    </form>
    @if(session('errors'))
        {{session('errors')->first()}}
    @endif

@endsection

