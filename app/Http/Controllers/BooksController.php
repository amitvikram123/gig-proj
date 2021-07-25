<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBook;
use App\Models\Book;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class BooksController extends Controller
{
    use UserTrait;
    protected User $user;
    public function __construct()
    { 
        $this->middleware('jwt.verified')->only([
            'index',
            'store',
            'update',
            'destroy',
            'rentBook',
            'returnBook'
        ]);
        $this->user = $this->getAuthenticatedUser();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::findAll()->get();
        return response()->json($books, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBook $request)
    {
        $newBook = $request->all();
        Book::create($newBook);

        return response()->json(['book_stored'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with('users')->whereKey($id);
        return response()->json($book, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBook $request, $id)
    {
        $book = Book::findOrFail($id);
        $modifiedBook = $request->all();
        $modifiedBook['user_id'] = $this->user;
        $book->fill($modifiedBook)->save();

        return response()->json(['book modified successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(['book deleted successfully'], 200);
    }

    /**
     * Rent a book
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function rentBook(Request $request)
    {
        $request->validate([
            'bookId' => 'numeric|min:1',
        ]);
        $book = Book::findOrFail($request->input('bookId'));
        if (isset($book->user_id) && (int) $book->user_id > 0) {
            return response()->json(['book already rented to someone else']);
        }
        $book->user()->associate($this->user);
        $book->save();
        return response()->json(User::with('book')->where('u_id','=',$this->user->u_id)->get());
    }

    /**
     * Return a rented book
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */

    public function returnBook(Request $request)
    {
        $request->validate([
            'bookId' => 'numeric|min:1',
        ]);

        $book = Book::findOrFail($request->input('bookId'));

        if (isset($book->user_id) && (int) $book->user_id > 0) {
            $book->user_id = null;
            $book->save();
            return response()->json(['book removed from user']);
        }
        return response()->json(['The requested book does not belong to you']);
    }
}
