<?php

require_once 'lib/response.php';
require_once 'lib/request.php';

function route(Request $request): Response {
    try {
        if ($request->method !== 'GET') return new MethodNotAllowed();
        if (!authenticate($request)) return new Unauthorized();
        $x = str_ends_with($request->url, '/') ? 'dump' : 'read';
        $x = shell_exec("dconf {$x} {$request->url}");
        if (!$x) $x = '';
        return new Response(
            200,
            ['Content-Type' => 'text/plain'],
            $x,
        );
    } catch (\Throwable $e) { return handle_error($e); }
}

function authenticate(Request $request): bool {
    $x = $request->header('Authorization');
    if (!$x) return false;
    if (strlen($x) < 7) return false;
    $x = base64_decode(substr($x, 6), true);
    if (!$x) return false;
    $x = substr($x, 0, -1);
    return $x === getenv('TOKEN');
}

function handle_error(\Throwable $e): Response {
    error_log($e->getMessage() . "\n" .$e->getTraceAsString());
    return new InternalServerError($e->getMessage());
}

function main() {
    $request = new Request();
    $response = route($request);
    $response->serve();
}

main();
