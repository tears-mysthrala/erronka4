<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Http;

/**
 * HTTP Request Class (PSR-7 compliant)
 */
class Request
{
    private string $method;
    private string $uri;
    private array $headers;
    private array $query;
    private array $post;
    private array $files;
    private array $server;
    private array $cookies;
    private ?string $body;
    private array $attributes = []; // For storing request-level data

    public function __construct(
        string $method,
        string $uri,
        array $headers = [],
        array $query = [],
        array $post = [],
        array $files = [],
        array $server = [],
        array $cookies = [],
        ?string $body = null
    ) {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->headers = $headers;
        $this->query = $query;
        $this->post = $post;
        $this->files = $files;
        $this->server = $server;
        $this->cookies = $cookies;
        $this->body = $body;
    }

    /**
     * Create request from PHP globals
     */
    public static function fromGlobals(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '/',
            getallheaders() ?: [],
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE,
            file_get_contents('php://input') ?: null
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return parse_url($this->uri, PHP_URL_PATH) ?: '/';
    }

    public function getQuery(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }

    public function getPost(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->post;
        }

        return $this->post[$key] ?? $default;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Get header line (PSR-7 compatible)
     */
    public function getHeaderLine(string $name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getCookie(string $name, mixed $default = null): mixed
    {
        return $this->cookies[$name] ?? $default;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getJsonBody(): ?array
    {
        if ($this->body === null) {
            return null;
        }

        return json_decode($this->body, true);
    }

    /**
     * Get parsed body (JSON or form data)
     */
    public function getParsedBody(): ?array
    {
        if ($this->isJson()) {
            return $this->getJsonBody();
        }

        // For form data, return POST array
        return $this->post;
    }

    public function isJson(): bool
    {
        $contentType = $this->getHeader('Content-Type');
        return $contentType !== null && str_contains($contentType, 'application/json');
    }

    public function isGet(): bool
    {
        return $this->method === 'GET';
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    public function isPut(): bool
    {
        return $this->method === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->method === 'DELETE';
    }

    /**
     * Set an attribute (mutable)
     */
    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Create new request with attribute (immutable PSR-7)
     */
    public function withAttribute(string $name, mixed $value): self
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    /**
     * Get an attribute
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Get all attributes
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
