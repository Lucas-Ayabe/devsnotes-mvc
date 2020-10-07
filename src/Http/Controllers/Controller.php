<?php
namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Response;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    public function redirect(string $path)
    {
        header("Location: {$_SERVER["REQUEST_URI"]}$path");
        exit();
    }

    public function sendResponse(int $httpStatusCode = 200)
    {
        $this->response->setHeader("Content-Type", "application/json");
        $this->response->send($httpStatusCode);
    }
}
