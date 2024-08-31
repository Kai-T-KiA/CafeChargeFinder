<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>店舗登録</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body class="bg-gray-100 font-sans"> 
        <x-app-layout>
            <div class="detail max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
                <h1 class="text-2xl font-bold mb-6">新店舗登録</h1>
                <form action="/posts" method="POST" class="space-y-4">
                    @csrf
                    <div class="name">
                        <h2 class="text-lg font-semibold mb-2">店舗名</h2>
                        <input type="text" name="place[name]" placeholder="店舗名" class="w-full p-2 border border-gray-300 rounded"/>
                    </div>
                    <div class="information space-y-4">
                        <div class='title'>
                            <h2 class="text-lg font-semibold mb-2">タイトル</h2>
                            <input type='text' name="post[title]" placeholder='タイトル' class="w-full p-2 border border-gray-300 rounded">
                        </div>
                        <div class='body'>
                            <h2 class="text-lg font-semibold mb-2">本文</h2>
                            <textarea name="post[body]" placeholder="コンセント情報" class="w-full p-2 border border-gray-300 rounded"></textarea>
                        </div>
                    </div>
                    <input type="submit" value="登録" class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700 cursor-pointer"/>
                </form>
            </div>
            
            <div class="footer text-center mt-8">
                <a href="/finder/home" class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">戻る</a>
            </div>
        </x-app-layout>

    </body>
</html>