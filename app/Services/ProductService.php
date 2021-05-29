<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends BaseService
{
    protected $productModel;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    public function getListProducts($whereCondition = [], $options = [], $page = null)
    {
        $products = $this->productModel;

        if (!empty($whereCondition)) {
            $products = $products->where($whereCondition);
        }

        if (isset($options['order_by']) && isset($options['order_type'])) {
            $products = $products->orderBy($options['order_by'], $options['order_type']);
        }

        if ($page) {
            $products = $products->paginate($page);
        } else {
            $products = $products->get();
        }

        return $products;
    }

    /**
     * Get one product by id
     *
     * @param int $id
     *
     * @return App\Models\Product | null
    */
    public function getProduct($id)
    {
        $product = $this->productModel->findOrFail($id);

        return $product;
    }

    /**
     * Create a new Product
     *
     * @param array $inputs ['name', 'price', 'quantity', ...]
     *
     * @return App\Models\Product | null
    */
    public function create($inputs)
    {
        try {
            $product = $this->productModel->create($inputs);
            // $product = $this->productRepository->create($inputs);
        } catch (\Throwable $th) {
            \Log::error($th);

            return null;
        }

        return $product;
    }

    /**
     * Update a product
     *
     * @param Product $product
     * @param array $inputs ['name', 'price', 'quantity', ...]
     *
     * @return App\Model\Product | null
    */
    public function update($product, $inputs)
    {
        try {
            $product = $product->update($inputs);
        } catch (\Throwable $th) {
            \Log::error($th);

            return null;
        }

        return $product;
    }

    /**
     * Delete a product
     *
     * @param Product $product
     *
     * @return Boolean
    */
    public function delete($product)
    {
        try {
            $delFlag = $product->delete();
        } catch (\Throwable $th) {
            \Log::error($th);

            return false;
        }

        return true;
    }
}
