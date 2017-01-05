<?php
$db = require __DIR__ . '/database.php';
$deploy = require __DIR__ . '/deploy.php';
$static = isset($deploy['static_url']) ? $deploy['static_url'] : dirname(__ROOT__);
$conf = array(
    'url_model' => '2',
    'url_pathinfo_depr' => '/',

    'url_router_on' => true,
    'url_route_rules' => require ROOT_PATH . 'routes/web' . CONF_EXT,

    'session_auto_start' => false,
    'session_options' => require __DIR__ . '/session' . CONF_EXT,

    'default_module' => 'site',
    'action_prefix' => 'action',
    'var_pathinfo' => 'r',

    'taglib_begin' => '{',
    'taglib_end' => '}',

    'tmpl_file_depr' => '.',
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => rtrim(str_replace('\\', '/', $static), '/'),
        '__PUBLIC__' => __ROOT__ . '/resources/assets',
        '__TPL__' => __ROOT__ . '/statics',
    ),

    'upload_path' => isset($deploy['upload_path']) ? rtrim($deploy['upload_path'], '/') . '/' : dirname(ROOT_PATH) . '/',

    'assets' => require __DIR__ . '/assets' . CONF_EXT,

    'tmpl_action_error' => ROOT_PATH . 'resources/views/vendor/message.html', // 默认错误跳转对应的模板文件
    'tmpl_action_success' => ROOT_PATH . 'resources/views/vendor/message.html', // 默认成功跳转对应的模板文件
    'tmpl_exception_file' => ROOT_PATH . 'resources/views/errors/exception.html',// 异常页面的模板文件
);
return array_merge($conf, $db, $deploy);
