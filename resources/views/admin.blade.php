@extends('layout')

@section('title', 'Admin')

@section('content')

    <div class="d-flex justify-content-between flex-nowrap">
        <ul class="nav nav-pills flex-column col-2" id="menu">
            @foreach ($rounds as $r)
                <li class="round nav-link p-1"><a class="btn btn-outline-primary {{ $round == $r ? 'active' : '' }}" href="{{ route('admin.view', ['round' => $r]) }}">{{ $r }}</a></li>
            @endforeach
        </ul>
        <div class="col-9" id="report">
            @isset($ticketsCount)
                <h3>Kolo: {{ $round }}</h3>
                <h3>Uplaceno tiketa: {{ $ticketsCount }}</h3>
                <h3>Vrednost uplate: {{ $ticketsValue }}</h3>
                @foreach($quotas as $k => $p)
                    <h3>{{ $k }} - {{ $p }}% = {{ $ticketsValue / 100 * $p }}</h3>
                @endforeach

                <hr>
                @if($isRoundOld)
                    <h3>Ovo kolo je odigrano {{ $isRoundOld->created_at }}</h3>
                @else
                    <form method="POST" action="{{ route('admin.roll') }}">
                        @csrf
                        <input type="hidden" name="round" value="{{ $round }}">
                        <input type="hidden" name="bank" value="{{ $ticketsValue / 100 * $quotas['bank']}}">
                        <button type="submit" class="btn btn-outline-primary">Izvlacenje brojeva</button>
                    </form>
                @endif
            @endisset
        </div>
    </div>



@endsection

