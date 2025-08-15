<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', function ($routes) {
    // Rute Kategori
    $routes->get('kategori', 'ApiController::getKategori');
    $routes->post('kategori', 'ApiController::createKategori');
    $routes->put('kategori/(:num)', 'ApiController::updateKategori/$1');
    $routes->delete('kategori/(:num)', 'ApiController::deleteKategori/$1');

    // Rute Transaksi
    $routes->get('transaksi', 'ApiController::getTransaksi');
    $routes->post('transaksi', 'ApiController::createTransaksi');
    $routes->put('transaksi/(:num)', 'ApiController::updateTransaksi/$1');
    $routes->delete('transaksi/(:num)', 'ApiController::deleteTransaksi/$1');
    // Rute update transaksi bisa ditambahkan jika perlu
});