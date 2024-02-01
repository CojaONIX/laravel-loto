@extends('layout')

@section('title', 'Uplata kredita')

@section('content')
    <a href="{{ route('kredit.home') }}">Home</a>

    <h2>Uplata Kredita</h2>

    <form method="POST" action="{{ route('kredit.uplata') }}">
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
@endsection
