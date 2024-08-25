<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>検索結果</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body onload="initMap()">
        <x-app-layout>
            <div id="map" style="height:300px">
                
            </div>
            
            <div class="result">
                <ul>
                    @foreach ($places as $place)
                      <li class="place_name">
                          <a href="/places/{{ $place->id }}">{{ $place->name }}</a>
                      </li>
                    @endforeach
                </ul>
            </div>
            
            <div class='registration'>
                <a href='/finder/regist'>店舗登録</a>
            </div>
            
            <div class="footer">
                <a href="/finder/home">戻る</a>
            </div>
            
            
        
        </x-app-layout>
        
        <script async defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ env('GOOGLE_MAP_API') }}&libraries=marker&callback=initMap"></script>
        <!--googel mapの表示処理--> 
        <script>
                var places = @json($places);
                
                console.log(places);
                
                function initMap() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                              function(position) {
                                    var location = {
                                      lat: position.coords.latitude,
                                      lng: position.coords.longitude
                                    };
                                    
                                    var map = new google.maps.Map(document.getElementById('map'), {
                                        zoom: 15,
                                        center: location,
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
                                    
                                    // データベースからの情報を表示
                                    places.forEach(function(place) {
                                       const markerElement = document.createElement("div");
                                       markerElement.textContent = place.name;
                                       markerElement.style.background = "blue";
                                       markerElement.style.color = "white";
                                       markerElement.style.padding = "5px";
                                       
                                       var marker = new google.maps.marker.AdvancedMarkerElement({
                                          position: { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude) },
                                          map: map,
                                          title: place.name
                                       }); 
                                    });
                              }
                        );
                    }
                }
                
                // ページがロードされたときに地図を初期化
                window.onload = initMap;
            
                
            </script>

    </body>
</html>