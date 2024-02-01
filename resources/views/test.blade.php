@extends('layout')

@section('title', 'Test')

@section('add_install')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        .query {
            cursor: pointer;

        }
        .query:hover {
            background-color: #ddd;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between flex-nowrap">
        <ul class="nav nav-pills flex-column col-2" id="menu">
            @foreach ($buttons as $btn)
                <li class="query nav-link p-1">{{$btn}}</li>
            @endforeach
        </ul>
        <div class="col-9">
            <input type="text" class="form-control col-12 mb-3" id="item">
            <pre id="data"></pre>
        </div>
    </div>

@endsection


@section('js')
    <script>
        $(document).ready(function() {

            $('.query').click(function(){
                $('.query').removeClass('active');
                $(this).addClass('active');
                query = $(this).text();
                $.ajax({
                    type: 'POST',
                    url: '/test',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        action: query,
                        item: $('#item').val()
                    },
                    success: function (data) {
                        $('#data').text(JSON.stringify(data, undefined, 4));
                    },
                    error: function (data) {
                        $('#data').text(JSON.stringify(data, undefined, 4));
                    }
                });
            });

        });
    </script>
@endsection
