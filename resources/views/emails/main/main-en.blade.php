<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        p { font-size: 12px; font-family: sans-serif, serif; line-height: 1.3; margin: 0; padding: 0; }
        hr { border: none; height: 2px; }
        .bold { font-weight: bolder; }
        .margin_top_10 { margin-top: 10px; }
        .text_align_justify { text-align: justify; }
        .max_width_logo { max-width: 150px; }
    </style>
</head>
<body>
<div class="container">
    @if (empty($user))
        <p class="text_align_justify">Hello!</p>
    @else
        <p class="text_align_justify">Hello, {{ $user->name }}</p>
    @endif

    <br>

    <p class="text_align_justify">{!! __($text) !!}</p>

    <div class="margin_top_10" style="background: #d9d9d9; font-size: 1px; line-height: 1px;">&nbsp;</div>

    <img alt="RushApp team" class="bold margin_top_10 max_width_logo" src="{{asset('storage/for_emails/logo.png')}}">

    <br>

    <p>E-mail: info.rushapp@gmail.com</p>
    <p>Site:
        <a href="https://rush-app.com" target="_blank">rush-app.com</a>
    </p>
</div>
</body>
</html>
