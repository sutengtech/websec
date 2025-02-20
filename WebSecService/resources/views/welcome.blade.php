<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multiplication Table</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</head>

<body>
    <div class="container text-center mt-5">
        <h1 class="text-primary">Multiplication Table bootstrap Styled</h1>
        <p class="lead">This is a simple Multiplication table using bootstrap.</p>
    </div>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Multiplication Table</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>&times;</th>
                        @for ($col = 1; $col <= 9; $col++)
                            <th>{{ $col }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @for ($row = 1; $row <= 5; $row++)
                        <tr>
                            <th class="table-secondary">{{ $row }}</th>
                            @for ($col = 1; $col <= 9; $col++)
                                <td>{{ $row * $col }}</td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
