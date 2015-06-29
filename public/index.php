<?php
require '../vendor/autoload.php';
require '../config.php';
// Setup custom Twig view
$twigView = new \Slim\Views\Twig();
$app = new \Slim\Slim(array(
    'debug' => true,
    'view' => $twigView,
    'templates.path' => 'templates/',
));
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
$app->hook('slim.before', function () use ($app) {
    $app->view()->appendData(array('baseUrl' => 'Web_AskGirls/public/'));
});
$view = $app->view();
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension()
);
// Automatically load router files
$routers = glob('../routers/*.router.php');
//require '../routers/index.router.php';
foreach ($routers as $router) {
    require $router;
}
$app->run();