<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->add('mylogin-auth', 'MyLogIn::auth');
$routes->add('mylogout', 'MyLogIn::logout');
$routes->get('mydashboard', 'MyDashboard::index',['filter' => 'myauthuser']);
$routes->get('dashboard/saob-session-data', 'MyDashboard::getSaobSessionData');
$routes->get('myaccount', 'MyAccount::index',['filter' => 'myauthuser']);
$routes->get('mydashboarddev', 'MyDashboardDev::index',['filter' => 'myauthuser']);

//User Management - User module
$routes->get('mymembers', 'MembersManagementController::index',['filter' => 'myauthuser']);
$routes->post('mymembers', 'MembersManagementController::index',['filter' => 'myauthuser']);


$routes->get('export-csv', 'MySaobReport::exportCsv');
$routes->get('monthly-export-csv', 'MySaobReport::monthlyExportCsv');
$routes->get('saob-export-csv', 'MySaobReport::saobExportCsv');

