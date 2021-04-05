<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductOrder;
use App\Models\Product;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUserId = auth()->id();
        $order = Order::where('user_id', $currentUserId)
            ->where('status', 1)
            ->first();

        $productOrders = null;

        if ($order) {
            $productOrders = ProductOrder::where('order_id', $order->id)->get();
        }

        $data = [
            'user' => auth()->user(),
            'productOrders' => $productOrders,
        ];
        return view('orders.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputData = $request->only([
            'product_id',
            'quantity',
        ]);

        $productId = $inputData['product_id'];
        $product = Product::find($productId);

        if (!$product) {
            return json_encode([
                'status' => false,
                'msg' => 'This product has been deleted.',
            ]);
        }

        // Create order
        $currentUserId = auth()->id();
        $orderData = [
            'code' => 'OGANI_' . now()->format('Ymd_His') . '_' . $currentUserId,
            'user_id' => $currentUserId,
        ];

        // Check current user has new order
        $currentOrder = Order::where('user_id', $currentUserId)
            ->where('status', 1)
            ->first();

        if (!$currentOrder) {
            try {
                $currentOrder = Order::create($orderData);

                // Create product_order
                $productOrderData = [
                    'product_id' => $inputData['product_id'],
                    'quantity' => $inputData['quantity'],
                    'order_id' => $currentOrder->id,
                    'price' => $product->price,
                ];
                $productOrder = ProductOrder::create($productOrderData);
            } catch (\Throwable $th) {
                \Log::info('create order failed');
                \Log::info($th);

                return json_encode([
                    'status' => false,
                    'msg' => 'Something went wrong.',
                ]);
            }

            $cartNumber = ProductOrder::where('order_id', $currentOrder->id)
                ->sum('quantity');

            return json_encode([
                'status' => true,
                'msg' => 'Add product to Cart success.',
                'quantity' => $cartNumber,
            ]);
        }

        // co order roi thi todo
        // Check product_order da ton tai hay chua
        $currentProductOrder = ProductOrder::where('product_id', $productId)
            ->where('order_id', $currentOrder->id)
            ->first();

        try {
            if (!$currentProductOrder) {
                // Create product_order
                $productOrderData = [
                    'product_id' => $inputData['product_id'],
                    'quantity' => $inputData['quantity'],
                    'order_id' => $currentOrder->id,
                    'price' => $product->price,
                ];
                $productOrder = ProductOrder::create($productOrderData);
            } else {
                $currentProductOrder->quantity += $inputData['quantity'];
                $currentProductOrder->save();
            }
        } catch (\Throwable $th) {
            \Log::error($th);

            return json_encode([
                'status' => false,
                'msg' => 'Something went wrong.',
            ]);
        }

        $cartNumber = ProductOrder::where('order_id', $currentOrder->id)
            ->sum('quantity');

        return json_encode([
            'status' => true,
            'msg' => 'Add product to Cart success.',
            'quantity' => $cartNumber,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productOrderId)
    {
        $productOrder = ProductOrder::find($productOrderId);

        try {
            $productOrder->delete();

            $result =[
                'status' => true,
                'msg' => 'Delete Success!',
            ];
        } catch (\Throwable $th) {
            \Log::error($th);

            $result = [
                'status' => false,
                'msg' => 'Delete Success!',
            ];
        }

        return json_encode($result);
    }
}
