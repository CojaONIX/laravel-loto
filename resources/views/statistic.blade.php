@extends('layout')

@section('title', 'Statistic')

@section('add_install')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection

@section('add_css')
    <style>
        .ticket {
            width: 100px;
            cursor: zoom-in;
        }

        .ticket span {
            display: inline-block;
            width: 30px;
            text-align: center;
            margin-bottom: 5px;
        }

    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-3 row">
            <div class="col-6">
                <ul class="nav nav-pills flex-column" id="menu">
                    @foreach ($rounds as $r)
                        <li class="round nav-link p-1"><a class="btn btn-outline-primary {{ $round == $r ? 'active' : '' }}" href="{{ route('statistic.view', ['round' => $r]) }}">{{ $r }}</a></li>
                    @endforeach
                </ul>
            </div>

            @isset($winNumbers)
            <div class="col-6">
                <div class="ticket">
                    @for($i=1; $i<=config('loto.combination')['from']; $i++)
                        <span class="border border-2{{ in_array($i, $winNumbers['numbers']) ? ' bg-danger text-white' : '' }}">{{ $i }}</span>
                    @endfor
                </div>
            </div>
            @endisset
        </div>

        <div class="col-md-9 d-flex justify-content-start flex-wrap" id="report">

            @isset($tickets)
                @foreach($tickets as $ticket)
                    <div class="mb-4 me-4">
                        <div class="ticket ">
                            @for($i=1; $i<=config('loto.combination')['from']; $i++)
                                <span class="border border-2{{ in_array($i, $ticket->numbers) ? ' bg-primary text-white' : '' }}">{{ $i }}</span>
                            @endfor
                        </div>
                        @if($winNumbers['numbers'])
                            <h5 class="text-center">{{ $ticket->win }} - {{ number_format($ticket->paid, 2) }}</h5>
                        @endif
                    </div>
                @endforeach

            @endisset

        </div>


    </div>

@endsection

@isset($winNumbers['numbers'])
    @section('js')
    <script>
        $(document).ready(function() {

            var winNumbers = {{ json_encode($winNumbers['numbers']) }};
            $('.ticket').hover(function(){
                for(i=0; i<winNumbers.length; i++) {
                    $(this).find('span').eq(winNumbers[i]-1).toggleClass("border-danger");
                }
            });
        });

    </script>
    @endsection
@endisset

