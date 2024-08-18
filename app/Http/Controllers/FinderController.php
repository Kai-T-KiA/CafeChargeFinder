<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use GoogleMaps;

class FinderController extends Controller
{
    public function home() {
        return view('finder.home');
    }
    
    public function result(Place $place) {
        return view('finder.result')->with(['places' => $place->get()]);
        // return view('finder.result');
    }
    
    public function detail(Place $place) {
        return view('finder.detail')->with(['place' => $place, 'posts' => $place->posts()->get()]);
    }
    
    public function regist() {
        return view('finder.regist');
    }
    
    public function store(Request $request, Place $place, Post $post) {
        
        // ユーザーが入力した店舗名
        $user_input_place_name = $request['place'];
        
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
            $place->opening_hours = $googlemap_data['opening_hours'];
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
        
        
        return redirect('/')->with('message', '店舗データが正常に登録されました。');
        // return redirect()->route('finder.regist')->with('message', '店舗データが正常に登録されました。');
    }
    
    private function get_place_detail_by_googlemap($user_input_place_name) {
        // Googel Maps APIで店舗情報を取得
        $response = GoogleMaps::load('place/textsearch')->setParam([
            'query' => $user_input_place_name,
            'key' => env('GOOGLE_MAPS_API_KEY'),
        ])->get();
        
        $place_data = json_decode($response, true)['results'][0];
        
        return [
            'name' => $place_data['name'],
            'address' => $place_data['formatted_address'],
            'opening_hours' => $place_data['opening_hours']['weekday_text'] ?? null,
            'latitude' => $place_data['geometry']['location']['lat'],
            'longitude' => $place_data['geometry']['location']['lng'],
        ];
    }
    
}
