<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\Orders\StoreRequest;
use App\models\Order;
use Exception;

class OrdersController extends Controller
{    

    public function index(){
        try {
            //code...
            $orders = Order::all();
            return view('orders.list', ['orders' => $orders]);
        } catch (Exception $e) {
            //throw $th;
        }
    }

    //
    public function store(StoreRequest $request){
        DB::beginTransaction();
        try {

            //config('shop.product_price');

            $data = $request->only(
                'customer_name',
                'customer_email',
                'customer_mobile'
            );

            //code...
            $order = new Order();
            $order->reference = uniqid().uniqid();
            $order->customer_name = trim($data['customer_name']);
            $order->customer_email = trim($data['customer_email']);
            $order->customer_mobile = trim($data['customer_mobile']);
            $order->status = Order::STATE_CREATED;
            $order->price = config('shop.product_price');
            $order->save();

            DB::commit();

            return redirect('/orders/show/'.$order->reference);
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput();
        }

    }

    public function show($reference){
        try {
            $order = Order::where('reference', '=', $reference)->first();  
            if(!empty($order)){
                return view('orders.show', ['order' => $order]);
            }else{
                return redirect('/orders/list')->with('error', 'Orden no encontrada');
            }                     
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput();
        }
    }
}
