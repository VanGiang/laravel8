<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
{
    public $viewData = [];

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(1);
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
        return view('admins.products.create', [
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

        $file = $request->file('images');

        if ($file && $file instanceof UploadedFile) {
            $file->store('public/products');

            $orgName = $file->getClientOriginalName();
            $hashName = $file->hashName();

            $inputData['images'] = $hashName;
            // $inputData['org_image_name'] = $orgName;
        }

        try {
            $product = Product::create(array_merge($inputData, [
                'user_id' => auth()->id()
            ]));

            return redirect('/products/' . $product->id);
        } catch (\Throwable $th) {
            return back()->with('status', 'Create failed!');;
        }
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

        $this->viewData['product'] = Product::findOrFail($id);
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
        $product = Product::find($id);
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
        $product = Product::find($id);

        try {
            $product->update([
                'name' => $inputData['name'],
                'price' => $inputData['price'],
                'quantity' => $inputData['quantity'],
                'description' => $inputData['description'],
            ]);

            return redirect('/products/' . $product->id);
        } catch (\Throwable $th) {
            return back()->with('status', 'Update failed!');;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        try {
            $product->delete();

            return redirect('/products')->with('status', 'Delete Success!');
        } catch (\Throwable $th) {
            return back()->with('status', 'Delete Failure!');
        }
    }
}
