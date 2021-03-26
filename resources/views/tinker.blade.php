<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BotMan Studio</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">

    <!--<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script> -->


    <!-- Styles -->
    <style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            margin: 0;
            padding: 0;
            background: #27ae60;
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .content {
            text-align: center;
        }
        .ChatLog__message
        {
           background: #FFF;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content" id="app">
        <botman-tinker api-endpoint="/botman"></botman-tinker>
    </div>
</div>

<script src="/js/app.js"></script>
</body>
</html>
