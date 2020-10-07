<?php
namespace App\Http;

class Request
{
    private array $data = [];
    private array $files = [];

    public function __construct()
    {
        if ($this->isGet()) {
            $this->data = $_GET;
        }

        if ($this->isPost()) {
            $this->data = $_POST;
        }

        if ($this->hasFiles()) {
            $this->files = $_FILES;
        }

        if ($this->isPut()) {
            $this->data = $this->getRequestData();
        }

        if ($this->isDelete()) {
            $this->data = $this->getRequestData();
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getRequestData(): array
    {
        parse_str(file_get_contents('php://input'), $input);
        return $input;
    }

    public function acceptCORS(string $method, string $origin = "*"): void
    {
        if ($this->isOptions()) {
            $response = new Response();
            $response->setHeader("Access-Control-Allow-Origin", $origin);
            $response->setHeader("Access-Control-Allow-Methods", $method);
            $response->setHeader("Content-Type", "text/plain");
            $response->send(200);
        }
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === $method;
    }

    public function isGet(): bool
    {
        return $this->isMethod("GET");
    }

    public function isPost(): bool
    {
        return $this->isMethod("POST");
    }

    public function isPut(): bool
    {
        return $this->isMethod("PUT");
    }

    public function isPatch(): bool
    {
        return $this->isMethod("PATCH");
    }

    public function isDelete(): bool
    {
        return $this->isMethod("DELETE");
    }

    public function isOptions(): bool
    {
        return $this->isMethod("OPTIONS");
    }

    public function hasFiles(): bool
    {
        return isset($_FILES) && count($_FILES);
    }
}
