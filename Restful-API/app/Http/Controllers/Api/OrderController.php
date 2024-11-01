<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\OrderController;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::get();
        if($order->count() > 0){
            return OrderResource::collection($order);
        }else{
            return response()->json(['message' => 'No record available'], 200);
        }
    }

    public function store()
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array', // Daftar item pesanan
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,ovo,gopay', // Validasi metode pembayaran
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Menghitung total harga berdasarkan item yang dipesan
        $totalPrice = collect($request->items)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        // Membuat order baru
        $order = Order::create([
            'user_id' => $request->user_id,
            'items' => json_encode($request->items), // Menyimpan item sebagai JSON
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
        ]);

        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->first();

    if ($order) {
        return new OrderResource($order);
    } else {
        return response()->json(['message' => 'Order not found or access denied'], 404);
    }
    }

    public function update()
    {

    }

    public function destroy()
    {
        $order = Order::find($id);

        if ($order) {
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }
}
