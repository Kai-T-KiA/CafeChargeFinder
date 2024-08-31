<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>ホーム画面</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <!--@vite('resources/css/app.css')-->
    </head>
    <body>
        <x-app-layout>
            <div class="">
                <div class="icon" style='width: 80%; margin: auto;'>
                    <img src="{{ asset('/image/Cafe charge finder2.png') }}" class="object-contain max-w-full h-auto p-4" style='width: 65%; margin: auto;'>
                </div>
                
                <div class="flex-1 w-full md:w-1/2" style='width: 80%; margin: auto;'>
                    <div class="radius" style="text-align: center; margin-bottom: 10px;">
                        <p>検索範囲（現在位置からの距離）</p>
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <label><input class="radius-checkbox" type="radio" name="radius" value="0" checked> 制限なし</label>
                            <label><input class="radius-checkbox" type="radio" name="radius" value="500"> 500m圏内</label>
                            <label><input class="radius-checkbox" type="radio" name="radius" value="1000"> 1km圏内</label>
                            <label><input class="radius-checkbox" type="radio" name="radius" value="1500"> 1.5km圏内</label>
                        </div>
                    </div>
                    
                    <div class="start mb-4" style="text-align: center;">
                        <button onclick='getLocation()' class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">
                            検索スタート
                        </button>
                    </div>
                    
                    <div class='registration' style="text-align: center;">
                        <a href='/finder/regist'>
                            <button class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">
                                店舗登録
                            </button>
                        </a>
                    </div> 
                </div>
            </div>
        </x-app-layout>
        
        <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ env('GOOGLE_MAP_API') }}"></script>
        
        <script>
            document.querySelectorAll('.radius-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        document.querySelectorAll('.radius-checkbox').forEach(cb => {
                            if (cb !== this) {
                                cb.checked = false;
                            }
                        });
                    }
                });
            });
            
            
            
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // 選択されたチェックボックスの半径を取得
                        const radius = document.querySelector('input[name="radius"]:checked').value;
                        
                        // サーバーに位置情報を送信
                        fetch('/save-location', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude,
                                radius: radius
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