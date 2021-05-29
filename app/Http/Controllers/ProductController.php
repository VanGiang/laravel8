<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    public $viewData = [];
    public $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = [
            'order_by' => 'created_at',
            'order_type' => 'desc',
        ];
        $products = $this->productService->getListProducts([], $orders, 2);

        $data = [
            'user' => auth()->user(),
            'products' => $products,
        ];

        return view('products.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $inputData = $request->only([
            'name',
            'code',
            'price',
            'quantity',
            'description',
        ]);

        $inputs = array_merge($inputData, [
            'user_id' => auth()->id()
        ]);

        $product = $this->productService->create($inputs);

        if ($product) {
            return redirect('/products/' . $product->id);
        }

        return back()->with('status', 'Create failed!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $currentUserId = auth()->id();
        // $cartNumber = 0;

        // $currentOrder = Order::where('user_id', $currentUserId)
        //     ->where('status', 1)
        //     ->first();

        // if ($currentOrder) {
        //     $cartNumber = ProductOrder::where('order_id', $currentOrder->id)
        //         ->sum('quantity');
        // }

        $this->viewData['product'] = $this->productService->getProduct($id);
        $this->viewData['user'] = auth()->user();
        // $this->viewData['cartNumber'] = $cartNumber;

        return view('products.show', $this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->productService->getProduct($id);
        if (!$product) {
            abort(404);
        }

        $data = [
            'user' => auth()->user(),
            'product' => $product,
        ];

        return view('products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {
        $inputData = $request->all();
        $product = $this->productService->getProduct($id);

        $inputs = [
            'name' => $inputData['name'],
            'price' => $inputData['price'],
            'quantity' => $inputData['quantity'],
            'description' => $inputData['description'],
        ];

        $updateFlg = $this->productService->update($product, $inputs);

        if ($updateFlg) {
            return redirect('/products/' . $product->id);
        }

        return back()->with('status', 'Update failed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->productService->getProduct($id);

        $delFlg = $this->productService->delete($product);

        if ($delFlg) {
            return redirect('/products')->with('status', 'Delete Success!');
        }

        return back()->with('status', 'Delete Failure!');
    }
}
