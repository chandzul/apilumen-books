<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Book;

class BookController extends Controller
{
  use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index() 
    {
      $books = Book::all();

      return $this->successResponse($books);
    }

    /**
     * [store description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function store(Request $request)
    {
      $rules = [
        'title' => 'required|max:255',
        'description' => 'required|max:255',
        'price' => 'required|min:1',
        'author_id' => 'required|min:1',
      ];

      $this->validate($request, $rules);

      $book = Book::create($request->all());

      return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * [show description]
     *
     * @param   [type]  $book  [$book description]
     *
     * @return  [type]           [return description]
     */
    public function show($book)
    {
      $book = Book::findOrFail($book);

      return $this->successResponse($book);
    }

    /**
     * [update description] -> se usa x-www-form-urlencode
     *
     * @param   Request  $request  [$request description]
     * @param   [type]   $book   [$book description]
     *
     * @return  [type]             [return description]
     */
    public function update(Request $request, $book)
    {
      $rules = [
        'title' => 'max:255',
        'description' => 'max:255',
        'price' => 'min:1',
        'author_id' => 'min:1',
      ];

      $this->validate($request, $rules);

      $book = Book::findOrFail($book);

      $book->fill($request->all());

      if($book->isClean()) {
        return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY); // mandaron algo igual o nada
      }

      $book->save();

      return $this->successResponse($book);   
    }

    /**
     * [destroy a exist book]
     *
     * @param   [type]  $book  [$book description]
     *
     * @return  [type]           [return description]
     */
    public function destroy($book)
    {
      $book = Book::findOrFail($book);

      $book->delete();

      return $this->successResponse($book);
    }
}
