<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>検索結果</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body>
        <x-app-layout>
            <div id="map" class="w-[80%] h-80 md:h-96 rounded-lg shadow-lg mx-auto mt-5 mb-5">
                
            </div>
            
            <div class="result mx-auto w-[80%] md:w-4/5 bg-white shadow-md rounded-lg p-6 mb-4">
                <div class="text-2xl font-bold mb-4">検索結果</div>
                <ul class="space-y-4">
                    @foreach ($places as $place)
                      <li class="place_name text-lg font-semibold text-blue-600 hover:text-blue-800 mb-15">
                          <a href="/places/{{ $place->id }}">{{ $place->name }}</a>
                      </li>
                    @endforeach
                </ul>
            </div>
            
            <div class='flex justify-center gap-4'>
                <div class='registration text-center mb-6'>
                    <a href='/finder/regist' class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">店舗登録</a>
                </div>
                
                <div class="footer text-center mb-6">
                    <a href="/finder/home" class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-300">戻る</a>
                </div>
            </div>
            
            
            
            
        
        </x-app-layout>
        
        <script defer src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key={{ env('GOOGLE_MAP_API') }}&libraries=marker&callback=initMap"></script>
        <!--googel mapの表示処理--> 
        <script>
                var places = @json($places);
                
                console.log(places);
                
                function initMap() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                              function(position) {
                                    // ユーザーの位置情報
                                    var position = {
                                      lat: position.coords.latitude,
                                      lng: position.coords.longitude
                                    };
                                    
                                    var map = new google.maps.Map(document.getElementById('map'), {
                                        zoom: 15,
                                        center: position,
                                        mapId: '7f1c3686cbc93098'
                                    });
                                    
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
                
            
                
            </script>

    </body>
</html>