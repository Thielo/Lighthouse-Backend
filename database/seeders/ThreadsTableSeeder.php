<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Thread;
use App\Models\Post;
use Faker\Factory as Fake;

class ThreadsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Fake::create();
        for ($t = 1; $t <= 10; $t++) {
            $threadUser = User::inRandomOrder()->first();
            $thread = Thread::create([
                'title' => $faker->sentence(),
                'user_id' => $threadUser->id,
            ]);
            echo "Thread: '" . $thread->title . "' created.\n";
            for ($p = 1; $p <= 10; $p++) {
                $postUser = User::inRandomOrder()->first();
                $titleTester = rand(1,2);
                if ($p == 1) {
                    $thisUID = $threadUser->id;
                    $thisTitle = '';
                } else if ($titleTester > 1) {
                    $thisUID = $postUser->id;
                    $thisTitle = $faker->sentence();
                }
                $post = Post::create([
                    'user_id' => $thisUID,
                    'thread_id' => $thread->id,
                    'title' => $thisTitle,
                    'body' => '<p>'.$faker->text().'</p>',
                ]);

                if ($post->title !== '') {
                    echo "-- Post: '" . $post->title . "' (" . $post->id . ") created.\n";
                } else {
                    echo "-- Post: (" . $post->id . ") created.\n";
                }

            }
        }
        // TestDummy::times(20)->create('App\Post');

    }
}
