<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $books_id = $request->input('books_id');
        $borrowname = $request->input('borrowname');

        if($id)
        {
            $transaction = transaction::with(['books'])->find($id);

            if($transaction)
                return ResponseFormatter::success(
                    $transaction,
                    'Data Peminjaman berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data Peminjaman tidak ada',
                    404
                );
        }
        
        $transaction = transaction::query();

        return ResponseFormatter::success(
            $transaction->paginate(15),
            'Data list Transaksi berhasil diambil'
        );
    }

    public function pinjam(Request $request)
    {
        try {
            $request->validate([
                'books_id' => ['required', 'integer'],
                'borrowname' => ['required', 'string', 'max:255'],
                'status' => ['required|in:Pinjam,Kembali'],
            ]);

            transaction::create([
                'book_id' => $request->book_id,
                'borrowname' => $request->borrowname,
                'status' => $request->status,
            ]);

            $transaction = transaction::where('borrowname', $request->borrowname)->first();

            return ResponseFormatter::success([
                'book' => $transaction
            ],'Peminjaman Berhasil');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function kembali(Request $request)
    {
        $data = $request->status;
        
        $transaction = transaction::where('id', $request->id)
        ->update($data);

        return ResponseFormatter::success($transaction,'Pengembalian Berhasil');
    }
}
