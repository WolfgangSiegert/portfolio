# Wolfgang Siegert · PHP Portfolio

Eigenständige, vertikal lesbare PHP-Version mit serverseitigen Templates, responsivem Layout und ohne externe Schrift-/CDN-Anfragen. Die Darstellung funktioniert ohne JavaScript; auf den veröffentlichten Produktionsdomains sendet lediglich ein kleines, cookieloses Traffic-Skript einen Seitenaufruf an die eigene Laravel-Anwendung unter `atm.tiny-bits.org`. Voraussetzung: PHP 8.1 oder neuer. Keine Composer-Abhängigkeiten.

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

## Traffic-Erfassung

`public/assets/portfolio-traffic.js` zählt ausschließlich Produktionsaufrufe. Lokale Vorschauen, automatisierte Browser und Global Privacy Control werden respektiert. Das Skript überträgt nur Version, Site und einen normalisierten Portfolio-Pfad; es verwendet weder Cookies noch `localStorage`, URL-Parameter oder einen clientseitigen Besucher-Identifier.

Der zugehörige Endpunkt `POST https://atm.tiny-bits.org/api/portfolio-traffic` und die private Auswertung werden im getrennten Laravel-Projekt betrieben. Das Portfolio erst veröffentlichen, wenn dieser Endpunkt bereitsteht. Technischer Vertrag und Aussagegrenzen stehen im Parent-Dokument `TRAFFIC-LOGGING.md`.
