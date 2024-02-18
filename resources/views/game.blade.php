@extends('layout')

@section('title', 'Game')

@section('add_css')
    <style>
        .ticket {
            width: 150px;
        }

        .ticket span {
            display: inline-block;
            width: 30px;
            text-align: center;
            border: 1px solid black;
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')

    <h3>Kredit: {{ $creditsSum }}</h3>
    <h3>Kolo: {{ $nextRound->round }} - {{ count($tickets) }} tiketa</h3>
    <h3>Vreme: {{ $nextRound->date }}</h3>
    @if($isPlayed)
        <h3>Odigrano: {{ $isPlayed->created_at }}</h3>
    @else
        <form method="POST" action="{{ route('game.ticket.add') }}">
            @csrf

            <button type="submit" class="btn btn-outline-primary">Uplati 1 tiket sa random brojevima</button>
        </form>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <hr>

    <div class="row">
    @foreach($tickets as $ticket)
        <div class="ticket mb-4">
            @for($i=1; $i<=config('loto.combination')['from']; $i++)
                @if(in_array($i, $ticket->numbers))
                    <span class="bg-primary text-white">{{ $i }}</span>
                @else
                    <span>{{ $i }}</span>
                @endif
            @endfor

        </div>
    @endforeach
    </div>

@endsection

