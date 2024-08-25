<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>ホーム画面</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body>
        <x-app-layout>
            <div class="icon">
                <img src="{{ asset('/image/Cafe Charge Finder.png') }}">
            </div>
            
            <div class="start">
                <button onclick='getLocation()'>検索スタート</button>
            </div>
            
            <div class='registration'>
                <a href='/finder/regist'><button>店舗登録</button></a>
            </div>
        </x-app-layout>
        
        <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ env('GOOGLE_MAP_API') }}"></script>
        
        <script>
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // サーバーに位置情報を送信
                        fetch('/save-location', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            // 位置情報送信後にリダイレクト
                            window.location.href = '/finder/result';
                        })
                        .catch(error => console.error('Error:', error));
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }
        </script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

    </body>
</html>