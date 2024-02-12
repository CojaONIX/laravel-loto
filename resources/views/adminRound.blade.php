@extends('admin')

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

@section('report')

    <div class="col-md-10 row" id="report">
        <div class="col-md-4">
            <table class="table">
                <tr>
                    <th>Kolo:</th>
                    <td></td>
                    <td class="text-end">{{ $roundActive }}</td>
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
                    <td class="text-end">{{ number_format($report['bank']['fund'], 2) }}</td>
                </tr>

                @foreach($report['wins']['percentages'] as $win => $percentage)
                    <tr>
                        <th>{{ $win }}</th>
                        <td>{{ $percentage }}%</td>
                        <td class="text-end">{{ number_format($report['wins']['funds'][$win], 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th>Prenet fond za {{ max(array_keys(config('loto.wins.percentages'))) }}:</th>
                    <td></td>
                    <td class="text-end">{{ number_format($report['fundIN'], 2) }}</td>
                </tr>

                <tr>
                    <th>Ukupno fond za {{ max(array_keys(config('loto.wins.percentages'))) }}:</th>
                    <td></td>
                    <td class="text-end">{{ number_format($report['wins']['funds'][max(array_keys(config('loto.wins.percentages')))] + $report['fundIN'], 2) }}</td>
                </tr>

            </table>
        </div>

        @isset($report['wins']['paids'])
            <div class="ticket col-md-4">
                @for($i=1; $i<=config('loto.combination')['from']; $i++)
                    @if(in_array($i, $numbers))
                        <span class="bg-primary text-white">{{ $i }}</span>
                    @else
                        <span>{{ $i }}</span>
                    @endif
                @endfor
            </div>

            <div class="col-md-4">
                <table class="table">
                    @foreach($report['wins']['counts'] as $k => $v)
                    <tr>
                        <th>{{ $k }}</th>
                        <td>{{ $v }}</td>
                        <td class="text-end">{{ number_format($report['wins']['paids'][$k], 2) }}</td>
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
                @for($i=1; $i<=config('loto.combination')['from']; $i++)
                    <span>{{ $i }}</span>
                @endfor

                <form method="POST" action="{{ route('admin.roll') }}">
                    @csrf
                    <input type="hidden" name="round" value="{{ $roundActive }}">
                    <button type="submit" class="btn btn-outline-primary col-9">ROLL</button>
                </form>
            </div>

        @endisset

    </div>


@endsection

