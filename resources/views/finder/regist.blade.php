<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>店舗登録</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body>
        
        <div class="detail">
            <h1>新店舗登録</h1>
            <form action="/posts" method="POST">
                @csrf
                <div class="name">
                    <h2>店舗名</h2>
                    <input type="text" name="place[name]" placeholder="店舗名"/>
                </div>
                <div class="information">
                    <div class='title'>
                        <h2>タイトル</h2>
                        <input type='text' name="post[title]" placeholder='タイトル'>
                    </div>
                    <div class='body'>
                        <h2>本文</h2>
                        <textarea name="post[body]" placeholder="コンセント情報"></textarea>
                    </div>
                </div>
                <input type="submit" value="登録"/>
            </form>
        </div>
        
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        
        <script>
        
            
        </script>

    </body>
</html>