<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $json = file_get_contents(database_path('seeders/data/demo.json'));
        $opening_hour = json_decode($json, true);
        
        
        DB::table('places')->insert([
          'address' => '〒171-0021 東京都豊島区西池袋3丁目34-1',
          'name' => '立教大学大学院人工知能科学研究科',
        //   'opening_hours' => json_encode((json_decode($json, true))),
          'opening_hours' => json_encode($opening_hour),
          'latitude' => 35.7307560533521,
          'longitude' => 139.7025632388044,
          'created_at' => new DateTime(),
          'updated_at' => new DateTime()
        ]);
        
        $mcd_json = file_get_contents(database_path('seeders/data/demo_mcd.json'));
        $mcd_opening_hour = json_decode($mcd_json, true);
        
        DB::table('places')->insert([
           'address' => '〒171-0021 東京都豊島区西池袋1丁目19-7ととのや会館',
           'name' => 'マクドナルド 池袋西口店',
           'opening_hours' => json_encode($mcd_opening_hour),
           'latitude' => 35.731283776200094,
           'longitude' => 139.70960326818823,
           'created_at' => new DateTime(),
           'updated_at' => new DateTime()
        ]);
    }
}
