<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Isplata sa Kredita</title>
</head>
<body>
    <a href="{{ route('kredit.home') }}">Home</a>

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
</body>
</html>
