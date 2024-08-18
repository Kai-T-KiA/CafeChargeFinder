<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('posts')->insert([
          'user_id' => 1,
          'place_id' => 1,
          'title' => 'コンセントの場所',
          'body' => '11号館5階(院生のみ利用可能)',
          'created_at' => new DateTime(),
          'updated_at' => new DateTime(),
        ]);
        
        DB::table('posts')->insert([
          'user_id' => 1,
          'place_id' => 2,
          'title' => 'コンセントの場所',
          'body' => '2FとB1Fのカウンター席',
          'created_at' => new DateTime(),
          'updated_at' => new DateTime(),
        ]);
    }
}
