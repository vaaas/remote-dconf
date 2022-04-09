<?php

class Response {
    function __construct(int $status, array $headers, string $body) {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    function serve(): void {
        http_response_code($this->status);
        foreach ($this->headers as $k=>$v) header($k . ': ' . $v);
        echo $this->body;
    }
}

class Unauthorized extends Response {
    function __construct(string $message='Unauthorized') {
        parent::__construct(401, ['Content-type' => 'text/plain'], $message);
    }
}

class MethodNotAllowed extends Response {
    function __construct(string $message='Method Not Allowed') {
        parent::__construct(405, ['Content-Type' => 'text/plain'], $message);
    }
}

class InternalServerError extends Response {
    function __construct(string $message='Internal Server Error') {
        parent::__construct(500, ['Content-Type' => 'text/plain'], $message);
    }
}
