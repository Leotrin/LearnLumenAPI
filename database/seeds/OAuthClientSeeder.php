<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OAuthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<10; $i++){
            DB::table('oauth_clients')->insert([
                    'id' => "id$i",
                    'secret' => "secret$i",
                    'name' => "Test Client$i"
                ]);
            // $this->call('UserTableSeeder');
        }
    }
}
