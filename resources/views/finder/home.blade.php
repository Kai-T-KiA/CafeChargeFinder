<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>ホーム画面</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body>
        
        <div class="icon">
            <img src="{{ asset('/image/Cafe Charge Finder.png') }}">
        </div>
        
        <div class="start">
            <a href='/finder/result'><button>検索スタート</button></a>
        </div>
        
        <div class='registration'>
            <a href='/finder/regist'>店舗登録</a>
        </div>
        
        <script>
        
            
        </script>

    </body>
</html>