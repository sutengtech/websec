<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assignment Day 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <div class="d-flex flex-column justify-content-center align-items-center ">
        <h1>Name <span class="badge text-bg-secondary">Mohammed Tarek Sayed</span></h1>
        <h2>ID <span class="badge text-bg-secondary">230102535</span></h2>  
    </div>
<div class="d-flex p-2 flex-wrap">
@php($j = 1)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>
@php($j = 2)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>
@php($j = 3)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>

</div class="">

@php($j = 4)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>

</div>
@php($j = 5)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>

@php($j = 6)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>

@php($j = 7)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>
@php($j = 8)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>
@php($j = 9)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>
@php($j = 10)
<div class="card m-4 col-sm-2">
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
        <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
        @endforeach
        </table>
    </div>
</div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
