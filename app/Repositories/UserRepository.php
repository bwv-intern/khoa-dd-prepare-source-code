<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function getModel()
    {
        return User::class;
    }

    /**
     * Get user login
     *
     * @param array $params
     * @return mixed
     */
    public function getUserLogin(array $params)
    {
        $query = User::query()
            ->where('email', $params['email'] ?? null)
            ->where('del_flg', $this->validDelFlg);
        $user = $query->get()->first();
        if ($user && Hash::check($params['password'], $user->password)) {
            return $user;
        }
        return null;
    }

    /**
     * Search user
     *
     * @param array $params
     * @return mixed
     */
    public function search(array $params)
    {
        $query = User::whereRaw('1=1');
        $query->where('del_flg', $this->validDelFlg);
        if (!empty($params['email'])) {
            $query->where('email', $params['email']);
        }
        if (!empty($params['user_flg'])) {
            $query->whereIn('user_flg', $params['user_flg']);
        }
        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (!empty($params['date_of_birth'])) {
            $query->where('date_of_birth', Carbon::createFromFormat('d/m/Y', $params['date_of_birth'])->format('Y/m/d'));
        }
        if (!empty($params['phone'])) {
            $query->where('phone', $params['phone']);
        }
        $query->orderBy('id', 'desc');

        return $query;
    }
}
