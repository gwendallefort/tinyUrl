<?php

namespace Database\Seeders;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShortUrlSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        ShortUrl::create([
            'uuid'         => Str::uuid(),
            'user_id'      => $user->id,
            'title'        => 'Laravel',
            'original_url' => 'https://laravel.com',
            'short_code'   => 'laravel',
        ]);
    }
}
