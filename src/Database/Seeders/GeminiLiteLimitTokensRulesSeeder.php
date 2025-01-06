<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeminiLiteLimitTokensRulesSeeder extends Seeder
{
    public function run()
    {
        DB::table('geminilite_data_table')->insert([
            'data' => 'geminilite_example_data',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

