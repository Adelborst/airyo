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
$route['default_controller'] = 'startbootstrap/pages';


//Admin Routes:

//airyo
$route[$this->config->item('auth')] = 'airyo/auth/login';
$route['airyo'] = 'airyo/auth/login';
$route['airyo/registration'] = 'airyo/auth/create_user';
$route['airyo/logout'] = 'airyo/auth/logout';

//airyo pages
$route['airyo/pages'] = 'airyo/pages';
$route['airyo/pages/add'] = 'airyo/pages/add';
$route['airyo/pages/edit'] = 'airyo/pages/edit';
$route['airyo/pages/edit/(:num)'] = 'airyo/pages/edit/$1';
$route['airyo/pages/delete'] = 'airyo/pages/delete';

//airyo users (profile)
$route['airyo/users'] = 'airyo/users';
$route['airyo/users/(:num)'] = 'airyo/users';
$route['airyo/users/add'] = 'airyo/users/add';
$route['airyo/users/edit'] = 'airyo/users/edit';
$route['airyo/users/edit/(:num)'] = 'airyo/users/edit/$1';
$route['airyo/users/delete'] = 'airyo/users/delete';

//airyo menu
$route['airyo/menu'] = 'airyo/menu';
$route['airyo/menu/ajax_rebuild/(:num)'] = 'airyo/menu/ajax_rebuild/$1';
$route['airyo/menu/(:num)'] = 'airyo/menu/index/$1';
$route['airyo/menu/add'] = 'airyo/menu/add';
$route['airyo/menu/add/(:num)'] = 'airyo/menu/add/$1';
$route['airyo/menu/edit'] = 'airyo/menu/edit';
$route['airyo/menu/edit/(:num)'] = 'airyo/menu/edit/$1';
$route['airyo/menu/delete'] = 'airyo/menu/delete';

//airyo file manager
$route['airyo/files'] = 'airyo/files';
$route['airyo/files/dir'] = 'airyo/files';
$route['airyo/files/delete'] = 'airyo/files/delete';
$route['airyo/files/createfolder'] = 'airyo/files/createfolder';
$route['airyo/files/renamefolder'] = 'airyo/files/renamefolder';
$route['airyo/files/upload'] = 'airyo/files/upload';
$route['airyo/files/(:any)'] = 'airyo/files/index/$1';
$route['airyo/download'] = 'airyo/files/download';

//airyo gallery manager
$route['airyo/gallery'] = 'airyo/gallery';
$route['airyo/gallery/uploadimages'] = 'airyo/gallery/uploadimages';
$route['airyo/gallery/(album:any)/ajax-sorting'] = 'airyo/gallery/ajax_sorting/$1';
$route['airyo/gallery/(album:any)'] = 'airyo/gallery/getalbum/$1';
$route['airyo/gallery/createalbum'] = 'airyo/gallery/createalbum';
$route['airyo/gallery/editAlbum'] = 'airyo/gallery/editAlbum';
$route['airyo/gallery/edit/(album:any)'] = 'airyo/gallery/editDescriptionAlbum/$1';
$route['airyo/gallery/ajaxRemoveAlbum'] = 'airyo/gallery/ajaxRemoveAlbum';

//airyo sliders manager
$route['airyo/sliders'] = 'airyo/sliders';
$route['airyo/sliders/edit/(:num)'] = 'airyo/sliders/edit/$1';

//airyo news
$route['airyo/news'] = 'airyo/news';
$route['airyo/news/(:num)'] = 'airyo/news';
$route['airyo/news/edit'] = 'airyo/news/edit';
$route['airyo/news/edit/(:num)'] = 'airyo/news/edit/$1';
$route['airyo/news/delete'] = 'airyo/news/delete';

//airyo chunks
$route['airyo/chunks'] = 'airyo/chunks';
$route['airyo/chunks/(:num)'] = 'airyo/chunks';
$route['airyo/chunks/edit'] = 'airyo/chunks/edit';
$route['airyo/chunks/edit/(:num)'] = 'airyo/chunks/edit/$1';
$route['airyo/chunks/delete'] = 'airyo/chunks/delete';

//airyo counters
$route['airyo/counters'] = 'airyo/counters';


//Frontend Routes:
$route['news'] = 'startbootstrap/news';
$route['news/(:any)'] = 'startbootstrap/news/item/$1';
$route['gallery'] = 'startbootstrap/gallery';
$route['(:any)'] = 'startbootstrap/pages/index/$1';



/* End of file routes.php */
/* Location: ./application/config/routes.php */