@extends('layout')

@section('title', 'Admin')

@section('content')
    <form method="POST" action="{{ route('transactions.credit.winnings') }}">
        @csrf
        <div class="d-flex flex-row col-6">
            <div class="form-floating mb-3">
                <input type="text" id="amount" class="form-control" name="amount" placeholder="Dobitak:" autofocus value="">
                <label for="amount">Dobitak:</label>
            </div>

            <button type="submit" class="btn btn-outline-primary m-3">Isplati Dobitak</button>
        </div>
    </form>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
