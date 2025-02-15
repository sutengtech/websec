<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multiplication Tables</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <style>
        table {
            width: 100%;
        }
        td {
            padding: 5px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row d-flex flex-wrap justify-content-center">
        @foreach (range(1, 10) as $j)
            <div class="card m-2 col-md-2 col-sm-4 p-2">
                <div class="card-header text-center font-weight-bold">{{$j}} Multiplication Table</div>
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        @foreach (range(1, 10) as $i)
                            <tr>
                                <td>{{$i}} * {{$j}}</td>
                                <td>=</td>
                                <td>{{ $i * $j }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>