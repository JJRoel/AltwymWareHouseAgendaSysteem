<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('groups')->insert([
            ['name' => 'Group A', 'color' => 'Red'],
            ['name' => 'Group B', 'color' => 'Blue']
        ]);
    }
}

