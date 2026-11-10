<?php
/**
 * Shared helpers for all route files.
 * Routes only call services; services throw Exception($message, $httpCode).
 */
class BaseRoutes
{
    /** Runs a service call and returns JSON with the correct HTTP status. */
    public static function handle(callable $fn, int $success = 200): void
    {
        try {
            Flight::json($fn(), $success);
        } catch (Throwable $e) {
            $code = (int)$e->getCode();
            if ($e instanceof ValidationException && ($code < 400 || $code > 499)) {
                $code = 400;
            }
            if ($code < 400 || $code > 599) {   // also catches PDOException string codes
                error_log($e->getMessage());
                $code = 500;
            }
            Flight::json(['error' => $code === 500 ? 'Internal server error.' : $e->getMessage()], $code);
        }
    }

    /** JSON request body as an array. */
    public static function body(): array
    {
        $data = Flight::request()->data->getData();
        return is_array($data) ? $data : [];
    }
}
