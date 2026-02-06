<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

$routes->group('owner', ['filter' => 'auth:owner'], function ($routes) {
    $routes->get('/', 'Owner::index');

    // Materials Management (Promotion Materials)
    $routes->group('materials', function ($routes) {
            $routes->get('/', 'Owner::materials');
            $routes->get('create', 'Owner::uploadMaterial');
            $routes->post('store', 'Owner::storeMaterial');
        }
        );

        // Agency Management
        $routes->group('agency', function ($routes) {
            $routes->get('/', 'AgencyAdmin::index');
            $routes->get('create', 'AgencyAdmin::create');
            $routes->post('store', 'AgencyAdmin::store');
            $routes->get('edit/(:num)', 'AgencyAdmin::edit/$1');
            $routes->post('update/(:num)', 'AgencyAdmin::update/$1');
            $routes->post('toggle-status/(:num)', 'AgencyAdmin::toggleStatus/$1');
        }
        );

        // Participant Management
        $routes->group('participant', function ($routes) {
            $routes->get('/', 'Participant::index');
            $routes->get('documents', 'Participant::documents');
            $routes->get('documents/(:num)', 'Participant::documents/$1');
            $routes->post('update-checklist', 'Participant::updateChecklist');
            $routes->post('verify-document', 'Participant::verifyDocument');
            $routes->post('upload-document', 'Participant::uploadDocument');
            $routes->get('receipt/(:num)', 'Participant::receipt/$1');
            $routes->get('transaction-receipt/(:num)', 'Participant::transactionReceipt/$1');
            $routes->get('payment-history/(:num)', 'Participant::paymentHistory/$1');
        }
        );

        // Reports
        $routes->get('reports', 'Reports::index');
        $routes->get('reports/equipment', 'Reports::equipment');

        // Account Settings
        $routes->get('settings', 'Owner::settings');
        $routes->post('update-settings', 'Owner::updateSettings');
        $routes->get('payment-verification', 'Owner::paymentVerification');
        $routes->post('verify-payment', 'Owner::verifyPayment');

        // Participant Verification & Checklist
        $routes->get('checklist/(:num)', 'Owner::checklist/$1');
        $routes->post('verify-participant', 'Owner::verifyParticipant');
    });

$routes->group('package', ['filter' => 'auth:owner'], function ($routes) {
    $routes->get('/', 'Package::index');
    $routes->get('create', 'Package::create');
    $routes->post('store', 'Package::store');
    $routes->get('edit/(:num)', 'Package::edit/$1');
    $routes->post('update/(:num)', 'Package::update/$1');
    $routes->get('delete/(:num)', 'Package::delete/$1');
});

$routes->group('agency', ['filter' => 'auth:agency'], function ($routes) {
    $routes->get('/', 'Agency::index');

    // Package Browsing & Registration
    $routes->get('packages', 'Agency::packages');
    $routes->get('package-detail/(:num)', 'Agency::packageDetail/$1');
    $routes->get('register/(:num)', 'Agency::register/$1');
    $routes->post('store-registration', 'Agency::storeRegistration');

    // Participant Management (Edit & List)
    $routes->get('participants', 'Agency::participants');
    $routes->get('edit-participant/(:num)', 'Agency::editParticipant/$1');
    $routes->post('update-participant/(:num)', 'Agency::updateParticipant/$1');
    $routes->get('checklist/(:num)', 'Agency::checklist/$1');
    $routes->post('toggle-equipment', 'Agency::toggleEquipment');

    // Payment Installments
    $routes->get('payments', 'Agency::payments');
    $routes->get('payment-detail/(:num)', 'Agency::paymentDetail/$1');
    $routes->post('store-payment', 'Agency::storePayment');
    // Income Report
    $routes->get('income', 'Agency::income');
});
// Equipment/Attribute Management
$routes->group('owner/equipment', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Equipment::index');
    $routes->post('store', 'Equipment::store');
    $routes->post('update/(:num)', 'Equipment::update/$1');
    $routes->get('toggle/(:num)', 'Equipment::toggle/$1');
    $routes->get('delete/(:num)', 'Equipment::delete/$1');
    $routes->get('participants', 'Equipment::participants');
    $routes->get('checklist/(:num)', 'Equipment::checklist/$1');
    $routes->post('update-status', 'Equipment::updateStatus');
    $routes->get('sync-all', 'Equipment::syncAll');
});

// Commission Management
$routes->group('owner/commissions', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Commission::index');
    $routes->post('update/(:num)', 'Commission::updateProgress/$1');
});
