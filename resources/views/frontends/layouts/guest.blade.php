<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title ?? config('app.name')}} - Synbio</title>

    <!-- ini favicon -->
    <link rel="shortcut icon" href="{{asset('frontends/Assets/faficon.png')}}" type="image/x-icon">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{asset('frontends/css/bootstrap.min.css')}}">

    <!-- bootstrap js -->
    <script src="{{asset('frontends/js/bootstrap.min.js')}}"></script>

    <!-- css -->
    <link rel="stylesheet" href="{{asset('frontends/style.css')}}">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    @yield ('content')
</body>

</html>
