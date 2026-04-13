# AGENTS.md - import-product-media-ee

## Zweck & Verantwortung

Das `import-product-media-ee` Modul bietet **EE-spezifische Product Media Import-Funktionalität**. Es ist ein **Tier 6 Modul** und erweitert `import-product-media`.

**Hauptverantwortung:**
- EE Product Media Staging Support
- EE Sequence Actions für Media
- Repository Pattern für EE Media-Daten
- Service Layer für EE Media-Verarbeitung
- Observer Pattern für EE Media-Hooks

## Architektur & Design Patterns

### Kern-Klassen
- **EeMediaRepository**: EE Media Persistierung
- **StagingMediaRepository**: Staging-Support
- **EeMediaProcessor**: Service Layer
- **EeMediaObserver**: Observer für EE-Hooks

### Verwendete Patterns
- **Observer Pattern**: Für EE-Hooks
- **Repository Pattern**: Für Daten-Persistierung
- **Service Layer**: Für Business Logic

## Abhängigkeiten

### Externe Pakete
- **Keine**

### TechDivision Dependencies
- **import-product-ee** ^27.0.0 - EE Product Importer
- **import-product-media** ^28.0.0 - Product Media Importer

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-cli-simple** - Master CLI

## Wichtige Entry Points

### Repository Klassen
```php
// EE Media Repository
EeMediaRepository::create($row): void

// Staging Media Repository
StagingMediaRepository::create($row): void
```

## Events & Extension Points

**Keine Events** - Tier 6 EE-Modul

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 6 Modul**: Erweitert Product Media Importer mit EE-Features
2. **EE-fokussiert**: Spezialisiert auf EE Staging
3. **Observer Pattern**: Für EE-Hooks
4. **Repository Pattern**: Für Persistierung
5. **Service Layer**: Für Business Logic

## Bekannte Einschränkungen

- **EE-Only**: Nur für Magento EE Deployments
- **Media-EE-Only**: Nur für EE Product Media

## Zusammenfassung

`import-product-media-ee` ist ein **Tier 6 Modul**, das EE-spezifische Product Media Import-Funktionalität bietet. Es erweitert den Product Media Importer mit EE-Features.

**Für Agenten:** Verstehe dieses Modul als **EE Product Media Importer** mit Staging Support.
