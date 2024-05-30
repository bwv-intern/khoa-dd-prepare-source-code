<?php

namespace Database\Seeders;

use App\Libs\ValueUtil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        // user_flg = 0: ADMIN, 1: USER, 2: SUPPORT
        for ($i = 1; $i <= 5; $i++) {
            $data[] = [
                'email' => 'admin' . $i . '@test.com',
                'password' => Hash::make('123456'),
                'name' => 'Admin Test ' . $i,
                'user_flg' => $i % sizeof(ValueUtil::getList('user.user_flg')),
                'date_of_birth' => fake()->date(),
                'phone' => ValueUtil::randomNumber(10),
                'address' => fake()->address(),
                'del_flg' => 0,
                'created_at' => new \DateTime(),
                'created_by' => 0,
                'updated_at' => new \DateTime(),
                'updated_by' => 0,
                'deleted_at' => null,
                'deleted_by' => null,
            ];
        }

        DB::table('user')->insert($data);
    }
}
