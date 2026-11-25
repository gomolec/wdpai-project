<?php

declare(strict_types=1);

use App\Controller\DefaultController;
use App\Controller\ProjectController;
use App\Controller\SecurityController;
use App\Routing;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$routing = new Routing();

// TU BĘDZIEMY DEFINIOWAĆ WSZYSTKIE TRASY WYSTĘPUJĄCE W NASZYM PROJEKCIE
$routing->get('/login', SecurityController::class, 'login');
$routing->post('/login', SecurityController::class, 'login');
$routing->get('/register', SecurityController::class, 'register');
$routing->post('/register', SecurityController::class, 'register');
$routing->get('/projects', ProjectController::class, 'index');
$routing->get('/projects/{id}', ProjectController::class, 'show');
$routing->get('/', DefaultController::class, 'index');

$routeFound = $routing->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI'],
);

if (!$routeFound) {
    http_response_code(404);
    (new DefaultController())->notFound();
}