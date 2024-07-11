<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->insert([
            [
                'groupid' => 1,
                'name' => 'Item 1',
                'aanschafdatum' => '2022-01-01',
                'tiernummer' => 'T1',
                'status' => 'available',
                'picture' => null
            ],
            [
                'groupid' => 1,
                'name' => 'Item 2',
                'aanschafdatum' => '2022-02-01',
                'tiernummer' => 'T2',
                'status' => 'unavailable',
                'picture' => null
            ],
            [
                'groupid' => 2,
                'name' => 'Item 3',
                'aanschafdatum' => '2022-03-01',
                'tiernummer' => 'T3',
                'status' => 'available',
                'picture' => null
            ]
        ]);
    }
}

