<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>E-mail to admin EN</title>

    <!-- Fonts -->

    <!-- Styles -->
    <style>
        p { font-size: 12px; font-family: sans-serif, serif; line-height: 1.3; margin: 0; padding: 0; }
        .margin_top_10 { margin-top: 10px; }
        .text_align_justify { text-align: justify; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <p class="bold">Request from the user!</p>

    <p class="text_align_justify margin_top_10">Name: {{$name}}</p>
    <p class="text_align_justify margin_top_10">Message: {{$text}}</p>
    <p class="text_align_justify margin_top_10">Email: {{$email}}</p>
</div>
</body>
</html>
