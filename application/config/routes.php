<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $route['home/dashboard'] = 'home/dashboard';
// $route['home/contact-us'] = 'home/contact';
// $route['home/galery'] = 'home/galery';
// $route['home/sampel'] = 'home/sampel';
// $route['home/recent-news'] = 'home/recentnews';
// $route['home/recent-news/(:num)'] = 'home/recentnews/$1';
// $route['home/news'] = 'home/news';
//$route['home/news/(:num)'] = 'home/news/$1';
// $route['home/(:any)'] = 'home/page/$1';
$route['default_controller'] = 'auth';
$route['404_override'] = 'home/home404';
$route['translate_uri_dashes'] = FALSE;
