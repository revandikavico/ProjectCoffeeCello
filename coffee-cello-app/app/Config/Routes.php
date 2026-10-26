<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('db-test', 'DbTest::index');

$routes->get('admin', 'AdminAuth::login');

$routes->get('admin/login', 'AdminAuth::login');

$routes->post('admin/login', 'AdminAuth::login');

$routes->post('admin/logout', 'AdminAuth::logout');

$routes->get('admin/products', 'AdminProducts::index');

$routes->get('admin/products', 'AdminProducts::index');

$routes->get('admin/products/create', 'AdminProducts::create');

$routes->post('admin/products/store', 'AdminProducts::store');

$routes->get('admin/products/edit/(:num)', 'AdminProducts::edit/$1');

$routes->post('admin/products/update/(:num)', 'AdminProducts::update/$1');

$routes->post('admin/products/delete/(:num)', 'AdminProducts::delete/$1');

$routes->get('checkout', 'Checkout::index');

$routes->post('checkout/place', 'Checkout::place');

$routes->get('checkout/success', 'Checkout::success');

$routes->get('admin/orders', 'AdminOrders::index');

$routes->post(
    'admin/orders/update-status/(:num)',
    'AdminOrders::updateStatus/$1'
);

