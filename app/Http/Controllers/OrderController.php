<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;


class OrderController extends Controller
{
    public function index() {
        $orders = Order::with('user')->paginate(10);
       // dd($orders);
        return view('orders.index', compact('orders'));
    }

    public function create() {
        $users = User::where('is_admin', 0)->get();
        return view('orders.create', compact('users'));
    }



    public function store(Request $request)
    {
        // Save Order
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->total = 0; // Will be updated later
        $order->save();

        // Save Products
        $grandTotal = 0;
        foreach ($request->products as $productData) {
            $product = new OrderProduct();
            $product->order_id = $order->id;
            $product->name = $productData['name'];

            $product->qty = $productData['qty'];
            $product->amount = $productData['amount'];
            $product->total = $productData['qty'] * $productData['amount'];
        // dd($product);
            $product->save();
            
            $grandTotal += $product->total;
        }

        // Update Order Total
        $order->total = $grandTotal;
        //dd($order);
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }


}

