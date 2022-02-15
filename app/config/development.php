<?php

/**
 * Configuration file for Development version
 *   You can create one for:
 *   development.php
 *   staging.php
 *   production.php
 */

/****************************/
/* == Base Configuration == */
/* @var string */
/****************************/

/**
 * Site Base URL with http:// or https:// prefix and trailing slash
 * @var string
 */
$site = "http://localhost/etamrin/";
/**
 * URL parse method
 *   - REQUEST_URI, suitable for Nginx
 *   - PATH_INFO, suitable for XAMPP
 *   - ORIG_PATH_INFO
 * @var string
 */
$method = "PATH_INFO"; //REQUEST_URI,PATH_INFO,ORIG_PATH_INFO,
/**
 * Admin Secret re-routing
 * this is alias for app/controller/admin/*
 * @var string
 */
$admin_secret_url = 'admin';
/**
 * Base URL with http:// or https:// prefix and trailing slash
 * @var string
 */
$cdn_url = '';

/********************************/
/* == Database Configuration == */
/* Database connection information */
/* @var array of string */
/********************************/
$db['host']  = 'localhost';
$db['user']  = 'root';
$db['pass']  = '';
$db['name']  = 'etamrin_db';
$db['port'] = '3306';
$db['charset'] = 'utf8mb4';
$db['engine'] = 'mysqli';
$db['enckey'] = '';

/****************************/
/* == Session Configuration == */
/* @var string */
/****************************/
$saltkey = 'etamrin43';

/********************************/
/* == Timezone Configuration == */
/* @var string */
/****************************/
$timezone = 'Asia/Jakarta';

/********************************/
/* == Core Configuration == */
/* register your core class, and put it on: */
/*   - app/core/ */
/* all var $core_* value in lower case string*/
/* @var string */
/****************************/
$core_prefix = 'ji_';
$core_controller = 'controller';
$core_model = 'model';

/********************************/
/* == Controller Configuration == */
/* register your onboarding (main) controller */
/*   - make sure dont add any traing slash in array key of routes */
/*   - all var $controller_* value in lower case string */
/*   - example $routes['produk/(:any)'] = 'produk/detail/index/$1' */
/*   - example example $routes['blog/id/(:num)/(:any)'] = 'blog/detail/index/$1/$2'' */
/* @var string */
/****************************/
$controller_main = 'home';
$controller_404 = 'notfound';

/********************************/
/* == Controller Re-Routing Configuration == */
/* make sure dont add any traing slash in array key of routes */
/* @var array of string */
/****************************/
// $routes['jobs/detail/(:num)'] = 'joblist/detail/$1';
// $routes['produk/(:any)'] = 'produk/detail/index/$1';
// $routes['blog/id/(:num)/(:any)'] = 'blog/detail/index/$1/$2';


/********************************/
/* == Another Configuration == */
/* configuration are in array of string format */
/*  - as name value pair */
/*  - accessing value by $this->semevar->key in controller extended class */
/*  - accessing value by $this->semevar->key in model extended class */
/****************************/

//firebase messaging
$semevar['fcm'] = new stdClass();
$semevar['fcm']->version = '';
$semevar['fcm']->apiKey = '';
$semevar['fcm']->authDomain = '';
$semevar['fcm']->databaseURL = '';
$semevar['fcm']->projectId = '';
$semevar['fcm']->storageBucket = '';
$semevar['fcm']->messagingSenderId = '';
$semevar['fcm']->appId = '';

// // example
// $semevar['site_name'] = 'Sewagor';
// $semevar['site_name_long'] = 'BELI SEHAT DENGAN SEWA GOR';
// $semevar['site_description'] = 'Aplikasi sewa gor olahraga yang memudahkan anda untuk booking.';
// $semevar['site_version'] = '1.0.0';
// $semevar['site_suffix'] = ' | Sewagor';
// $semevar['site_logo_big'] = 'media/icon/android-chrome-512x512.png';
// $semevar['site_logo_small'] = 'media/icon/small.png';
// $semevar['site_logo'] = 'media/logo-sewagor.png';
// $semevar['site_logo_light'] = 'media/logo-sewagor-light.png';
// $semevar['site_address'] = 'Bandung, Indonesia';
// $semevar['email_from'] = 'noreply@thecloudalert.com';
// $semevar['email_reply'] = 'recruitment@sbpgroup.id';
// $semevar['copyright'] = $semevar['site_name'] . ' versi ' . $semevar['site_version'] . ' &copy; Sewagor 2022';

$semevar['site_name'] = 'Etamrin';
$semevar['site_name_long'] = 'Website Management Tugas';
$semevar['site_description'] = 'Aplikasi Managemen Tugas Berbasis Web.';
$semevar['site_version'] = '1.0.0';
$semevar['site_suffix'] = ' | Etamrin';
$semevar['site_logo_big'] = 'media/icon/android-chrome-512x512.png';
$semevar['site_logo_small'] = 'media/icon/small.png';
$semevar['site_logo'] = 'media/logo-etamrin.png';
$semevar['site_logo_light'] = 'media/logo-tamrin-light.png';
$semevar['site_address'] = 'Bandung, Indonesia';
$semevar['email_from'] = 'noreply@thecloudalert.com';
$semevar['email_reply'] = 'rezziqbal@gmail.com';
$semevar['copyright'] = $semevar['site_name'] . ' versi ' . $semevar['site_version'] . ' &copy; Sewagor 2022';

// admin
$semevar['admin_site_suffix'] = ' - Administrator';
$semevar['admin_logo'] = 'media/logo-etanrin.png';
$semevar['admin_logo_w'] = '224';
$semevar['admin_logo_h'] = '69';
$semevar['admin_logo_light'] = 'media/logo-tamrin-light.png';
$semevar['admin_logo_light_w'] = '224';
$semevar['admin_logo_light_h'] = '69';

//
$semevar['app_name'] = 'Etamrin';
$semevar['app_logo'] = 'media/logo-tamrin.png';
$semevar['app_version'] = '1.01.16-dev';

$semevar['company_name'] = 'PT SEWAGOR';
$semevar['company_name_short'] = 'PT SEWAGOR';
$semevar['company_about'] = '';
$semevar['company_address'] = 'Bandung, Indonesia';
$semevar['media_user'] = 'media/user';

$semevar['media_upload'] = 'media/upload';
$semevar['media_capture'] = 'media/capture';
