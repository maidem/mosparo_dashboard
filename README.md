# maidem/mosparo-dashboard

TYPO3-Extension mit Dashboard-Widgets für mosparo-Statistiken. Zeigt gültige und Spam-Einsendungen sowie deren Verlauf direkt im TYPO3-Backend-Dashboard.

## Überblick

Die Extension fragt über die mosparo-API die Statistik-Daten des konfigurierten mosparo-Projekts ab und stellt sie als Dashboard-Widgets bereit:

- **Gültige Einsendungen** — Zahlen-Widget, letzte 14 Tage
- **Spam-Einsendungen** — Zahlen-Widget, letzte 14 Tage
- **Mosparo Einsendungen** — Balkendiagramm mit dem Tagesverlauf beider Werte

## Voraussetzungen

- TYPO3 14+
- PHP 8.4+
- Eine erreichbare mosparo-Instanz mit Public/Private Key

## Installation

`composer install maidem/mosparo-dashboard`

Anschließend im Backend unter **Dashboard → Widget hinzufügen** die gewünschten Mosparo-Widgets auswählen.

## Konfiguration

Host, Public Key und Private Key werden über die Erweiterungskonfiguration gesetzt (**System → Einstellungen → Erweiterungskonfiguration → maidem_mosparo_dashboard**) oder per Umgebungsvariable überschrieben (z. B. in Coolify) — praktisch, wenn dieselbe Extension in mehreren Projekten mit unterschiedlichen mosparo-Instanzen läuft.

### Umgebungsvariablen

- `MOSPARO_HOST` (oder `MOSPARO_PUBLIC_SERVER`) — Basis-URL der mosparo-Instanz (z. B. `https://protect.example.com`)
- `MOSPARO_PUBLIC_KEY` — Public Key des mosparo-Projekts
- `MOSPARO_PRIVATE_KEY` — Private Key des mosparo-Projekts

Diese Variablen werden unabhängig vom `TYPO3_CONTEXT` ausgelesen (siehe `config/system/additional.php` im Hauptprojekt) und überschreiben dort die Erweiterungskonfiguration — funktioniert also sowohl in Production (Coolify) als auch lokal in DDEV (`.ddev/config.local.yaml`).

## Technische Details

- **MosparoStatisticService** — baut den `Mosparo\ApiClient\Client` auf und cached das Ergebnis von `getStatisticByDate()` 5 Minuten lang (eigener Cache `maidem_mosparo_dashboard`), um die API nicht bei jedem Dashboard-Reload anzufragen
- **ValidSubmissionsDataProvider** / **SpamSubmissionsDataProvider** — liefern die Zahlen für die `NumberWithIconWidget`-Kacheln
- **SubmissionsChartDataProvider** — bereitet die Tageswerte im Chart.js-Format für das `BarChartWidget` auf
