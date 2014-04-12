<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['404_override'] = '';

$route['default_controller'] = 'amarga/pages';

// admin
$route['admin'] = 'admin';
$route['admin'] = 'admin/auth/login';
$route['auth'] = 'admin/auth/login';
$route['admin/login'] = 'admin/auth/login';
$route['admin/registration'] = 'admin/auth/create_user';
$route['admin/logout'] = 'admin/auth/logout';

//admin content
$route['admin/content'] = 'admin/content';
$route['admin/content/add'] = 'admin/content/add';
$route['admin/content/edit'] = 'admin/content/edit';
$route['admin/content/edit/(:num)'] = 'admin/content/edit/$1';

//admin menu
$route['admin/menu'] = 'admin/menu';
$route['admin/menu/add'] = 'admin/menu/add';
$route['admin/menu/add/(:num)'] = 'admin/menu/add/$1';
$route['admin/menu/edit'] = 'admin/menu/edit';
$route['admin/menu/edit/(:num)'] = 'admin/menu/edit/$1';

//admin file manager
$route['admin/files'] = 'admin/files';
$route['admin/files/dir'] = 'admin/files';
$route['admin/files/(:any)'] = 'admin/files/index/$1';

$route['(:any)'] = 'amarga/pages/index/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */