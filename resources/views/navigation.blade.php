<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home.view') }}">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('game.view') }}">Game</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('transactions.view') }}">Transactions</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('statistic.view') }}">Statistika</a></li>

{{--                <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Other</a>--}}
{{--                    <ul class="dropdown-menu">--}}

{{--                        <li><hr class="dropdown-divider"></li>--}}

{{--                        <li><a class="dropdown-item" href="{{ route('welcome.view') }}">Welcome</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
            </ul>

{{--            <ul class="navbar-nav me-auto mb-2 mb-lg-0">--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ route('test.view') }}">Test</a></li>--}}
{{--                <li class="nav-item"><a class="nav-link" href="{{ route('admin.view') }}">Admin</a></li>--}}
{{--            </ul>--}}

            @auth
                <div class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Hello, {{ Auth::user()->name}}</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="post" action="/logout" class="dropdown-item">
                                @csrf
                                <button class="btn btn-outline-danger col-12" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="/login" class="btn btn-outline-primary ms-3">Login</a>
            @endauth

        </div>
    </div>
</nav>
