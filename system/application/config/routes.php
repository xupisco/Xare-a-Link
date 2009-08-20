<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";

$route['index'] = "home";

$route['newlink/:any'] = "xare/newlink";
$route['editlink/:any'] = "xare/editlink";

$route['post'] = "xare/postlink";
$route['comment'] = "xare/postcomment";

$route['ajax/getdir'] = "ajax/getDir";
$route['ajax/getpr'] = "ajax/getPR";
$route['ajax/getmeta'] = "ajax/getMeta";
$route['ajax/validateURL'] = "ajax/validateURL";
$route['ajax/uSaveLink'] = "ajax/userSaveLink";
$route['ajax/uRemoveLink'] = "ajax/userRemoveLink";
$route['ajax/logOut'] = "ajax/userLogOut";
$route['ajax/validateUser'] = "ajax/userValidate";
$route['ajax/getCurrentTags'] = "ajax/getCurrentTags";
$route['ajax/getUserTags'] = "ajax/getUserTags";
$route['ajax/saveTags'] = "ajax/saveTags";

$route['s/:any'] = "xare/search";
$route['parse/:num'] = "xare/parseDirByID";
$route['go/:num'] = "xare/goToURLByID";

$route['t/register'] = "tools/register";
$route['t/register_do'] = "tools/register_do";
$route['u/:any'] = "xare/search/u";

$route['adm_ur'] = "xare/updateRelations";
$route[':any'] = "xare";
//$route[':any/index'] = "xare";

?>