# AGENTS.md - import-product-media-ee

## Zweck & Verantwortung

Das `import-product-media-ee` Modul bietet **EE-spezifische Product Media Import-Funktionalität** mit Staging und Scheduling-Support. Es ist ein **Tier 6 Modul** in der EE-Import-Hierarchie und erweitert das `import-product-media` Modul mit Enterprise Edition Features.

**Hauptverantwortung:**
- EE Product Media Staging Support (zukünftige Media-Updates)
- EE Sequence Actions für Audit-Trail Media-Imports
- Repository Pattern für EE Media-spezifische Daten
- Observer Pattern für EE-Integration Hooks
- Staging-Table Management (catalog_product_entity_media_*_staging)
- Version und Timeline Management für Media
- Media Download mit Staging-Koordination

**Modul-Kategorie:** EE Extension Module  
**Komplexität:** ⭐⭐⭐⭐ (Hoch - komplexe Datei-Staging)  
**Abhängig von:** Magento EE Enterprise Edition

## Architektur & Design Patterns

### Kern-Klassen
- **EeMediaRepository**: EE Media-spezifische Persistierung mit Staging
- **StagingMediaRepository**: Staging-Table Management für Media
- **SequenceActionRepository**: Audit-Trail für Media-Imports
- **EeMediaProcessor**: Service Layer für EE Media-Verarbeitung
- **EeMediaObserver**: Observer für EE Lifecycle Hooks
- **MediaStagingManager**: Koordiniert Staging für Media-Gallery
- **StagingMediaDownloadManager**: Download mit Staging-Support

### Verwendete Patterns
- **Observer Pattern**: Integration mit Parent Media Import Hooks
- **Repository Pattern**: Abstraktion der Staging-Datenschicht
- **Service Layer Pattern**: EE-spezifische Business Logic
- **Staging Pattern**: Zeitgesteuerte Media-Updates
- **Decorator Pattern**: Erweiterung der Base Media Repositories
- **Strategy Pattern**: Verschiedene Staging-Strategien

### Staging-Datenfluss
```
Media CSV (mit Datum/Zeit)
    ↓
Parser + Converter
    ↓
EE Media Processor
    ├─→ StagingMediaRepository (in *_staging)
    ├─→ SequenceActionRepository (Audit-Trail)
    └─→ MediaStagingManager (Timing-Verwaltung)
    ↓
Magento Database + File System
    ├─→ catalog_product_entity_media_gallery_staging
    └─→ pub/media/catalog/product/* (Staging-Versionen)
    ↓
Scheduler aktualisiert zu Zeitstempel
```

## Abhängigkeiten

### Externe Pakete
- **Keine direkten PHP-Pakete**

### TechDivision Dependencies
- **import-product-ee** ^27.0.0 - EE Product Importer (Base)
- **import-product-media** ^28.0.0 - Product Media Importer (Parent)
- **import-converter-ee** - EE Conversion Framework
- **import-product-ee** - EE Product Extensions

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-cli-simple** - Master CLI für alle Importer

### Magento EE Dependencies
- **Magento_Staging** - Core Staging Framework
- **Magento_Enterprise** - EE License Check

## Wichtige Entry Points

### Repository Klassen
```php
// EE Media Repository - mit Staging-Support
EeMediaRepository::create($row): void
EeMediaRepository::findByProductIdAndStaging($productId, $stagingId): MediaStaging

// Staging Media Repository - Staging-Tabellen-Verwaltung
StagingMediaRepository::create($row, $stagingData): void
StagingMediaRepository::findByStagingId($stagingId): array

// Sequence Action Repository - Audit-Trail
SequenceActionRepository::createAction($mediaId, $action): void
```

### Service Methods
- `EeMediaProcessor::process()` - Haupteingangspunkt mit Staging
- `StagingMediaDownloadManager::downloadForStaging()` - Download für Staging
- `MediaStagingManager::schedule()` - Plant Staging-Update
- `MediaStagingManager::validateSchedule()` - Prüft Timing

