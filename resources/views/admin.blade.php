@extends('layout')

@section('title', 'Admin')

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

    <div class="row">
        <ul class="nav nav-pills flex-column col-md-2" id="menu">
            @foreach ($rounds as $r)
                <li class="round nav-link p-1"><a class="btn btn-outline-primary {{ $round == $r ? 'active' : '' }}" href="{{ route('admin.view', ['round' => $r]) }}">{{ $r }}</a></li>
            @endforeach
        </ul>

        <div class="col-md-10 row" id="report">

            @isset($report)
                <div class="col-md-4">
                    <table class="table">
                        <tr>
                            <th>Kolo:</th>
                            <td></td>
                            <td class="text-end">{{ $round }}</td>
                        </tr>
                        <tr>
                            <th>Uplaceno tiketa:</th>
                            <td></td>
                            <td class="text-end">{{ $report['ticketsCount'] }}</td>
                        </tr>
                        <tr>
                            <th>Vrednost uplate:</th>
                            <td></td>
                            <td class="text-end">{{ number_format($report['ticketsValue'], 2) }}</td>
                        </tr>
                        <tr>
                            <th>Bank:</th>
                            <td>{{ $report['bank']['percentage'] }}%</td>
                            <td class="text-end">{{ number_format($report['bank']['value'], 2) }}</td>
                        </tr>

                        @foreach($report['wins'] as $k => $p)
                            <tr>
                                <th>{{ $k }}</th>
                                <td>{{ $report['wins'][$k]['percentage'] }}%</td>
                                <td class="text-end">{{ number_format($report['wins'][$k]['value'], 2) }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <th>Prenet fond za 5:</th>
                            <td></td>
                            <td class="text-end">{{ number_format($report['fundIN'], 2) }}</td>
                        </tr>

                        <tr>
                            <th>Ukupno fond za 5:</th>
                            <td></td>
                            <td class="text-end">{{ number_format($report['wins'][5]['value'] + $report['fundIN'], 2) }}</td>
                        </tr>

                    </table>
                </div>

                @isset($report['played'])
                    <div class="ticket col-md-4">
                        @for($i=1; $i<=10; $i++)
                            @if(in_array($i, $report['played']['numbers']))
                                <span class="bg-primary text-white">{{ $i }}</span>
                            @else
                                <span>{{ $i }}</span>
                            @endif
                        @endfor
                    </div>

                    <div class="col-md-4">
                        <table class="table">
                            @foreach($report['played']['report'] as $k => $v)
                            <tr>
                                <th>{{ $k }}</th>
                                <td>{{ $v['wins'] }}</td>
                                <td class="text-end">{{ number_format($v['value'], 2) }}</td>
                            </tr>
                            @endforeach

                            <tr>
                                <th>Prenos:</th>
                                <td></td>
                                <td class="text-end">{{ number_format($report['fundOUT'], 2) }}</td>
                            </tr>
                        </table>
                    </div>

                @else
                    <div class="ticket col-md-4">
                        @for($i=1; $i<=10; $i++)
                            <span>{{ $i }}</span>
                        @endfor

                        <form method="POST" action="{{ route('admin.roll') }}">
                            @csrf
                            <input type="hidden" name="round" value="{{ $round }}">
                            <button type="submit" class="btn btn-outline-primary col-9">ROLL</button>
                        </form>
                    </div>

                @endisset

            @endisset

        </div>

    </div>

@endsection

