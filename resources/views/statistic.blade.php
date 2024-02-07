@extends('layout')

@section('title', 'Statistic')

@section('add_css')
    <style>
        .ticket {
            width: 100px;
        }

        .ticket span {
            display: inline-block;
            width: 30px;
            text-align: center;
            border: 1px solid black;
            margin-bottom: 5px;
        }

        .winNumbers {
            border: 1px solid red;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <ul class="nav nav-pills flex-column col-md-2" id="menu">
            @foreach ($rounds as $r)
                <li class="round nav-link p-1"><a class="btn btn-outline-primary {{ $round == $r ? 'active' : '' }}" href="{{ route('statistic.view', ['round' => $r]) }}">{{ $r }}</a></li>
            @endforeach
        </ul>

        <div class="col-md-10 d-flex justify-content-start flex-wrap" id="report">

            @isset($tickets)

                @foreach($tickets as $ticket)
                    <div class="mb-4 me-4">
                        @if($winNumbers['numbers'])
                        <h5 class="text-center">{{ $ticket->win }} - {{ number_format($ticket->paid, 2) }}</h5>
                        @endif
                        <div class="ticket ">

                            @for($i=1; $i<=10; $i++)
                                @if(in_array($i, $ticket->numbers))
                                    <span class="bg-primary text-white border border-2{{ in_array($i, $winNumbers['numbers']) ? ' border-danger' : '' }}">{{ $i }}</span>
                                @else
                                    <span class="border border-2{{ in_array($i, $winNumbers['numbers']) ? ' border-danger' : '' }}">{{ $i }}</span>
                                @endif
                            @endfor

                        </div>
                    </div>
                @endforeach


            @endisset

        </div>


    </div>



@endsection

