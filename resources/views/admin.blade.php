@extends('layout')

@section('title', 'Admin')

@section('add_install')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection

@section('add_css')
    <style>
        .round {
            cursor: pointer;
        }
        .round:hover {
            background-color: #ddd;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between flex-nowrap">
        <ul class="nav nav-pills flex-column col-2" id="menu">
            @foreach ($rounds as $round)
                <li class="round nav-link p-1">{{$round}}</li>
            @endforeach
        </ul>
        <div class="col-9" id="report">

        </div>
    </div>

@endsection


@section('js')
    <script>
        $(document).ready(function() {

            $('.round').click(function(){
                $('.round').removeClass('active');
                $(this).addClass('active');
                round = $(this).text();
                $.ajax({
                    type: 'POST',
                    url: '/admin/report',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        round: round,
                    },
                    success: function (data) {
                        $('#report').html('');
                        $('#report').append('<h3>Kolo: ' + data.round + '</h3>')
                        $('#report').append('<h3>Uplaceno tiketa: ' + data.ticketsCount + '</h3>')
                        $('#report').append('<h3>Vrednost uplate: ' + data.ticketsValue + '</h3>')

                        $.each(data.quotas,function(index, value){
                            $('#report').append('<h3>' + index + ' - ' + value + '% = ' + data.ticketsValue / 100 * value + '</h3>');
                        });
                    },
                    error: function (data) {
                        alert(JSON.stringify(data, undefined, 4));
                    }
                });
            });

        });
    </script>
@endsection
