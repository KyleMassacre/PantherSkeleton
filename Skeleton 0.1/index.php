<?php

session_start();
ob_start();
require_once('lib/template.php');
require_once('config/config.php');
require_once('global_func.php');

$template = array();
$temp['content'] = '';
$temp['top_right'] = '';
$temp['menu'] = '';

$template = new template('templates/' . $config['template'] . '/main.php');
$template->set('title', $config['site_title']);
$template->set('footer', $config['footer']);

if (array_key_exists('page', $_GET)) {

    $page = filter_var($_GET['page'], FILTER_SANITIZE_STRING) ? htmlspecialchars($_GET['page']) : '';
    $page = in_array($page, scandir('mods', 1)) ? $page : 'index';

    ob_start();
    include_once('mods/' . $page . '/main.php');
    $file = ob_get_contents();
    ob_end_clean();
    $temp['content'] = $file;
} else {
    ob_start();
    include_once('mods/index/main.php');
    $file = ob_get_contents();
    ob_end_clean();
    $temp['content'] = $file;
}


$menu = array_key_exists('loggedin', $_SESSION) ? 'home' : 'index';
//Fetch the menu data :)
ob_start();
include_once('mods/' . $menu . '/menu.php');
$file = ob_get_contents();
ob_end_clean();
$temp['menu'] = $file;

$template->set('menu', $temp['menu']);
$template->set('content', $temp['content']);
$template->set('top_right', $temp['top_right']);
echo $template->output();
?>