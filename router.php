<?php
declare(strict_types=1);

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$blocked = str_starts_with($path, '/data/')
  || $path === '/data'
  || str_starts_with($path, '/includes/')
  || str_starts_with($path, '/.')
  || preg_match('/\.(zip|md)$/i', $path);

if ($blocked) {
  http_response_code(404);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'Not found';
  return true;
}

$localFile = __DIR__ . $path;
if ($path !== '/' && is_file($localFile)) return false;
if ($path === '/') return false;

http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo 'Not found';
return true;
