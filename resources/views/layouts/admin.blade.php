<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - DMS RS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="d-flex">
    @include('components.sidebar')

    <div class="flex-grow-1">
        @include('components.navbar')

        <main class="container-fluid p-4">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
