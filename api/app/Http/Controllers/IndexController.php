<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
        $users = $this->userService->getUsers();
        return response()->json([
            'message' => 'list all users',
            'users' => $users,
        ]);
    }
}
