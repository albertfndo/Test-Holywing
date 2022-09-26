<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\bookcategory;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
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
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if($id)
        {
            $category = bookcategory::with(['books'])->find($id);

            if($category)
                return ResponseFormatter::success(
                    $category,
                    'Data produk berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data kategori produk tidak ada',
                    404
                );
        }
        
        $category = bookcategory::query();

        if($name)
            $category->where('name', 'like', '%' . $name . '%');

        if($show_product)
            $category->with('books');

        return ResponseFormatter::success(
            $category->paginate($limit),
            'Data list kategori produk berhasil diambil'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addcategory(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            bookcategory::create([
                'name' => $request->name,
            ]);

            $bookcategory = bookcategory::where('name', $request->name)->first();

            return ResponseFormatter::success([
                'bookcategory' => $bookcategory
            ],'Book Category Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function updatecategory(Request $request)
    {
        $data = $request->all();
        
        $bookcategory = bookcategory::where('id', $request->id)
        ->update($data);

        return ResponseFormatter::success($bookcategory,'Book Category Updated');
    }

    public function deletecategory(Request $request)
    {
        $data = $request->all();
        bookcategory::destroy($data);
        return ResponseFormatter::success($data,'Book Category Deleted');
    }
}
