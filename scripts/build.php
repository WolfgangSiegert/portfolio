<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$output = $root . '/dist';
set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
if (!is_dir($output . '/assets')) mkdir($output . '/assets', 0755, true);
ob_start();
try {
    require $root . '/public/index.php';
    $html = ob_get_clean();
} catch (Throwable $error) {
    ob_end_clean();
    throw $error;
}
if (str_contains($html, '<?php') || !str_contains($html, '<!doctype html>')) {
    throw new RuntimeException('Export enthält kein gültiges statisches HTML.');
}
file_put_contents($output . '/index.html', $html);
copy($root . '/public/assets/portfolio.css', $output . '/assets/portfolio.css');
copy($root . '/public/assets/wolfgang-siegert-portrait.jpg', $output . '/assets/wolfgang-siegert-portrait.jpg');
copy($root . '/public/favicon.svg', $output . '/favicon.svg');
file_put_contents($output . '/.nojekyll', '');
echo "Statischer Export: $output\n";
