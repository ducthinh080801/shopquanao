<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Cart extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $cart = session()->get('cart') ?? [];
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = $this->productModel->find($id);
            if ($product) {
                $price = $product['discount_price'] ?? $product['price'];
                $subtotal = $price * $item['quantity'];
                
                $cartItems[] = [
                    'id' => $id,
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'subtotal' => $subtotal,
                    'slug' => $product['slug'],
                ];
                
                $total += $subtotal;
            }
        }

        $data = [
            'title' => 'Giỏ hàng',
            'cart_items' => $cartItems,
            'total' => $total,
        ];

        return view('frontend/cart/index', $data);
    }

    public function add()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity') ?? 1;
        $size = $this->request->getPost('size');
        $color = $this->request->getPost('color');

        $product = $this->productModel->find($productId);

        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại'
            ]);
        }

        if ($product['stock'] < $quantity) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không đủ hàng trong kho'
            ]);
        }

        $cart = session()->get('cart') ?? [];
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color,
            ];
        }

        session()->set('cart', $cart);

        // Calculate cart count
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_count' => $cartCount
        ]);
    }

    public function update()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            $product = $this->productModel->find($productId);
            
            if ($product['stock'] < $quantity) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Không đủ hàng trong kho'
                ]);
            }

            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }

            session()->set('cart', $cart);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Sản phẩm không có trong giỏ hàng'
        ]);
    }

    public function remove()
    {
        $productId = $this->request->getPost('product_id');
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Sản phẩm không có trong giỏ hàng'
        ]);
    }

    public function clear()
    {
        session()->remove('cart');
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng'
        ]);
    }

    public function count()
    {
        $cart = session()->get('cart') ?? [];
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return $this->response->setJSON([
            'success' => true,
            'count' => $cartCount
        ]);
    }
}
