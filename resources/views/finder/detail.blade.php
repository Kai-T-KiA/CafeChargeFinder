<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>検索結果</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body class="font-sans bg-gray-100">
        <x-app-layout>
            <div id="map" class="w-[80%] h-80 md:h-96 rounded-lg shadow-lg mx-auto mt-5 mb-5">
                
            </div>
            
            <div class='charge_spot_location p-4 bg-white rounded-lg w-[80%] mx-auto mb-2.5'>
                <h3 class="text-xl font-semibold mb-4">コンセントの場所</h3>
                @foreach ($posts as $post)
                  <p class="text-base mb-2">{{ $post->body }}</p>
                @endforeach
            </div>
            
            <div class="detail p-4 bg-white rounded-lg w-[80%] mx-auto mb-2.5">
                <h3 class="text-xl font-semibold mb-2">{{ $place->name }}</h3>
                <p class="text-base mb-2">住所: {{ $place->address }}</p>
                <ul class="list-disc list-inside">
                   @if($place->opening_hours)
                        @php
                            $openingHours = json_decode($place->opening_hours, true);
                            // dump($place->opening_hours);
                            // dd($openingHours); // デコード後の配列を表示
                        @endphp
                        @if(isset($openingHours))
                            @foreach ($openingHours as $text)
                                <li class="text-base">{{ $text }}</li>
                            @endforeach
                        @else
                            <li class="text-base">営業時間の情報がありません</li>
                        @endif
                        
                    @else
                        <li class="text-base">営業時間の情報がありません</li>
                    @endif
                </ul>
            </div>
            
            <p id='duration' class="p-4 text-base text-gray-700 bg-white rounded-lg w-[80%] mx-auto mb-2.5"></p>
            
            
            <div class="footer text-center m-5">
                <a href="/finder/result" class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">戻る</a>
            </div>
        </x-app-layout>
        
        <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ env('GOOGLE_MAP_API') }}&libraries=marker&callback=initMap"></script>
        <!--googel mapの表示処理--> 
        <script>
            var place = @json($place);
            
            var target_location = { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude)};
            
            window.initMap = function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                          function(position) {
                                //ユーザーの位置情報
                                var position = {   
                                  lat: position.coords.latitude,
                                  lng: position.coords.longitude
                                };
                                
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 15,
                                    center: target_location,
                                    mapId: '7f1c3686cbc93098'
                                });
                                
                                // DirectionsServiceとDirectionsRendererの作成
                                var directionsService = new google.maps.DirectionsService();
                                var directionsRenderer = new google.maps.DirectionsRenderer();
                                directionsRenderer.setMap(map);
                                
                                // ユーザーの現在位置を示すマーカー
                                const userMarkerElement = document.createElement("div");
                                userMarkerElement.textContent = "You are here!";
                                userMarkerElement.style.background = "red";
                                userMarkerElement.style.color = "white";
                                userMarkerElement.style.padding = "5px";
                                
                                var user_marker = new google.maps.marker.AdvancedMarkerElement({
                                    position: position,
                                    map: map
                                });
                                
                                // データベースに登録されている地点をを示すマーカー
                                const markerElement = document.createElement("div");
                                markerElement.textContent = place.name;
                                markerElement.style.background = "blue";
                                markerElement.style.color = "white";
                                markerElement.style.padding = "5px";
                                   
                                var marker = new google.maps.marker.AdvancedMarkerElement({
                                   position: target_location,
                                   map: map,
                                   title: place.name
                                }); 
                                
                                // ルートを描画し、徒歩時間を表示する関数を呼び出す
                                calculateRouteAndDuration(directionsService, directionsRenderer, position);
                                
                          }
                    );
                }
            }
            
            // ルートを計算して表示し、徒歩時間も表示する関数
            function calculateRouteAndDuration(directionsService, directionsRenderer, position) {
                var request = {
                    origin: position,
                    destination: target_location,
                    travelMode: 'WALKING' // 他に 'DRIVING', 'BICYCLING', 'TRANSIT' も選択可能
                };
        
                directionsService.route(request, function (result, status) {
                    if (status === 'OK') {
                        directionsRenderer.setDirections(result);
        
                        // ルート結果から徒歩時間を取得
                        var route = result.routes[0].legs[0];
                        var duration = route.duration.text;
                        
                        console.log(route);
                        console.log(duration);
        
                        // 徒歩時間を表示
                        document.getElementById('duration').innerText = '徒歩時間： ' + duration;
                    } else {
                        document.getElementById('duration').innerText = 'ルートを計算できませんでした: ' + status;
                    }
                });
            }
            
            
        
            
        </script>

    </body>
</html>