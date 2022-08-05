<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $history = Transaction::with('transactiondetail.product.galleries')->get();
        return view('backend.transaction.index', compact('history'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $history = Transaction::with('transactiondetail.product.galleries')->where('id', $id)->first();
        $items = TransactionDetail::with('transaction', 'product.galleries')->where('transactions_id', $id)->get();
        return view('backend.transaction.show', compact('history', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $history = Transaction::with('transactiondetail.product.galleries')->where('id', $id)->first();
        return view('backend.transaction.edit', compact('history'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // 'transaction_status' => $request -> transaction_status
        DB::table('transactions')->where('id', $id)->update([]);

        return redirect()->route('transaction.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function laporan()
    {
        $df = Request('dateFrom');
        $dt = Request('dateTo');

        if ($df || $dt) {
            $data = Transaction::with('transactiondetail.product.galleries')->whereBetween('created_at', [$df, $dt])->get();
            $data = Transaction::with('transactiondetail.product.galleries')->whereBetween('created_at', [$df, $dt])->get();
        } else {
            $data = Transaction::with('transactiondetail.product.galleries')->get();
        }

        $data = Transaction::with('transactiondetail.product.galleries')->get();
        return view('backend.transaction.laporan', compact('data', 'df', 'dt'));
    }
}