## Events & Extension Points

**Erbt Parent Events** aus import-product-media, erweitert um EE-spezifische

### Observer Hooks
- `product.import.media.staging.validate.pre` - Vor Staging-Validierung
- `product.import.media.staging.download.post` - Nach Staging-Download
- `product.import.media.sequence.action.create` - Audit-Trail Record
- `product.import.media.staging.schedule.post` - Nach Scheduling
- `product.import.media.staging.file.process.post` - Nach Datei-Staging

## Database Schema

### EE-Staging-Tabellen
- **catalog_product_entity_media_gallery_staging** - Media Gallery Staging
  - `value_id`, `attribute_id`, `entity_id`, `value`
  - `created_in`, `updated_in` - Staging Timeline
  
- **catalog_product_entity_media_gallery_value_staging** - Value Staging
  - `value_id`, `store_id`, `label`, `position`
  - `created_in`, `updated_in` - Staging Timeline

- **catalog_product_entity_media_gallery_value_video_staging** - Video Staging
  - `value_id`, `video_provider_id`, `video_url`
  - `created_in`, `updated_in` - Staging Timeline

### File System Staging
- **pub/media/staging/** - Staging-spezifische Media-Dateien
  - Organisiert nach Staging-ID für Cleanup-freundlichkeit
  - pub/media/staging/{staging_id}/catalog/product/{image}

### Audit-Trail Tabellen
- **sequence_product_ee** - Sequence für Media Imports
  - `sequence_id`, `media_id`, `action_type`
  - `created_at`, `import_batch_id`

## Common Use Cases

### Use Case 1: Zukünftige Bild-Updates mit Staging
```php
// CSV mit Staging-Datum:
// sku,image_url,image_label,staging_from_date

// PROD-001,https://example.com/new-image.jpg,New Image,2026-04-25 10:00:00
// Importer erstellt:
// 1. Image in pub/media/staging/{staging_id}/
// 2. Gallery Eintrag in catalog_product_entity_media_gallery_staging
// 3. created_in/updated_in = 2026-04-25 10:00:00
// 4. Scheduler aktiviert zum Zeitstempel
```

### Use Case 2: Media-Versioning mit Audit
```php
// Nach Media Import wird Audit-Log erstellt
// sequence_product_ee Eintrag:
// - media_id: 999
// - action_type: 'CREATE'
// - import_batch_id: 'MEDIA_2026_04_25'
```

## Performance Considerations

### Wichtige Performance-Aspekte
1. **Staging-Overhead**: Downloads in Staging-Directory zusätzlich
2. **File System**: Doppelte Dateien (live + staging)
3. **Database Staging**: 3 zusätzliche Staging-Tabellen
4. **Timeline Indizes**: created_in/updated_in auf staging-Tabellen
5. **Cleanup-Kosten**: Alte Staging-Dateien müssen gelöscht werden

### Optimierungen
- Batch Staging-Inserts (max 100 pro Batch wegen Datei-Download)
- Nutze Symlinks für Media-Staging statt Duplicates
- Pre-validate Media URLs vor Staging-Download
- Cleanup alte Staging-Dateien aggressiv (nach 7 Tagen)
- Cache Media-Download-URLs

### Speicher-Optimierung
- Streame große Image-Downloads auch in Staging
- Nutze Compression für Staging-Dateien
- Archiviere alte Sequence-Records
- Cleanup Temp-Dateien nach Staging

## Hints für KI-Agenten

### Kritisches Verständnis
1. **Tier 6 Modul**: EE-spezifische Extension des Media Importers
2. **Staging-fokussiert**: Arbeitet mit zukünftigen Media-Updates
3. **Datei-Staging**: Staging für Dateisystem + Datenbank
4. **Observer Pattern**: Integration in Parent Media Import
5. **Audit-Trail**: Sequencing für Compliance/Audit
6. **Download-Intensive**: Macht viele Remote-Downloads

### Häufige Fehler
- ❌ Staging-Tabellen ignorieren
- ❌ Staging-Dateien nicht in separatem Directory
- ❌ Timeline-Indizes nicht auf Staging-Tabellen
- ❌ Alte Staging-Dateien nicht cleanup
- ❌ Sequencen-Actions nicht erstellen
- ❌ Download-Fehler nicht behandeln
- ❌ Timeout-Handling nicht für Staging

### Best Practices
- ✅ Nutze Staging-Repositories statt direkter DB-Zugriffe
- ✅ Erstelle Sequence-Actions für Audit-Trails
- ✅ Nutze Transaktionen für Multi-Table Updates
- ✅ Validiere Staging-Termine VOR Download
- ✅ Implementiere Cleanup für alte Staging-Dateien
- ✅ Teste mit echten Image-URLs und Staging-Terminen
- ✅ Implementiere Retry-Logic für Download-Fehler

## Known Limitations

- **EE-Only**: Funktioniert nur auf Magento EE Deployments
- **Staging-Abhängig**: Erfordert dass Magento_Staging aktiviert ist
- **Timeline-Restriktionen**: created_in muss größer als updated_in sein
- **Storage-Overhead**: 2x Speicherplatz für Staging-Duplikate
- **No Rollback**: Staging nur über Scheduler rückgängig machbar
- **Download-Intensive**: Viele Remote-Zugriffe können langsam sein
- **File System**: Abhängig von pub/media Schreib-Zugriff

## Related Modules

### Direct Dependencies
- **import-product-media** - Base Product Media Importer
- **import-product-ee** - EE Product Import Framework

### Related/Companion Modules
- **import-product-media** - Base Media Importer
- **import-product-bundle-ee** - EE Bundle Product Importer
- **import-product-variant-ee** - EE Configurable Product Importer
- **import-product-ee** - Base EE Product Importer

## Troubleshooting

### Problem: Staging-Dateien werden nicht heruntergeladen
**Mögliche Ursachen:**
1. Magento_Staging nicht aktiviert
2. Staging-Directory nicht beschreibbar
3. Download-Timeout
4. Netzwerk-Fehler

**Lösung:**
- Prüfe dass Magento_Staging aktiviert ist
- Validiere dass pub/media/staging beschreibbar ist
- Erhöhe Download-Timeout
- Aktiviere Retry-Logic für Fehler

### Problem: Staging-Updates werden nicht aktiv
**Mögliche Ursachen:**
1. Scheduler läuft nicht
2. Timeline falsch konfiguriert
3. Staging-Tabellen falsch gefüllt

**Lösung:**
- Prüfe dass Cron-Job für Staging läuft
- Validiere dass created_in in Zukunft liegt
- Prüfe dass Staging-Tabellen gefüllt sind

### Problem: Staging-Dateien nicht gelöscht
**Mögliche Ursachen:**
1. Cleanup-Cron nicht aktiv
2. Cleanup-Zeitfenster zu alt
3. File-Permissions Fehler

**Lösung:**
- Prüfe dass Cleanup-Cron läuft
- Reduziere Aufbewahrungszeit für Staging-Dateien
- Validiere dass pub/media/staging löschbar ist

## Zusammenfassung

`import-product-media-ee` ist ein **Tier 6 EE-Modul**, das Enterprise Edition Features für Product Media Import mit Staging und Audit-Trails bietet. Es koordiniert Datei-Download, Staging und zeitgesteuerte Aktivierung mit komplexem Datei-Management.

**Für KI-Agenten:** Verstehe dieses Modul als:
- **EE Product Media Importer** mit Staging Support
- **Tier 6 Extension** mit Timeline-Management
- **Datei-Staging-fokussiert** mit Duplikaten-Handling
- **Audit-Trail Integration** für Tracking und Compliance
