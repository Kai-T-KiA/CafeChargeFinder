<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use GoogleMaps;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class FinderController extends Controller
{
    public function home() {
        return view('finder.home');
    }
    
    public function save_location(Request $request) {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
    
        // セッションに位置情報を保存
        session(['current_location' => ['latitude' => $latitude, 'longitude' => $longitude]]);
        
        return response()->json(['success' => true]);
    }
    
    public function result(Request $request, Place $place) {
        $current_location = session('current_location');
    
        if (!$current_location) {
            // セッションに位置情報がない場合の処理
            return redirect()->route('home')->with('error', '位置情報が保存されていません。');
        }
        
        $current_latitude = $current_location['latitude'];
        $current_longitude = $current_location['longitude'];
        
        
        $radius = 2000; // 2 km

        $places = $place::select('id', 'name', 'latitude', 'longitude')
            ->selectRaw(
                '(6371000 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$current_latitude, $current_longitude, $current_latitude]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit(5)
            ->get();
            
        
        return view('finder.result')->with(['places' => $places]);
    }
    
    public function detail(Place $place) {
        return view('finder.detail')->with(['place' => $place, 'posts' => $place->posts()->get()]);
    }
    
    public function regist() {
        return view('finder.regist');
    }
    
    public function store(Request $request, Place $place, Post $post) {
        
        // ユーザーが入力した店舗名
        $user_input_place_name = $request['place']['name'];
        
        
        // Google Maps APIで店舗名を統一
        $googlemap_data = $this->get_place_detail_by_googlemap($user_input_place_name);
        $regist_name = $googlemap_data['name'];
        
        
        // 既に店舗データが登録されているか確認
        $place = Place::where('name', $regist_name)->first();
        
        if(!$place) {
            // 店舗が存在しなければ、新たなデータとして登録
            $place = new Place();
            $place->name = $regist_name;
            $place->address = $googlemap_data['address'];
            $place->opening_hours = json_encode($googlemap_data['opening_hours'], JSON_UNESCAPED_UNICODE);
            $place->latitude = $googlemap_data['latitude'];
            $place->longitude = $googlemap_data['longitude'];
            $place->save();
        }
        
        // ユーザーの口コミを登録
        $post_input = $request['post'];
        $post->fill($post_input);
        $post->user_id = Auth::id();
        $post->place_id = $place->id;
        $post->save();
        
        
        return redirect('/finder/home')->with('message', '店舗データが正常に登録されました。');
    }
    
    private function get_place_detail_by_googlemap($user_input_place_name) {
        // Googel Maps APIで店舗情報を取得
        $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
            'query' => $user_input_place_name,
            'key' => env('GOOGLE_MAP_API'),
            'language' => 'ja',
        ]);
    
        $place_data = $response->json();
        
        
        $placeId = $place_data['results'][0]['place_id'];

        // Place Details APIを使って営業時間を取得する
        $detailsResponse = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'fields' => 'opening_hours',
            'key' => env('GOOGLE_MAP_API'),
            'language' => 'ja',
        ]);

        $details = $detailsResponse->json();
        
        return [
            'name' => $place_data['results'][0]['name'],
            'address' => $place_data['results'][0]['formatted_address'],
            'opening_hours' => $details['result']['opening_hours']['weekday_text'],
            'latitude' => $place_data['results'][0]['geometry']['location']['lat'],
            'longitude' => $place_data['results'][0]['geometry']['location']['lng'],
        ];
    }
    
}
