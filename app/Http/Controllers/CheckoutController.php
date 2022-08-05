<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use Exception;
use Midtrans\Snap;
use Midtrans\Config;


class CheckoutController extends Controller
{

    public function checkoutdata(Request $request)
    {
        DB::table('carts')->update([
            'Quantity' => $request -> Quantity,
        ]);

        $user = Auth::user();
        $request -> all();
        $name = $request -> name;
        $totalprice = $request -> totalprice;
        $email = $request -> email;
        $notes = $request->notes;
        $no_pesanan = $request->no_pesanan;
        $Quantity = $request -> Quantity;

        if($request->courier == 'JNE'){
            $priceOngkir = 15000;
        }else{
            $priceOngkir = 30000;
        }

                 $carts = Cart::with(['product','user'])
                 ->where('users_id', Auth::user()->id)
                 ->get();

        return view('frontend.paymentorder',compact('name','Quantity','totalprice','email','notes','no_pesanan',
        'carts','priceOngkir'));
    }

    public function process(Request $request)
    {
        if ($request->payment == "NonTunai"){
            $user = Auth::user();
            $code = 'STORE-' . mt_rand(0000,9999);

            $carts = Cart::with(['product','user'])
                ->where('users_id', Auth::user()->id)
                ->get();

            $transaction = Transaction::create([
                'users_id' => Auth::user()->id,
                'total_price' => $request->total_price,
                // 'transaction_status' => 'PENDING',
                'name' => $request -> name,
                'email' => $request -> email,
                'notes' => $request->notes,
                'no_pesanan' => $request->no_pesanan,
                'code' => $code,
                'method' => 'NonTunai',
                'evidence' => $request->file('evidence')->storeAs('image/evidence',$request->file('evidence')->getClientOriginalName(), 'public'),
            ]);

            foreach ($carts as $cart) {
                TransactionDetail::create([
                    'transactions_id' => $transaction->id,
                    'products_id' => $cart->product->id,
                    'price' => $cart->product->Price,
                    'quantity' => $cart-> Quantity,
                ]);

                $update = Product::where('id',$cart->product->id)->first();
                $update->Stock =  $update->Stock - $cart->Quantity;
                $update->save();
            }

            Cart::with(['product','user'])
                ->where('users_id', Auth::user()->id)
                ->delete();

            return redirect()->route('success');

        } else if($request->payment == "Tunai"){
            $user = Auth::user();
            $code = 'STORE-' . mt_rand(0000,9999);


            $carts = Cart::with(['product','user'])
                ->where('users_id', Auth::user()->id)
                ->get();

            $transaction = Transaction::create([
                'users_id' => Auth::user()->id,
                'total_price' => $request->total_price,
                // 'transaction_status' => 'PENDING',
                'name' => $request -> name,
                'email' => $request -> email,
                'notes' => $request->notes,
                'no_pesanan' => $request->no_pesanan,
                'code' => $code,
                'method' => 'Tunai',
            ]);

            foreach ($carts as $cart) {
                TransactionDetail::create([
                    'transactions_id' => $transaction->id,
                    'products_id' => $cart->product->id,
                    'price' => $cart->product->Price,
                    'quantity' => $cart-> Quantity,
                ]);

                $update = Product::where('id',$cart->product->id)->first();
                $update->Stock =  $update->Stock - $cart->Quantity;
                $update->save();
            }

            Cart::with(['product','user'])
                ->where('users_id', Auth::user()->id)
                ->delete();

            return redirect()->route('successTunai');
        }
      
    }

    public function order()
    {
    //    $carts = Cart::with(['product.galleries', 'user'])
    //    ->where('users_id', Auth::user()->id)
    //    ->get();

       $transaction = Transaction::orderBy('id','DESC')
       ->where('users_id',Auth::user()->id)->first();

        // $transactiondetail = TransactionDetail::with('product.galleries','transaction')->where('id',$transaction->id)->get();

    //    return view('frontend.paymentorder',compact('carts','transaction','transactiondetail'));
    }

    public function callback(Request $request)
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        $notification = new Notification();

        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        $transaction = Transaction::findOrFail($order_id);

        if($status == 'capture'){
            if($type = 'credit_card'){
                if($fraud == 'challenge'){
                    $transaction->status = 'PENDING';
                }
                else{
                    $transaction->status = 'SUCCESS';
                }
            }
        }

        else if($status == 'settlement'){
            $transaction->status = 'SUCCESS';
        }
        else if($status == 'pending'){
            $transaction->status = 'PENDING';
        }
        else if($status == 'deny'){
            $transaction->status = 'CANCELLED';
        }
        else if($status == 'expire'){
            $transaction->status = 'CANCELLED';
        }
        else if($status == 'cancel'){
            $transaction->status = 'CANCELLED';
        }

        $transaction->save();
    }
}
