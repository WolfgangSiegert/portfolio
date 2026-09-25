<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$output = $root . '/dist';
set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
if (!is_dir($output . '/assets')) mkdir($output . '/assets', 0755, true);
if (!is_dir($output . '/downloads')) mkdir($output . '/downloads', 0755, true);
foreach (['de', 'en'] as $portfolioLocale) {
    $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($root . '/public/index.php') . ' ' . escapeshellarg($portfolioLocale);
    $html = shell_exec($command);
    if ($html === null) throw new RuntimeException('Portfolio konnte nicht gerendert werden.');
    if (str_contains($html, '<?php') || !str_contains($html, '<!doctype html>')) {
        throw new RuntimeException('Export enthält kein gültiges statisches HTML.');
    }
    $target = $portfolioLocale === 'en' ? $output . '/en' : $output;
    if (!is_dir($target)) mkdir($target, 0755, true);
    file_put_contents($target . '/index.html', $html);
}
copy($root . '/public/assets/portfolio.css', $output . '/assets/portfolio.css');
copy($root . '/public/assets/wolfgang-siegert-portrait.jpg', $output . '/assets/wolfgang-siegert-portrait.jpg');
copy($root . '/public/downloads/wolfgang-siegert-frontend-developer.pdf', $output . '/downloads/wolfgang-siegert-frontend-developer.pdf');
copy($root . '/public/downloads/wolfgang-siegert-kurzprofil-lebenslauf.pdf', $output . '/downloads/wolfgang-siegert-kurzprofil-lebenslauf.pdf');
copy($root . '/public/favicon.svg', $output . '/favicon.svg');
file_put_contents($output . '/.nojekyll', '');
echo "Statischer Export: $output\n";
