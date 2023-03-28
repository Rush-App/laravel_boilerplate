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
        .margin_top_30 { margin-top: 30px; }
        .text_align_justify { text-align: justify; }
        .max_width_logo { max-width: 150px; }
        .link { text-decoration: none; color: #ffffff!important;; }
        .button {
            background-color: #2d2d2d;
            border-radius: 4px;
            border: none;
            padding: 15px 32px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer !important;
        }
    </style>
</head>
<body>
<div class="container">
    <p class="text_align_justify">Hello!</p>

    <br>

    <p class="text_align_justify">{!! __($text) !!}</p>

    <div class="margin_top_30">
        <button class="button">
            <a class="link" href="{{$reset_url}}" target="_blank">Change password</a>
        </button>
    </div>

    <div class="margin_top_30" style="background: #d9d9d9; font-size: 1px; line-height: 1px;">&nbsp;</div>

    <img alt="Logo" class="margin_top_10 max_width_logo" src="{{asset('storage/for_emails/logo.png')}}">

    <br>

    <p>E-mail: info.rushapp@gmail.com</p>
    <p>Site:
        <a href="https://rush-app.com" target="_blank">rush-app.com</a>
    </p>
</div>
</body>
</html>
