@extends('layout')

@section('title', 'Isplata sa kredita')

@section('content')
    <a href="{{ route('kredit.home') }}" class="btn btn-outline-primary">Home</a>

    <h2>Isplata sa Kredita</h2>

    <form method="POST" action="{{ route('kredit.isplata') }}">
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
@endsection
