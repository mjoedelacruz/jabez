<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JABEZ</title>
     <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="{{url('manifest.json')}}">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#2c3e50">
    <meta name="theme-color" content="#ffffff">
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700"> --}}
    <link rel="stylesheet" href="{{url('css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{url('css/elephant.min.css')}}">
    <link rel="stylesheet" href="{{url('css/application.min.css')}}">
    <link rel="stylesheet" href="{{url('css/demo.min.css')}}">
</head>