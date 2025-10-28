<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::index');
$routes->get('/search', 'Home::search');

// Authentication
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::loginPost');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::registerPost');
$routes->get('/logout', 'Auth::logout');
$routes->get('/forgot-password', 'Auth::forgotPassword');
$routes->post('/forgot-password', 'Auth::forgotPasswordPost');

// Products
$routes->get('/products', 'Products::index');
$routes->get('/products/category/(:segment)', 'Products::category/$1');
$routes->get('/products/(:segment)', 'Products::detail/$1');
$routes->post('/products/review', 'Products::addReview');

// Cart
$routes->get('/cart', 'Cart::index');
$routes->post('/cart/add', 'Cart::add');
$routes->post('/cart/update', 'Cart::update');
$routes->post('/cart/remove', 'Cart::remove');
$routes->post('/cart/clear', 'Cart::clear');
$routes->get('/cart/count', 'Cart::count');

// Checkout
$routes->get('/checkout', 'Checkout::index');
$routes->post('/checkout/process', 'Checkout::process');

// Orders
$routes->get('/orders', 'Orders::index');
$routes->get('/orders/detail/(:segment)', 'Orders::detail/$1');
$routes->get('/orders/track', 'Orders::track');
$routes->post('/orders/track', 'Orders::trackPost');
$routes->get('/orders/success/(:segment)', 'Orders::success/$1');
$routes->get('/orders/invoice/(:segment)', 'Orders::invoice/$1');
$routes->get('/orders/download-invoice/(:segment)', 'Orders::downloadInvoice/$1');

// User Profile
$routes->get('/profile', 'Profile::index');
$routes->post('/profile/update', 'Profile::update');
$routes->post('/profile/change-password', 'Profile::changePassword');
$routes->get('/profile/orders', 'Profile::orders');
$routes->get('/profile/payments', 'Profile::payments');
$routes->post('/profile/addCard', 'Profile::addCard');
$routes->post('/profile/removeCard', 'Profile::removeCard');
$routes->get('/profile/invoices', 'Profile::invoices');

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('dashboard/revenue-chart', 'Admin\Dashboard::revenueChart');
    
    // Products
    $routes->get('products', 'Admin\AdminProducts::index');
    $routes->get('products/create', 'Admin\AdminProducts::create');
    $routes->post('products/store', 'Admin\AdminProducts::store');
    $routes->get('products/edit/(:num)', 'Admin\AdminProducts::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\AdminProducts::update/$1');
    $routes->post('products/delete/(:num)', 'Admin\AdminProducts::delete/$1');
    $routes->post('products/restock/(:num)', 'Admin\AdminProducts::restock/$1');
    $routes->get('products/restock/(:num)', 'Admin\AdminProducts::restockForm/$1');
    
    // Orders
    $routes->get('orders', 'Admin\AdminOrders::index');
    $routes->get('orders/detail/(:num)', 'Admin\AdminOrders::detail/$1');
    $routes->post('orders/update-status', 'Admin\AdminOrders::updateStatus');
    $routes->get('orders/invoices', 'Admin\AdminOrders::invoices');
    
    // Customers
    $routes->get('customers', 'Admin\AdminCustomers::index');
    $routes->get('customers/detail/(:num)', 'Admin\AdminCustomers::detail/$1');
    $routes->post('customers/toggle-status/(:num)', 'Admin\AdminCustomers::toggleStatus/$1');
    
    // Reviews
    $routes->get('reviews', 'Admin\AdminReviews::index');
    $routes->get('reviews/pending', 'Admin\AdminReviews::pending');
    $routes->post('reviews/approve/(:num)', 'Admin\AdminReviews::approve/$1');
    $routes->post('reviews/hide/(:num)', 'Admin\AdminReviews::hide/$1');
    $routes->post('reviews/delete/(:num)', 'Admin\AdminReviews::delete/$1');
});
