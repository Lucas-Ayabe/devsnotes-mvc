<?php

use App\Http\Controllers\NotesController;

$idRoute = "/note/{id}";

return function (FastRoute\RouteCollector $routes) use ($idRoute) {
    $routes->get('/', [NotesController::class => "index"]);
    $routes->get('/notes', [NotesController::class => "index"]);
    $routes->get($idRoute, [NotesController::class => "get"]);
    $routes->post('/notes', [NotesController::class => "save"]);
    $routes->put($idRoute, [NotesController::class => "update"]);
    $routes->delete($idRoute, [NotesController::class => "delete"]);
    $routes->addRoute('OPTIONS', '/note/{id}', [
        NotesController::class => "cors",
    ]);
};
