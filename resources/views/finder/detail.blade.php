<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>検索結果</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body onload="initMap()" class="font-sans bg-gray-100">
        <x-app-layout>
            <div id="map" class="w-full h-72 sm:h-64 md:h-80 lg:h-96 rounded-lg"  style="height: 400px; width: 80%; margin: auto; margin-top: 20px; margin-bottom: 20px;">
                
            </div>
            
            <div class='charge_spot_location p-4 bg-white rounded-lg' style="margin: auto; width: 80%; margin-bottom: 10px;">
                <h3 class="text-xl font-semibold mb-4">コンセントの場所</h3>
                @foreach ($posts as $post)
                  <p class="text-base mb-2">{{ $post->body }}</p>
                @endforeach
            </div>
            
            <div class="detail p-4 bg-white rounded-lg" style="margin: auto; width: 80%; margin-bottom: 10px">
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
            
            <p id='duration' class="p-4 text-base text-gray-700 bg-white rounded-lg" style="margin: auto; width: 80%; margin-bottom: 10px;"></p>
            
            
            <div class="footer text-center" style="margin: 20px;">
                <a href="/finder/result" class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">戻る</a>
            </div>
        </x-app-layout>
        
        <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ env('GOOGLE_MAP_API') }}&libraries=marker&callback=initMap"></script>
        <!--googel mapの表示処理--> 
        <script>
            var place = @json($place);
            
            var target_location = { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude)};
            var origin;
            
            
            function initMap() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                          function(position) {
                                var location = {
                                  lat: position.coords.latitude,
                                  lng: position.coords.longitude
                                };
                                
                                origin = location;
                                
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 15,
                                    center: target_location,
                                    mapId: '7f1c3686cbc93098'
                                });
                                
                                // ユーザーの現在位置を示すマーカー
                                const userMarkerElement = document.createElement("div");
                                userMarkerElement.textContent = "You are here!";
                                userMarkerElement.style.background = "red";
                                userMarkerElement.style.color = "white";
                                userMarkerElement.style.padding = "5px";
                                
                                var user_marker = new google.maps.marker.AdvancedMarkerElement({
                                    position: location,
                                    map: map
                                });
                                
                                // データベースに登録されている地点をを示すマーカー
                                const markerElement = document.createElement("div");
                                markerElement.textContent = place.name;
                                markerElement.style.background = "blue";
                                markerElement.style.color = "white";
                                markerElement.style.padding = "5px";
                                   
                                var marker = new google.maps.marker.AdvancedMarkerElement({
                                   // position: { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude) },
                                   position: target_location,
                                   map: map,
                                   title: place.name
                                }); 
                                
                          }
                    );
                }
            }
            
            function calculateDuration() {
                var service = new google.maps.DistanceMatrixService();
                
                // DistanceMatrixAPIリクエスト
                service.getDistanceMatrix(
                  {
                    origins: [origin],
                    destinations: [target_location],
                    travelMode: 'WALKING'
                  },
                  function(response, status) {
                    if (status === google.maps.DistanceMatrixStatus.OK) {
                      var duration = response.rows[0].elements[0].duration.text;
                      document.getElementById('duration').innerText = '徒歩時間： ' + duration;
                    } else {
                      document.getElementById('duration').innerText = 'エラー： ' + status;
                    }
                  }
                );
            }
            
            // ページがロードされたときに地図を初期化
            window.onload = initMap;
            window.onload = calculateDuration;
        
            
        </script>

    </body>
</html>