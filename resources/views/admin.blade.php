@extends('layout')

@section('title', 'Admin')

@section('content')

    <div class="d-flex justify-content-between flex-nowrap">
        <ul class="nav nav-pills flex-column col-2" id="menu">
            @foreach ($report['rounds'] as $r)
                <li class="round nav-link p-1"><a class="btn btn-outline-primary {{ $report['rounds'] == $r ? 'active' : '' }}" href="{{ route('admin.view', ['round' => $r]) }}">{{ $r }}</a></li>
            @endforeach
        </ul>
        <div class="col-6" id="report">
            <table class="table">
                <tr>
                    <th>Kolo:</th>
                    <td></td>
                    <td class="text-end">{{ $report['round'] }}</td>
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
                    <td class="text-end">{{ number_format($report['transfer'], 2) }}</td>
                </tr>

                <tr>
                    <th>Ukupno fond za 5:</th>
                    <td></td>
                    <td class="text-end">{{ number_format($report['wins'][5]['value'] + $report['transfer'], 2) }}</td>
                </tr>

            </table>

            <hr>
            @if($report['played'])
                <h3>Ovo kolo je odigrano {{ $report['played'] }}</h3>
            @else
                <form method="POST" action="{{ route('admin.roll') }}">
                    @csrf
                    <input type="hidden" name="round" value="{{ $report['round'] }}">
                    <button type="submit" class="btn btn-outline-primary">Izvlacenje brojeva</button>
                </form>
            @endif
        </div>
    </div>



@endsection

