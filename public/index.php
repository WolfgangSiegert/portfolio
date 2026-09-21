<?php
declare(strict_types=1);
$profile = json_decode(file_get_contents(__DIR__ . '/../content/portfolio.json'), true, 512, JSON_THROW_ON_ERROR);
function e(string $value): string { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function tags(array $items): void {
    echo '<ul class="tags">';
    foreach ($items as $item) echo '<li>' . e($item) . '</li>';
    echo '</ul>';
}
$identity = $profile['identity'];
$sections = ['ueber-mich'=>'Über mich', 'skills'=>'Skills', 'erfahrung'=>'Erfahrung', 'projekte'=>'Projekte', 'ai'=>'KI & Entwicklung', 'ausbildung'=>'Ausbildung', 'persoenlich'=>'Persönlich', 'kontakt'=>'Kontakt'];
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= e($profile['hero']['text']) ?>">
  <meta name="theme-color" content="#f4f1e9">
  <title><?= e($identity['name']) ?> · Frontend Developer in Leipzig</title>
  <link rel="stylesheet" href="assets/portfolio.css">
</head>
<body>
<a class="skip" href="#inhalt">Zum Inhalt springen</a>
<header class="header">
  <a class="brand" href="#start" aria-label="<?= e($identity['name']) ?> – Start">ws<span>.</span></a>
  <span class="header-name"><?= e($identity['name']) ?><small><?= e($identity['role']) ?></small></span>
  <a class="contact-link" href="#kontakt">Kontakt ↗</a>
</header>
<main id="inhalt">
  <section class="hero" id="start" aria-labelledby="hero-title">
    <div class="hero-copy">
      <p class="eyebrow"><span class="dot"></span><?= e($identity['location']) ?> / <?= e($identity['role']) ?></p>
      <h1 id="hero-title"><?= e($profile['hero']['title']) ?><br><em><?= e($profile['hero']['accent']) ?></em><br><?= e($profile['hero']['after']) ?></h1>
      <p class="lead"><?= e($profile['hero']['text']) ?></p>
      <div class="actions"><a class="button" href="#projekte">Meine Arbeit entdecken →</a><a href="#ueber-mich">Über mich ↗</a></div>
    </div>
    <aside class="hero-card" aria-label="Mein Schwerpunkt">
      <span class="card-label">Perspektive / Frontend</span>
      <div class="monogram" aria-hidden="true">W<span>S</span></div>
      <p><?= e($profile['hero']['statement']) ?></p>
      <?php tags($profile['hero']['craft']); ?>
      <small><?= e($profile['hero']['traits']) ?></small>
    </aside>
  </section>
  <nav class="section-nav" aria-label="Abschnitte"><?php foreach ($sections as $id=>$label): ?><a href="#<?= e($id) ?>"><?= e($label) ?></a><?php endforeach; ?></nav>
  <section class="section" id="ueber-mich" aria-labelledby="about-title">
    <p class="section-label">01 / Über mich</p><div><h2 id="about-title"><?= e($profile['about']['title']) ?></h2>
    <?php foreach ($profile['about']['paragraphs'] as $paragraph): ?><p class="copy"><?= e($paragraph) ?></p><?php endforeach; ?>
    <?php tags($profile['about']['facts']); ?></div>
  </section>
  <section class="section green" id="skills" aria-labelledby="skills-title">
    <p class="section-label">02 / Skills</p><div><h2 id="skills-title">Mein Werkzeugkasten.</h2><div class="skill-grid">
    <?php foreach ($profile['skills'] as $skill): ?><article class="skill"><span class="meta"><?= e($skill['number']) ?></span><h3><?= e($skill['title']) ?></h3><p><?= e($skill['text']) ?></p><?php tags($skill['items']); ?></article><?php endforeach; ?>
    </div></div>
  </section>
  <section class="section" id="erfahrung" aria-labelledby="experience-title">
    <p class="section-label">03 / Erfahrung</p><div><h2 id="experience-title">Mein beruflicher Weg.</h2>
    <?php foreach ($profile['experience'] as $entry): ?><article class="entry"><p class="meta"><?= e($entry['date']) ?></p><h3><?= e($entry['company']) ?></h3><p class="role"><?= e($entry['role']) ?></p><?php foreach ($entry['paragraphs'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?></article><?php endforeach; ?>
    <article class="entry"><h3>Vor der Softwareentwicklung</h3><?php foreach ($profile['earlierExperience'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?></article></div>
  </section>
  <section class="section" id="projekte" aria-labelledby="projects-title">
    <p class="section-label">04 / Projekte</p><div><h2 id="projects-title">Arbeit, an der ich mitgewirkt habe.</h2><div class="project-grid">
    <?php foreach ($profile['projects'] as $project): ?><article class="project <?= e($project['tone']) ?>"><div class="project-cover"><span><?= e($project['n']) ?> / <?= e($project['kind']) ?></span><h3><?= e($project['name']) ?></h3></div><div class="project-copy"><p class="meta"><?= e($project['company']) ?></p><p><?= e($project['text']) ?></p><?php tags($project['tags']); ?><?php if (isset($project['url'], $project['linkLabel'])): ?><a class="project-link" href="<?= e($project['url']) ?>"><?= e($project['linkLabel']) ?> →</a><?php endif; ?></div></article><?php endforeach; ?>
    </div><p class="note"><?= e($profile['projectsNote']) ?></p></div>
  </section>
  <section class="section dark" id="ai" aria-labelledby="ai-title">
    <p class="section-label">05 / KI & Entwicklung</p><div><h2 id="ai-title"><?= e($profile['ai']['title']) ?></h2><?php foreach ($profile['ai']['paragraphs'] as $paragraph): ?><p class="copy"><?= e($paragraph) ?></p><?php endforeach; ?><?php tags($profile['ai']['tools']); ?><p class="note"><?= e($profile['ai']['note']) ?></p></div>
  </section>
  <section class="section" id="ausbildung" aria-labelledby="education-title">
    <p class="section-label">06 / Ausbildung</p><div><h2 id="education-title">Fundament & Perspektive.</h2><?php foreach ($profile['education'] as $entry): ?><article class="entry"><p class="meta"><?= e($entry['date']) ?></p><h3><?= e($entry['title']) ?></h3><?php if (isset($entry['text'])): ?><p><?= e($entry['text']) ?></p><?php endif; ?></article><?php endforeach; ?></div>
  </section>
  <section class="section green" id="persoenlich" aria-labelledby="personal-title">
    <p class="section-label">07 / Persönlich</p><div><h2 id="personal-title"><?= e($profile['personal']['title']) ?></h2><p class="copy"><?= e($profile['personal']['text']) ?></p><?php tags($profile['personal']['interests']); ?><p class="note"><?= e($profile['personal']['born']) ?><br><?= e($profile['personal']['languages']) ?></p></div>
  </section>
  <section class="section contact" id="kontakt" aria-labelledby="contact-title">
    <p class="section-label">08 / Kontakt</p><div><h2 id="contact-title"><?= e($profile['contact']['title']) ?></h2><p class="copy"><?= e($profile['contact']['text']) ?></p><a class="email" href="mailto:<?= e($identity['email']) ?>"><?= e($identity['email']) ?> ↗</a><div class="actions"><a href="<?= e($identity['githubUrl']) ?>" target="_blank" rel="noopener noreferrer">GitHub ↗<span class="sr-only"> (öffnet einen neuen Tab)</span></a><a href="#start">Zurück zum Anfang ↑</a></div></div>
  </section>
</main>
<footer class="footer"><span><?= e($identity['name']) ?> · <?= e($identity['location']) ?></span><span>Mit Verstand. Und Persönlichkeit.</span></footer>
</body>
</html>
