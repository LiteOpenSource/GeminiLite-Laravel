<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeminiLiteLimitTokensRulesSeeder extends Seeder
{
    public function run()
    {
        DB::table('gemini_lite_roles')->insert([
            'name' => 'Patient',
            'description' => 'A patient registered in the system',
            'daily_request_limit' => 100,
            'monthly_request_limit' => 3000,
            'daily_token_limit' => 300000,
            'monthly_token_limit' => 30000000,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

