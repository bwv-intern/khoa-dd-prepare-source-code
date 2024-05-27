<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Render user01 page
     */
    public function usr01(Request $request)
    {
        $paramSession = session()->get('usr01.search') ?? [];
        $users = $this->userRepository->search($paramSession);
        $users = $this->pagination($users);
        return view('screens.user.usr01', compact('users', 'paramSession'));
    }

    /**
     * Handle user01 page
     */
    public function handleUsr01(Request $request)
    {
        $params = $request->only(['user_id', 'user_flg', 'name', 'email']);
        session()->forget('usr01.search');
        session()->put('usr01.search', $params);
        return to_route('user.usr01');
    }
}
