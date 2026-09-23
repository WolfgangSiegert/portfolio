# Wolfgang Siegert · PHP Portfolio

Eigenständige, vertikal lesbare PHP-Version mit serverseitigen Templates, responsivem Layout und ohne JavaScript oder externe Schrift-/CDN-Anfragen. Voraussetzung: PHP 8.1 oder neuer. Keine Composer-Abhängigkeiten.

## Lokale Vorschau

```sh
php -S 127.0.0.1:8080 -t public
```

Öffne http://127.0.0.1:8080. Für reguläres PHP-Hosting ist `public/` der Document Root; `content/` muss daneben vorhanden sein.

## Statischer Export für GitHub Pages

```sh
php scripts/build.php
php -S 127.0.0.1:8081 -t dist
```

`dist/` enthält ausschließlich HTML, CSS und `.nojekyll`, keine PHP-Quellen oder Inhaltsdateien. Relative Asset-URLs funktionieren sowohl unter einer eigenen Domain als auch unter `BENUTZER.github.io/REPOSITORY/`.

Die Daten in `content/portfolio.json` sind eine generierte Kopie der zentralen Parent-Quelle. Aktualisierung im Parent: `npm run content:sync`. Diese JSON-Kopie wird hier committed, damit ein separater Clone ohne Parent oder Node funktioniert.

## Veröffentlichung

1. Dieses PHP-Repository in ein eigenes GitHub-Repository pushen.
2. Im GitHub-Repository: Settings → Pages → Source: GitHub Actions.
3. Ein Push auf `main` veröffentlicht die aktuelle Version automatisch. Alternativ: Actions → Publish PHP portfolio to GitHub Pages → Run workflow.
4. Nach erfolgreichem Workflow zeigt Settings → Pages die veröffentlichte Adresse.

Der Workflow startet bei einem Push auf `main` oder manuell. Das Portfolio verlinkt die horizontale Vue-Showcase-Variante unter `/portfolio-vue/`.

Eigene Domain: Settings → Pages → Custom domain. DNS beim Domainanbieter passend konfigurieren und anschließend Enforce HTTPS aktivieren. Die genaue Domain wird nicht vorgegeben.
