<?php
namespace App\Http;

class Response
{
    private array $data;

    public function send(int $httpStatusCode = 200): void
    {
        http_response_code($httpStatusCode);
        echo json_encode($this->data);
        exit();
    }

    /**
     * Get the value of data
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param array $data
     * @return  self
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Sets a response parameter.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setParam(string $key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Set a header to the response
     *
     * @param string $header
     * @param string $value
     * @return self
     */
    public function setHeader(string $header, string $value): self
    {
        header("$header: $value");
        return $this;
    }

    /**
     * set the headers to the response.
     *
     * @param array $headers
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            $this->setHeader($header, $value);
        }

        return $this;
    }
}
