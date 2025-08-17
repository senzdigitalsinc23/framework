<?php

namespace App\Core;

class Request
{
    protected string $uri;
    protected string $method;
    protected array $get;
    protected array $post;
    protected array $headers;

    public function __construct()
    {
        $this->uri = $this->parseUri();
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->get = $_GET;
        $this->post = $_POST;
        $this->headers = getallheaders();
    }

    /**
     * Get the request URI (path only, without query string)
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the HTTP method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get GET parameters
     */
    public function getQuery(string $key = null, $default = null)
    {
        if ($key === null) return $this->get;
        return $this->get[$key] ?? $default;
    }

    /**
     * Get POST parameters
     */
    public function getPost(string $key = null, $default = null)
    {
        if ($key === null) return $this->post;
        return $this->post[$key] ?? $default;
    }

    /**
     * Get headers
     */
    public function getHeader(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    protected function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH); // remove query string
        return rtrim($uri, '/') ?: '/';
    }

    public function input(string $key, $default = null)
    {
        return $this->bodyParams[$key] 
            ?? $this->queryParams[$key] 
            ?? $default;
    }
}
