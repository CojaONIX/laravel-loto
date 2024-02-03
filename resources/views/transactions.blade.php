@extends('layout')

@section('title', 'Home')

@section('content')

    <form method="POST" action="{{ route('transactions.credit.add') }}">
        @csrf
        <input type="text" id="amount" name="amount" placeholder="Iznos:" autofocus value="">
        <label for="amount">Iznos:</label>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Uplati</button>
    </form>

    <form method="POST" action="{{ route('transactions.credit.withdraw') }}">
        @csrf
        <input type="text" id="amount" name="amount" placeholder="Iznos:" autofocus value="">
        <label for="amount">Isplati:</label>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Isplati</button>
    </form>

    <form method="POST" action="{{ route('transactions.credit.winnings') }}">
        @csrf
        <input type="text" id="amount" name="amount" placeholder="Dobitak:" autofocus value="">
        <label for="amount">Dobitak:</label>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Isplati Dobitak</button>
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

@endsection
