<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\Orders\StoreRequest;
use App\models\Order;

use Dnetix\Redirection\PlacetoPay;

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

            $data = $request->only(
                'customer_name',
                'customer_email',
                'customer_mobile'
            );

            $order = new Order();
            $order->reference = uniqid().uniqid();
            $order->customer_name = trim($data['customer_name']);
            $order->customer_email = trim($data['customer_email']);
            $order->customer_mobile = trim($data['customer_mobile']);
            $order->status = Order::STATE_CREATED;
            $order->price = config('shop.product_price');
            $order->save();

            $paymentRequest = $this->createPaymentRequest($order->reference, $order->price);
            if ($paymentRequest->isSuccessful()) {
                $order->request_id = $paymentRequest->requestId();
                $order->process_url =$paymentRequest->processUrl();                
                $order->save();
                DB::commit();
                return redirect('/orders/show/'.$order->reference);
                // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
                // Redirect the client to the processUrl or display it on the JS extension
                //header('Location: ' . $paymentRequest->processUrl());
            } else {
                // There was some error so check the message and log it
                return back()->withInput()->with('error', $paymentRequest->status()->message());                
            }                        
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
            return back()->withInput();
        }
    }

    public function confirm($reference){
        DB::beginTransaction();
        try {
            $order = Order::where('reference', '=', $reference)->first();  
            if(!empty($order)){ 
                $placetopay = $this->placetopayClient();
                $response = $placetopay->query($order->request_id);
                if ($response->isSuccessful()) {                   
                    if ($response->status()->isApproved()) {
                        // The payment has been approved
                        $order->status = Order::STATE_PAYED;
                        $order->save();
                        DB::commit();
                        return view('orders.show', ['order' => $order])->with('success', 'Orden pagada');
                    }else if($response->status()->isFailed() || $response->status()->isRejected()){                        
                        $order->status = Order::STATE_REJECTED;
                        $order->save();
                        $request = $this->retryOrder($order, $response);
                        DB::commit();
                        if($request['success']){                           
                            return view('orders.show', ['order' => $order])->with('warning', $request['message']);
                        }else{
                            return view('orders.show', ['order' => $order])->with('error', $request['message']);
                        }
                    }else{                                              
                        return view('orders.show', ['order' => $order])->with('warning', 'Error desconocido');;
                    }                    
                } else {
                    // There was some error with the connection so check the message
                    return view('orders.show', ['order' => $order])->with('error', $response->status()->message());
                    //print_r($response->status()->message() . "\n");
                }                        
            }else{
                return redirect('/orders/list')->with('error', 'Orden no encontrada');
            }            
        } catch (Exception $e) {
            DB::rollback();
            return redirect('/orders/list')->with('error', $e->getMessage());
        }       
    }

    private function retryOrder(Order $order, $response){

        $data = [
            'success' => true,
            'message' => null,
            'order' => null
        ];

        $paymentRequest = $this->createPaymentRequest($order->reference, $order->price);
        if ($paymentRequest->isSuccessful()) {
            $order->request_id = $paymentRequest->requestId();
            $order->process_url =$paymentRequest->processUrl();                
            $order->save();
            $data['message'] = $response->status()->isFailed() ? 'El pago ha fallado, por favor intente de nuevo' : 'El pago ha sido rechazado, por favor intente de nuevo';            
        }else{
            $data['success'] = false;
            $data['message'] = $paymentRequest->status()->message();            
        }
        return $data;
    }

    private function createPaymentRequest($reference, $price){   
        
        $placetopay = $this->placetopayClient();
        
        $request = [
            'payment' => [
                'reference' => $reference,
                'description' => 'Testing payment',
                'amount' => [
                    'currency' => config('shop.product_currency'),
                    'total' => $price,
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => route('order.confirm', ['reference' => $reference]),
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];

        return $placetopay->request($request);
    }

    private function placetopayClient(){
        return new PlacetoPay([
            'login' => config('placetopay.login'),
            'tranKey' => config('placetopay.trankey'),
            'url' => config('placetopay.url'),
            'rest' => [
                'timeout' => 45, // (optional) 15 by default
                'connect_timeout' => 30, // (optional) 5 by default
            ]
        ]);
    }
}
