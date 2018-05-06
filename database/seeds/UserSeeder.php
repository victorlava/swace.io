<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $x) {
            $user = new User;
            $user->first_name = $faker->firstName;
            $user->last_name = $faker->lastName;
            $user->phone = $faker->phoneNumber;
            $user->email = $faker->email;
            $user->password = \Hash::make('admin');
            $user->verified = rand(0, 1);
            $user->contributed = rand(0, 1);
            $user->created_at = '2016-05-01';
            $user->verified_at = '2016-05-01';
            $user->save();
        }
    }
}
