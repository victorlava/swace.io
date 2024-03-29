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
        if (env('ENV') == 'local') {
            $faker = Faker::create();

            foreach (range(1, 10) as $i) {
                $user = new User;
                $user->first_name = $faker->firstName;
                $user->last_name = $faker->lastName;
                $user->phone = $faker->phoneNumber;
                $user->email = 'dummy_user+' . $i . '@swace.io';
                $user->password = \Hash::make('admin');
                $user->verified = rand(0, 1);
                $user->contributed = 0;

                if ($user->isVerified()) {
                    $user->verified_at = date('Y-m-d H:i:s');
                }

                $user->save();
            }
        } else {
            $user = new User;
            $user->first_name = 'Administrator';
            $user->last_name = '';
            $user->phone = '00000';
            $user->email = 'mosescaterpt@swace.io';
            $user->password = \Hash::make('UitARTIBiTEGINiGnoBI');
            $user->verified = 1;
            $user->contributed = 0;
            $user->admin = 1;
            $user->kyc = 1;
            $user->verified_at = date('Y-m-d H:i:s');
            $user->save();
        }
    }
}
