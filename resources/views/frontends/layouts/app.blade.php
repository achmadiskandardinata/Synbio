<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? config('app.name')}} - Synbio</title>

    @include('frontends.layouts.partials.style')
</head>

<body>
    <!-- navbar -->
    @include('frontends.layouts.partials.navbar')
    <!--navbar -->

    @yield('content')

    <!-- footer -->
    @include('frontends.layouts.partials.footer')
    <!-- footer -->
</body>

</html>
