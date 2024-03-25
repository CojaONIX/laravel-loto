@extends('layout')

@section('title', 'Game')

@section('add_install')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection

@section('add_css')
    <style>
        .ticket {
            width: 130px;
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

    <div class="row">
        <div class="col-6">
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
        </div>

        <div class="col-6">
            <div id="newTicket" class="ticket">
                @for($i=1; $i<=config('loto.combination.from'); $i++)
                    <span class="border border-2">{{ $i }}</span>
                @endfor
            </div>

            <button id="btnRandomize" class="btn btn-outline-primary">Randomize</button>
            <p id="info"></p>
        </div>
    </div>

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

@section('js')
    <script>
        $(document).ready(function() {

            $('#btnRandomize').click(function(){
                $('#newTicket span').removeClass('bg-primary text-white');
                let numbers = [];
                while (numbers.length < {{ config('loto.combination')['find'] }}) {
                    let number = Math.floor(Math.random() * {{ config('loto.combination')['from'] }}) + 1;
                    if(numbers.includes(number)) {
                        continue;
                    }
                    numbers.push(number);
                    $('#newTicket span').eq(number - 1).addClass('bg-primary text-white');
                }
                $('#info').text(numbers);
            });
        });

    </script>
@endsection
