<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to DiaryNote</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico?v=2') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome/style.css') }}">
</head>

<body>
    <div class="container">
        <img src="{{ asset('image/Diary Notes 1 (1).png') }}" alt="Logo" class="logo">
        <div id="quote"></div>
        <a href="{{ route('login') }}" class="login-btn">Start Writing</a>
    </div>

    <script src="{{ asset('js/quotes.js') }}"></script>
</body>

</html>
