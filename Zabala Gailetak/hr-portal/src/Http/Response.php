<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Http;

/**
 * HTTP Response Class (PSR-7 compliant)
 */
class Response
{
    private int $statusCode;
    private array $headers;
    private string $body;

    public function __construct(string $body = '', int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Create a JSON response
     */
    public static function json($data, int $statusCode = 200): self
    {
        return new self(
            json_encode($data, JSON_UNESCAPED_UNICODE),
            $statusCode,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Create an HTML response
     */
    public static function html(string $html, int $statusCode = 200): self
    {
        return new self(
            $html,
            $statusCode,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }

    /**
     * Render a view file and return HTML response
     */
    public static function view(string $viewPath, array $data = [], int $statusCode = 200): self
    {
        // Automatically inject CSRF token into all views
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['csrf_token'])) {
            $data['csrf_token'] = $_SESSION['csrf_token'];
        }

        // Extract data to make variables available in view
        extract($data);

        // Start buffering
        ob_start();

        // Define path relative to project root
        $fullPath = dirname(__DIR__, 2) . '/public/views/' . $viewPath . '.php';

        if (!file_exists($fullPath)) {
            // Fallback for development debugging
            return self::html("View not found: $viewPath", 500);
        }

        require $fullPath;

        // Get content
        $content = ob_get_clean();

        return self::html($content, $statusCode);
    }

    /**
     * Create a redirect response
     */
    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new self(
            '',
            $statusCode,
            ['Location' => $url]
        );
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withHeader(string $name, string $value): self
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    public function withStatus(int $statusCode): self
    {
        $clone = clone $this;
        $clone->statusCode = $statusCode;
        return $clone;
    }
}
