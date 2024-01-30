<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loto</title>
</head>

<body>

    <h3>Name: {{ Auth::user()->name }}</h3>
    <h3>Email: {{ Auth::user()->email }}</h3>

    <form method="post" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <table>
        <tr>
            <th>id</th>
            <th>type</th>
            <th>amount</th>
            <th>created_at</th>
        </tr>

        @foreach($kredits as $kredit)
            <tr>
                <th>{{ $kredit->id }}</th>
                <th>{{ $kredit->type }}</th>
                <th>{{ $kredit->amount }}</th>
                <th>{{ $kredit->created_at }}</th>
            </tr>
        @endforeach
    </table>

    <h3>Kredit: {{ $kredits->pluck('amount')->sum() }}</h3>

    <nav>
        <ul>
            <li><a href="{{ route('kredit.uplata.view') }}">Uplata kredita</a></li>
            <li><a href="{{ route('kredit.isplata.view') }}">Isplata sa kredita</a></li>
            <li><a href="{{ route('tiket.dobitak.view') }}">Isplata dobitka</a></li>
        </ul>
    </nav>

    <form method="POST" action="{{ route('tiket.uplata') }}">
        @csrf

        <button type="submit">Uplati 1 tiket sa random brojevima</button>
    </form>
</body>
</html>
