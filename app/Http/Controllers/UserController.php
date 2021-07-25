<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBook;

class UserController extends Controller
{
    use UserTrait;
    protected User $user;

    public function __construct()
    {
        $this->middleware('jwt.verified');
        $this->user = $this->getAuthenticatedUser();
    }
    /**
     * Get list of users along with all the books
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(['book' => fn($query) => $query->pluck('book_name')])->get();
        return response()->json($user, 200);
    }

    /**
     * Show profile data as well as books taken for one user
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show(int $id)
    {
        $user = User::with(['book' => fn($query) => $query->pluck('book_name')])->whereKey($id)->first();
        return response()->json($user, 200);
    }

}
