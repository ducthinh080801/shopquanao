<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class AdminProducts extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Quản lý sản phẩm',
            'products' => $this->productModel->orderBy('created_at', 'DESC')->paginate(20),
            'pager' => $this->productModel->pager,
        ];

        return view('admin/products/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Thêm sản phẩm mới',
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('admin/products/create', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $rules = [
            'category_id' => 'required',
            'name' => 'required|min_length[3]',
            'slug' => 'required|is_unique[products.slug]',
            'price' => 'required|decimal',
            'stock' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productData = [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('discount_price') ?: null,
            'stock' => $this->request->getPost('stock'),
            'image' => $this->request->getPost('image'),
            'sizes' => $this->request->getPost('sizes'),
            'colors' => $this->request->getPost('colors'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->productModel->insert($productData)) {
            return redirect()->to('/admin/products')->with('success', 'Thêm sản phẩm thành công');
        }

        return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm');
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $product = $this->productModel->find($id);
        
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Sửa sản phẩm',
            'product' => $product,
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('admin/products/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Không tìm thấy sản phẩm');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'slug' => 'required',
            'price' => 'required|decimal',
            'stock' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productData = [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('discount_price') ?: null,
            'stock' => $this->request->getPost('stock'),
            'image' => $this->request->getPost('image'),
            'sizes' => $this->request->getPost('sizes'),
            'colors' => $this->request->getPost('colors'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->productModel->update($id, $productData)) {
            return redirect()->to('/admin/products')->with('success', 'Cập nhật sản phẩm thành công');
        }

        return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm');
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $product = $this->productModel->find($id);
        
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        // Delete image
        if ($product['image'] && file_exists(WRITEPATH . '../public/' . $product['image'])) {
            unlink(WRITEPATH . '../public/' . $product['image']);
        }

        if ($this->productModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }

    public function restockForm($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $product = $this->productModel->find($id);
        
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Nhập hàng cho sản phẩm',
            'product' => $product,
        ];

        return view('admin/products/restock', $data);
    }

    public function restock($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $quantity = $this->request->getPost('quantity');
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Không tìm thấy sản phẩm');
        }

        if (!is_numeric($quantity) || $quantity <= 0) {
            return redirect()->back()->withInput()->with('error', 'Số lượng nhập phải là số dương');
        }

        $newStock = $product['stock'] + $quantity;
        
        if ($this->productModel->update($id, ['stock' => $newStock])) {
            return redirect()->to('/admin/dashboard')->with('success', 'Nhập hàng thành công cho sản phẩm: ' . $product['name']);
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra khi nhập hàng');
    }
}
