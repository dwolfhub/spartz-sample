<?php

class DatabaseSeeder extends Seeder {

    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $this->call('UserTableSeeder');
        $this->call('CityTableSeeder');
    }

}

class UserTableSeeder extends Seeder
{

    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('users')->delete();

        $csvFile = new Keboola\Csv\CsvFile(__DIR__ . '/../../../data/users.csv');
        $now = date('Y-m-d H:i:s');

        $insertData = [];
        foreach($csvFile as $index => $row) {
            if (!$index) {
                continue;
            }

            array_push($insertData, [
                'id' => $row[0],
                'first_name' => $row[1],
                'last_name' => $row[2],
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        DB::table('users')->insert($insertData);
    }
}

class CityTableSeeder extends Seeder
{

    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('users_cities')->delete();
        DB::table('cities')->delete();

        $csvFile = new Keboola\Csv\CsvFile(__DIR__ . '/../../../data/cities.csv');
        $now = date('Y-m-d H:i:s');

        $insertData = [];
        foreach($csvFile as $index => $row) {
            if (!$index) {
                continue;
            }

            array_push($insertData, [
                'id' => $row[0],
                'name' => $row[1],
                'state' => $row[2],
                'status' => $row[3],
                'latitude' => $row[4],
                'longitude' => $row[5],
                'created_at' => $now,
                'updated_at' => $now
            ]);
            if (!($index % 75)) {
                DB::table('cities')->insert($insertData);
                $insertData = [];
            }
        }

        DB::table('cities')->insert($insertData);
    }
}