@extends('layout')

@section('title', 'Home')

@section('content')

    <h3>Kredit: {{ $credits->pluck('amount')->sum() }}</h3>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('transactions.credit.add') }}">
        @csrf
        <div class="d-flex flex-row col-6">
            <div class="form-floating mb-3">
                <input type="text" id="amount" class="form-control" name="amount" placeholder="Iznos:" autofocus value="">
                <label for="amount">Uplati:</label>
            </div>

            <button type="submit" class="btn btn-outline-primary m-3">Uplati</button>
        </div>
    </form>

    <form method="POST" action="{{ route('transactions.credit.withdraw') }}">
        @csrf

        <div class="d-flex flex-row col-6">
            <div class="form-floating mb-3">
                <input type="text" id="amount" class="form-control" name="amount" placeholder="Iznos:" value="">
                <label for="amount">Isplati:</label>
            </div>

            <button type="submit" class="btn btn-outline-primary m-3">Isplati</button>
        </div>
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
