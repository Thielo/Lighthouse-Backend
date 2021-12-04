<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use Faker\Factory as Fake;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Fake::create();
        $users = [
            [
                'user' => [
                    'username' => 'admin',
                    'email' => 'admin@lighthouse.io',
                    'password' => Hash::make('admin'),
                ],
                'profile' => [
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName,
                    'image' => $faker->imageUrl(800, 400, 'cats', true, 'Faker', true)
                ]
            ],
            [
                'user' => [
                    'username' => 'moderator',
                    'email' => 'moderator@lighthouse.io',
                    'password' => Hash::make('moderator'),
                ],
                'profile' => [
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName,
                ]
            ],
            [
                'user' => [
                    'username' => 'user',
                    'email' => 'user@lighthouse.io',
                    'password' => Hash::make('user'),
                ],
                'profile' => [
                    'first_name' => $faker->firstName(),
                    'image' => $faker->imageUrl(800, 400, 'cats', true, 'Faker', true)
                ]
            ],
        ];

        foreach($users as $user){
            $account = User::create($user['user']);
            $profileData = [
                'user_id' => $account->id
            ];
            $profile = Profile::create(array_merge($profileData, $user['profile']));
            echo "User: '" . $account->username . "' / '" . $account->email . "' created.\n";
            echo "Profile: " . $profile->id . " created. (User: " . $profile->user_id . ")\n";
        }
    }
}
