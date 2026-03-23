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
$routes->get('mydashboarddev', 'MyDashboardDev::index',['filter' => 'myauthuser']);

//Members Management - Members module
$routes->get('mymembers', 'MembersManagementController::index',['filter' => 'myauthuser']);
$routes->post('mymembers', 'MembersManagementController::index',['filter' => 'myauthuser']);

//User Account - User module
$routes->get('myaccount', 'AccountSettingsController::index',['filter' => 'myauthuser']);
$routes->post('myaccount', 'AccountSettingsController::index',['filter' => 'myauthuser']);

//LOAN AVAILMENT - Loan availment module
$routes->get('myloanavailment', 'LoanAvailmentController::index',['filter' => 'myauthuser']);
$routes->post('myloanavailment', 'LoanAvailmentController::index',['filter' => 'myauthuser']);

//LOAN PROFILE - Loan profile module
$routes->get('myloanprofile', 'LoanProfileController::index',['filter' => 'myauthuser']);
$routes->post('myloanprofile', 'LoanProfileController::index',['filter' => 'myauthuser']);


