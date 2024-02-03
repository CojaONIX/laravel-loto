@extends('layout')

@section('title', 'Game')

@section('content')

    <h3>Name: {{ Auth::user()->name }}</h3>
    <h3>Email: {{ Auth::user()->email }}</h3>

    <h3>Kredit: {{ $credits->pluck('amount')->sum() }}</h3>
    <h3>Kolo: {{ $round }} - {{ $ticketsNumber }} tiketa</h3>
    <h3>Vreme: {{ $date }}</h3>
    <form method="POST" action="{{ route('game.ticket.add') }}">
        @csrf

        <button type="submit" class="btn btn-outline-primary">Uplati 1 tiket sa random brojevima</button>
    </form>
    @if(session('errors'))
        {{session('errors')->first()}}
    @endif

@endsection

