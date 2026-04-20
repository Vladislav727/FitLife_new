<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(10)->create();

        Goal::factory()->count(15)->create();
        Post::factory()->count(20)->create();

        if ($users->count() >= 2) {
            $pairs = $users->take(6)->values();

            for ($index = 0; $index < $pairs->count() - 1; $index += 2) {
                $subscriber = $pairs[$index];
                $subscribedUser = $pairs[$index + 1];

                Subscription::create([
                    'user_id' => $subscriber->id,
                    'subscribed_user_id' => $subscribedUser->id,
                    'status' => 'accepted',
                ]);

                Subscription::create([
                    'user_id' => $subscribedUser->id,
                    'subscribed_user_id' => $subscriber->id,
                    'status' => 'accepted',
                ]);
            }
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);
    }
}
