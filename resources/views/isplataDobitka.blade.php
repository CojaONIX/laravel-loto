@extends('layout')

@section('title', 'Isplata Dobitka')

@section('content')
    <a href="{{ route('kredit.home') }}">Home</a>

    <h2>Isplata Dobitka</h2>

    <form method="POST" action="{{ route('tiket.dobitak') }}">
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
@endsection
