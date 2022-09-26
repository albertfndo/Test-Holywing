<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\book;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $category_id = $request->input('category_id');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if($id)
        {
            $book = book::with(['category'])->find($id);

            if($book)
                return ResponseFormatter::success(
                    $book,
                    'Data buku berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data buku tidak ada',
                    404
                );
        }
        
        $book = book::query();

        if($name)
            $book->where('name', 'like', '%' . $name . '%');

        if($show_product)
            $book->with('books');

        if($category_id)
            $book->where('category_id', $category_id);
 
        return ResponseFormatter::success(
            $book->paginate($limit),
            'Data list buku berhasil diambil'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addbook(Request $request)
    {
        try {
            $request->validate([
                'category_id' => ['required', 'integer'],
                'name' => ['required', 'string', 'max:255'],
            ]);

            book::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
            ]);

            $book = book::where('name', $request->name)->first();

            return ResponseFormatter::success([
                'book' => $book
            ],'Book Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function updatebook(Request $request)
    {
        $data = $request->all();
        
        $book = book::where('id', $request->id)
        ->update($data);

        return ResponseFormatter::success($book,'Book Updated');
    }

    public function deletebook(Request $request)
    {
        $data = $request->all();
        book::destroy($data);
        return ResponseFormatter::success($data,'Book Deleted');
    }
}
