<?php
declare(strict_types=1);
$profiles = json_decode(file_get_contents(__DIR__ . '/../content/portfolio.json'), true, 512, JSON_THROW_ON_ERROR);
$locale = (($argv[1] ?? null) ?? ($_GET['lang'] ?? 'de')) === 'en' ? 'en' : 'de';
$en = $locale === 'en';
$profile = $profiles[$locale];
$languageHref = isset($argv[1]) ? ($en ? '../' : 'en/') : ($en ? './' : '?lang=en');
$assetBase = isset($argv[1]) && $en ? '../' : '';
function e(string $value): string { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function t(bool $en, string $de, string $english): string { return $en ? $english : $de; }
function icon(string $name, string $class = 'icon'): string {
    $paths = [
        'user'=>'<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'code'=>'<path d="m18 16 4-4-4-4M6 8l-4 4 4 4M14.5 4l-5 16"/>',
        'folder'=>'<path d="M3 6h6l2 2h10v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"/><path d="M8 12v4M12 11v5M16 13v3"/>',
        'briefcase'=>'<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2"/>',
        'cpu'=>'<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3M9 9h6v6H9z"/>',
        'graduation'=>'<path d="m2 10 10-5 10 5-10 5L2 10Z"/><path d="M6 12v5c3 2 9 2 12 0v-5M22 10v6"/>',
        'heart'=>'<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>',
        'mail'=>'<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/>',
        'home'=>'<path d="m3 11 9-8 9 8"/><path d="M5 10v10h14V10M9 20v-6h6v6"/>',
        'arrow-up-right'=>'<path d="M7 17 17 7M7 7h10v10"/>',
        'arrow-right'=>'<path d="M5 12h14M13 6l6 6-6 6"/>',
        'download'=>'<path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>',
        'git'=>'<circle cx="6" cy="5" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="6" cy="19" r="2"/><path d="M6 7v10M8 6h5a5 5 0 0 1 5 5v-3"/>',
        'boxes'=>'<path d="m12 2 4 2.3v4.6L12 11 8 8.9V4.3L12 2ZM5 11l4 2.3v4.6L5 20l-4-2.1v-4.6L5 11Zm14 0 4 2.3v4.6L19 20l-4-2.1v-4.6l4-2.3Z"/>',
        'flask'=>'<path d="M9 3h6M10 3v6l-5 9a2 2 0 0 0 2 3h10a2 2 0 0 0 2-3l-5-9V3M8 14h8"/>',
        'app'=>'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 8h18M7 6h.01"/>',
        'braces'=>'<path d="M8 3H6a2 2 0 0 0-2 2v4a2 2 0 0 1-2 2 2 2 0 0 1 2 2v4a2 2 0 0 0 2 2h2M16 3h2a2 2 0 0 1 2 2v4a2 2 0 0 0 2 2 2 2 0 0 0-2 2v4a2 2 0 0 1-2 2h-2"/>',
        'monitor'=>'<rect x="2" y="3" width="14" height="12" rx="2"/><path d="M6 19h6M9 15v4"/><rect x="17" y="8" width="5" height="11" rx="1"/>',
        'package'=>'<path d="m12 3 8 4-8 4-8-4 8-4ZM4 7v10l8 4 8-4V7M12 11v10"/>',
        'panels'=>'<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 9v12"/>',
        'sliders'=>'<path d="M4 7h10M18 7h2M4 17h2M10 17h10"/><circle cx="16" cy="7" r="2"/><circle cx="8" cy="17" r="2"/>',
        'smartphone'=>'<rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/>',
        'users'=>'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'hammer'=>'<path d="m14 5 5 5M12 7l4-4 5 5-4 4M14 10 4 20l-2-2 10-10"/>',
        'pencil'=>'<path d="m4 20 4-1 11-11-3-3L5 16l-1 4ZM14 7l3 3"/>',
        'trophy'=>'<path d="M8 4h8v5a4 4 0 0 1-8 0V4ZM12 13v4M8 21h8M9 17h6M5 5H3v2a4 4 0 0 0 4 4M19 5h2v2a4 4 0 0 1-4 4"/>',
        'circle'=>'<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2"/>',
        'check'=>'<circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/>',
    ];
    $body = $paths[$name] ?? $paths['check'];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $body . '</svg>';
}
function brand_icon(string $name): string {
    $file = __DIR__ . '/assets/icons/' . $name . '.svg';
    $svg = file_get_contents($file);
    if ($svg === false) return '';
    return str_replace('<svg ', '<svg class="tag-logo" aria-hidden="true" focusable="false" ', trim($svg));
}
function tag_icon(string $label): string {
    $brands = [
        'Vue 2 & 3'=>['vue'], 'Vue 3'=>['vue'], 'Nuxt'=>['nuxt'], 'TypeScript'=>['typescript'], 'JavaScript'=>['javascript'],
        'HTML & CSS'=>['html','css'], 'Tailwind CSS'=>['tailwind'], 'Vite'=>['vite'], 'Pinia / Vuex'=>['pinia','vue'],
        'Node.js'=>['node'], 'npm / pnpm'=>['npm','pnpm'], 'Playwright'=>['playwright'], 'Vitest'=>['vitest'], 'Git'=>['git'],
        'Bitbucket / GitLab'=>['bitbucket','gitlab'], 'Jira / Confluence'=>['jira','confluence'], 'ChatGPT'=>['openai'],
        'Codex'=>['openai'], 'GitHub Copilot'=>['copilot'], 'Laravel'=>['laravel'], 'PostgreSQL'=>['postgresql'],
    ];
    $areas = [
        'REST-APIs'=>'braces', 'REST APIs'=>'braces', 'Webanwendung'=>'app', 'Web application'=>'app', 'Web-App'=>'panels', 'Web app'=>'panels', 'PWA'=>'monitor', 'Produktentwicklung'=>'package', 'Product development'=>'package',
        'Administration'=>'sliders', 'Mobile Apps'=>'smartphone', 'Mobile apps'=>'smartphone', 'Scrum'=>'users', 'Heimwerken'=>'hammer', 'DIY'=>'hammer', 'Zeichnen'=>'pencil', 'Drawing'=>'pencil',
        'Tischtennis'=>'trophy', 'Table tennis'=>'trophy', 'Fußball'=>'circle', 'Football'=>'circle',
    ];
    $icons = '';
    foreach ($brands[$label] ?? [] as $brand) $icons .= brand_icon($brand);
    if (isset($areas[$label])) $icons .= icon($areas[$label], 'tag-icon');
    elseif ($icons === '') $icons = icon('check', 'tag-icon');
    return '<span class="tag-icon-set" aria-hidden="true">' . $icons . '</span>';
}
function work_area_icon(string $title): string {
    return icon(['Frontend'=>'code', 'Architektur & Tooling'=>'boxes', 'Architecture & tooling'=>'boxes', 'Qualität & Zusammenarbeit'=>'flask', 'Quality & collaboration'=>'flask'][$title] ?? 'check', 'entry-heading-icon');
}
function tags(array $items): void {
    echo '<ul class="tags">';
    foreach ($items as $item) echo '<li>' . tag_icon($item) . '<span>' . e($item) . '</span></li>';
    echo '</ul>';
}
$identity = $profile['identity'];
$sections = $en
    ? ['ueber-mich'=>'About me', 'skills'=>'Skills', 'erfahrung'=>'Experience', 'projekte'=>'Projects', 'ai'=>'AI & development', 'ausbildung'=>'Education', 'persoenlich'=>'Personal', 'kontakt'=>'Contact']
    : ['ueber-mich'=>'Über mich', 'skills'=>'Skills', 'erfahrung'=>'Erfahrung', 'projekte'=>'Projekte', 'ai'=>'KI & Entwicklung', 'ausbildung'=>'Ausbildung', 'persoenlich'=>'Persönlich', 'kontakt'=>'Kontakt'];
?>
<!doctype html>
<html lang="<?= e($locale) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= e($profile['hero']['text']) ?>">
  <meta name="theme-color" content="#f4f1e9">
  <link rel="icon" href="<?= $assetBase ?>favicon.svg" type="image/svg+xml">
  <title><?= e($identity['name']) ?> · Frontend Developer in Leipzig</title>
  <link rel="stylesheet" href="<?= $assetBase ?>assets/portfolio.css">
</head>
<body>
<a class="skip" href="#inhalt"><?= t($en, 'Zum Inhalt springen', 'Skip to content') ?></a>
<header class="header">
  <div class="brand-cluster"><a class="brand" href="#start" aria-label="<?= e($identity['name']) ?> – <?= t($en, 'Start', 'Home') ?>">ws<span>.</span></a><a class="site-home-link" href="https://tiny-bits.org/" aria-label="<?= t($en, 'Zur allgemeinen tiny-bits.org-Startseite', 'Go to the main tiny-bits.org homepage') ?>" title="<?= t($en, 'tiny-bits.org Startseite', 'tiny-bits.org homepage') ?>"><?= icon('home') ?></a></div>
  <span class="header-name"><?= e($identity['name']) ?><small><?= e($identity['role']) ?></small></span>
  <div class="header-links">
    <a class="language-switch" href="<?= e($languageHref) ?>" lang="<?= $en ? 'de' : 'en' ?>" hreflang="<?= $en ? 'de' : 'en' ?>" aria-label="<?= t($en, 'Zur englischen Version wechseln', 'Switch to German') ?>" title="<?= t($en, 'Zur englischen Version wechseln', 'Switch to German') ?>"><span aria-hidden="true"><?= $en ? '🇩🇪' : '🇬🇧' ?></span></a>
    <nav class="view-switch view-switch--vertical" aria-label="Portfolio-Ansicht">
      <a href="<?= $en && isset($argv[1]) ? '../' : './' ?>" aria-label="Vertikale Ansicht" data-label="Layout vertikal" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="5" rx="1"/><rect x="3" y="10" width="18" height="5" rx="1"/><rect x="3" y="17" width="18" height="4" rx="1"/></svg></a>
      <a href="<?= $en && isset($argv[1]) ? '../../portfolio-vue/?lang=en' : '../portfolio-vue/' ?>" aria-label="Horizontale Ansicht" data-label="Layout horizontal"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="5" height="18" rx="1"/><rect x="10" y="3" width="5" height="18" rx="1"/><rect x="17" y="3" width="4" height="18" rx="1"/></svg></a>
    </nav>
    <a class="contact-link icon-link" href="#kontakt"><?= t($en, 'Kontakt', 'Contact') ?> <?= icon('arrow-up-right') ?></a>
  </div>
</header>
<main id="inhalt">
  <section class="hero" id="start" aria-labelledby="hero-title">
    <div class="hero-copy">
      <p class="eyebrow"><span class="dot"></span><?= e($identity['location']) ?> / <?= e($identity['role']) ?></p>
      <h1 id="hero-title"><?= e($profile['hero']['title']) ?><br><em><?= e($profile['hero']['accent']) ?></em><br><?= e($profile['hero']['after']) ?></h1>
      <p class="lead"><?= e($profile['hero']['text']) ?></p>
      <div class="actions"><a class="button icon-link" href="#projekte"><?= icon('folder') ?><?= t($en, 'Meine Arbeit entdecken', 'Explore my work') ?><?= icon('arrow-right') ?></a><a class="icon-link" href="#ueber-mich"><?= icon('user') ?><?= t($en, 'Über mich', 'About me') ?><?= icon('arrow-up-right') ?></a></div>
    </div>
    <aside class="hero-card" aria-label="<?= t($en, 'Mein Schwerpunkt', 'My focus') ?>">
      <span class="card-label"><?= t($en, 'Perspektive / Frontend', 'Perspective / Frontend') ?></span>
      <img class="profile-photo" src="<?= $assetBase ?>assets/wolfgang-siegert-portrait.jpg" width="908" height="1200" alt="<?= t($en, 'Porträt von Wolfgang Siegert', 'Portrait of Wolfgang Siegert') ?>" fetchpriority="high">
      <p><?= e($profile['hero']['statement']) ?></p>
      <?php tags($profile['hero']['craft']); ?>
      <small><?= e($profile['hero']['traits']) ?></small>
    </aside>
  </section>
  <nav class="section-nav" aria-label="<?= t($en, 'Abschnitte', 'Sections') ?>"><?php foreach ($sections as $id=>$label): ?><a href="#<?= e($id) ?>"><?= e($label) ?></a><?php endforeach; ?></nav>
  <section class="section" id="ueber-mich" aria-labelledby="about-title">
    <p class="section-label">01 / <?= t($en, 'Über mich', 'About me') ?></p><div><h2 id="about-title" class="heading-with-icon"><?= icon('user') ?><span><?= e($profile['about']['title']) ?></span></h2>
    <?php foreach ($profile['about']['paragraphs'] as $paragraph): ?><p class="copy"><?= e($paragraph) ?></p><?php endforeach; ?>
    <?php tags($profile['about']['facts']); ?></div>
  </section>
  <section class="section green" id="skills" aria-labelledby="skills-title">
    <p class="section-label">02 / Skills</p><div><h2 id="skills-title" class="heading-with-icon"><?= icon('code') ?><span><?= t($en, 'Mein Werkzeugkasten.', 'My toolkit.') ?></span></h2><div class="skill-grid">
    <?php foreach ($profile['skills'] as $skill): ?><article class="skill"><span class="meta"><?= e($skill['number']) ?></span><h3 class="entry-heading"><?= work_area_icon($skill['title']) ?><span><?= e($skill['title']) ?></span></h3><p><?= e($skill['text']) ?></p><?php tags($skill['items']); ?></article><?php endforeach; ?>
    </div></div>
  </section>
  <section class="section" id="erfahrung" aria-labelledby="experience-title">
    <p class="section-label">03 / <?= t($en, 'Erfahrung', 'Experience') ?></p><div><h2 id="experience-title" class="heading-with-icon"><?= icon('briefcase') ?><span><?= t($en, 'Mein beruflicher Weg.', 'My professional journey.') ?></span></h2>
    <?php foreach ($profile['experience'] as $entry): ?><article class="entry"><p class="meta"><?= e($entry['date']) ?></p><h3><?= e($entry['company']) ?></h3><p class="role"><?= e($entry['role']) ?></p><?php foreach ($entry['paragraphs'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?></article><?php endforeach; ?>
    <article class="entry"><h3><?= t($en, 'Vor der Softwareentwicklung', 'Before software development') ?></h3><?php foreach ($profile['earlierExperience'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?></article></div>
  </section>
  <section class="section" id="projekte" aria-labelledby="projects-title">
    <p class="section-label">04 / <?= t($en, 'Projekte', 'Projects') ?></p><div><h2 id="projects-title" class="heading-with-icon"><?= icon('folder') ?><span><?= t($en, 'Arbeit, an der ich mitgewirkt habe.', 'Work I have contributed to.') ?></span></h2><div class="project-grid">
    <?php foreach ($profile['projects'] as $project): ?><article class="project <?= e($project['tone']) ?>"><div class="project-cover"><div class="project-cover-top"><span><?= e($project['n']) ?> / <?= e($project['kind']) ?></span><?php if (!empty($project['status'])): ?><strong class="project-status"><?= e($project['status']) ?></strong><?php endif; ?></div><h3 class="project-title"><?php if ($project['name'] === 'JoinSplit'): ?><img class="project-app-icon" src="<?= $assetBase ?>assets/projects/joinsplit-icon.svg" width="64" height="64" alt=""><?php endif; ?><?= e($project['name']) ?></h3></div><?php if (!empty($project['preview'])): ?><img class="project-preview" src="<?= $assetBase ?>assets/projects/<?= e($project['preview']['file']) ?>" width="1280" height="720" alt="<?= e($project['preview']['alt']) ?>" loading="lazy"><?php endif; ?><div class="project-copy"><p class="meta"><?= e($project['company']) ?></p><p><?= e($project['text']) ?></p><?php tags($project['tags']); ?><?php if (!empty($project['links'])): ?><div class="project-links"><?php foreach ($project['links'] as $link): ?><a class="project-link icon-link" href="<?= e($link['href']) ?>"<?= !empty($link['newTab']) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?= e($link['label']) ?><?= icon(!empty($link['newTab']) ? 'arrow-up-right' : 'arrow-right') ?><?php if (!empty($link['newTab'])): ?><span class="sr-only"> (<?= t($en, 'öffnet einen neuen Tab', 'opens in a new tab') ?>)</span><?php endif; ?></a><?php endforeach; ?></div><?php endif; ?></div></article><?php endforeach; ?>
    </div></div>
  </section>
  <section class="section dark" id="ai" aria-labelledby="ai-title">
    <p class="section-label">05 / <?= t($en, 'KI & Entwicklung', 'AI & development') ?></p><div><h2 id="ai-title" class="heading-with-icon"><?= icon('cpu') ?><span><?= e($profile['ai']['title']) ?></span></h2><?php foreach ($profile['ai']['paragraphs'] as $paragraph): ?><p class="copy"><?= e($paragraph) ?></p><?php endforeach; ?><?php tags($profile['ai']['tools']); ?><p class="note"><?= e($profile['ai']['note']) ?></p></div>
  </section>
  <section class="section" id="ausbildung" aria-labelledby="education-title">
    <p class="section-label">06 / <?= t($en, 'Ausbildung', 'Education') ?></p><div><h2 id="education-title" class="heading-with-icon"><?= icon('graduation') ?><span><?= t($en, 'Fundament & Perspektive.', 'Foundation & perspective.') ?></span></h2><?php foreach ($profile['education'] as $entry): ?><article class="entry"><p class="meta"><?= e($entry['date']) ?></p><h3><?= e($entry['title']) ?></h3><?php if (isset($entry['text'])): ?><p><?= e($entry['text']) ?></p><?php endif; ?></article><?php endforeach; ?></div>
  </section>
  <section class="section green" id="persoenlich" aria-labelledby="personal-title">
    <p class="section-label">07 / <?= t($en, 'Persönlich', 'Personal') ?></p><div><h2 id="personal-title" class="heading-with-icon"><?= icon('heart') ?><span><?= e($profile['personal']['title']) ?></span></h2><p class="copy"><?= e($profile['personal']['text']) ?></p><?php tags($profile['personal']['interests']); ?><p class="note"><?= e($profile['personal']['born']) ?><br><?= e($profile['personal']['languages']) ?></p></div>
  </section>
  <section class="section contact" id="kontakt" aria-labelledby="contact-title">
    <p class="section-label">08 / <?= t($en, 'Kontakt', 'Contact') ?></p><div><h2 id="contact-title" class="heading-with-icon"><?= icon('mail') ?><span><?= e($profile['contact']['title']) ?></span></h2><p class="copy"><?= e($profile['contact']['text']) ?></p><a class="email icon-link" href="mailto:<?= e($identity['email']) ?>"><?= icon('mail') ?><span><?= e($identity['email']) ?></span></a><div class="actions"><a class="icon-link" href="<?= e($identity['githubUrl']) ?>" target="_blank" rel="noopener noreferrer"><?= icon('git') ?>GitHub<?= icon('arrow-up-right') ?><span class="sr-only"> (<?= t($en, 'öffnet einen neuen Tab', 'opens in a new tab') ?>)</span></a><a class="icon-link" href="#start"><?= t($en, 'Zurück zum Anfang', 'Back to top') ?><?= icon('arrow-up-right') ?></a></div><div class="document-links" aria-label="<?= t($en, 'Dokumente zum Download', 'Documents to download') ?>"><?php foreach ($profile['documents'] as $document): ?><a href="<?= e($document['href']) ?>" download><span><strong><?= e($document['label']) ?></strong><small><?= e($document['detail']) ?></small></span><?= icon('download') ?></a><?php endforeach; ?><a class="document-request" href="<?= e($profile['documentRequest']['href']) ?>"><span><strong><?= e($profile['documentRequest']['label']) ?></strong><small><?= e($profile['documentRequest']['detail']) ?></small></span><?= icon('mail') ?></a></div></div>
  </section>
</main>
<footer class="footer"><span><?= e($identity['name']) ?> · <?= e($identity['location']) ?></span><span><?= t($en, 'Mit Verstand. Und Persönlichkeit.', 'With purpose. And personality.') ?></span></footer>
</body>
</html>
