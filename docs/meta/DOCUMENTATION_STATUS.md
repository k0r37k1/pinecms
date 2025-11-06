# 📊 PineCMS - Dokumentations-Status-System

> **Last Updated:** 2025-01-06
> **Purpose:** Einheitliches Status-Marker-System für alle Dokumentationsdateien

---

## 🎯 Übersicht

Dieses Dokument definiert das konsistente Status-Marker-System für:

- `docs/CORE_FEATURES.md` - Feature-Spezifikationen
- `docs/ROADMAP.md` - Entwicklungs-Roadmap
- `docs/OFFICIAL_PLUGINS.md` - Plugin-Dokumentation

---

## 📋 Status-Marker (Basis)

Diese Marker zeigen den **Implementierungs-Status** eines Features:

| Marker | Status            | Bedeutung                                                    | Verwendung                      |
| ------ | ----------------- | ------------------------------------------------------------ | ------------------------------- |
| ✅     | **Abgeschlossen** | Feature ist implementiert, getestet und produktionsbereit    | Fertige Features                |
| 🚧     | **In Arbeit**     | Feature wird aktuell entwickelt                              | Aktive Entwicklung              |
| 📋     | **Geplant**       | Feature ist geplant, aber noch nicht gestartet               | Backlog                         |
| ⏸️     | **Pausiert**      | Feature-Entwicklung temporär pausiert                        | Blockierte/verschobene Features |
| ❌     | **Gestrichen**    | Feature wurde aus dem Scope entfernt (YAGNI/Deprioritisiert) | Entfernte Features              |

---

## 🎯 Priorität-Marker (Roadmap)

Diese Marker zeigen die **Priorität/Dringlichkeit** für die Roadmap:

| Marker | Priorität    | Bedeutung                              | Release-Impact    |
| ------ | ------------ | -------------------------------------- | ----------------- |
| 🔴     | **Critical** | Blocker für Release - MUSS fertig sein | Release blockiert |
| 🟠     | **High**     | Wichtig - sollte im Release sein       | Release-Ziel      |
| 🟡     | **Medium**   | Wünschenswert - kann verschoben werden | Optional          |
| 🟢     | **Low**      | Nice-to-have - niedrige Priorität      | Future Release    |

---

## 🔧 Typ-Marker (Features)

Diese Marker zeigen den **Feature-Typ**:

| Marker | Typ              | Bedeutung                           | Scope           |
| ------ | ---------------- | ----------------------------------- | --------------- |
| 🎯     | **Core Feature** | Kern-Funktionalität im Core CMS     | Core CMS        |
| 🔧     | **Enhancement**  | Verbesserung/Erweiterung            | Core CMS        |
| 🔌     | **Plugin**       | Plugin-Feature (Official/Community) | Plugin-System   |
| 🧪     | **Experimental** | Experimentelles Feature (Beta)      | Testing/Preview |

---

## 📝 Verwendung in Dokumenten

### CORE_FEATURES.md

**Format:**

```markdown
#### Feature Name

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

- Feature-Beschreibung 1
- Feature-Beschreibung 2
```

**Beispiel:**

```markdown
#### Web-Installer

**Status:** 🚧 In Arbeit
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

- System Requirements Check (PHP 8.3+, Extensions, Permissions)
- Environment Setup (.env Generator)
- SQLite Database Creation
```

---

### ROADMAP.md

**Format:**

```markdown
### Week X: Feature Category

**Status:** 📋 Geplant
**Priorität:** 🔴 Critical
**Version:** v1.0.0

#### Deliverables

- Feature 1
- Feature 2
```

**Beispiel:**

```markdown
### Week 1-2: Installer & Setup

**Status:** 🚧 In Arbeit
**Priorität:** 🔴 Critical
**Version:** v1.0.0

#### Deliverables

- Web-based Installer UI
- System Requirements Check
```

---

### OFFICIAL_PLUGINS.md

**Format:**

```markdown
### Plugin Name

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)
**Typ:** 🔌 Plugin

#### Features (~X Features)

- Feature 1
- Feature 2
```

**Beispiel:**

```markdown
### Newsletter Plugin

**Version:** 1.0.0
**Requires:** PineCMS >= 1.1.0
**Status:** 📋 Geplant (Planned for v1.1.0)
**Typ:** 🔌 Plugin

#### Features (~7 Features)

- Newsletter Editor
- Subscriber Management
```

---

## 🔄 Status-Übergang (Workflow)

Features durchlaufen typischerweise diese Status-Übergänge:

```
📋 Geplant → 🚧 In Arbeit → ✅ Abgeschlossen
              ↓
            ⏸️ Pausiert → 🚧 In Arbeit
              ↓
            ❌ Gestrichen
```

**Regeln:**

1. **Neue Features starten als:** 📋 Geplant
2. **Bei Entwicklungs-Start:** 📋 → 🚧
3. **Bei Fertigstellung:** 🚧 → ✅
4. **Bei Blockierung:** 🚧 → ⏸️ (mit Grund im Kommentar)
5. **Bei YAGNI-Entscheidung:** 📋/🚧/⏸️ → ❌ (mit Begründung)

---

## 📐 Konsistenz-Regeln

### 1. Status-Marker

- ✅ **IMMER verwenden** für abgeschlossene Features
- 📋 **IMMER verwenden** für geplante, nicht gestartete Features
- 🚧 **IMMER verwenden** für Features in aktiver Entwicklung
- ⏸️ **NUR verwenden** mit Begründung im Kommentar
- ❌ **NUR verwenden** mit Begründung (YAGNI, Deprecated, etc.)

### 2. Priorität-Marker (nur ROADMAP.md)

- 🔴 Critical: Features die **Release blockieren**
- 🟠 High: Features die **im Release-Ziel** sind
- 🟡 Medium: Features die **verschoben werden können**
- 🟢 Low: Features die **optional/nice-to-have** sind

### 3. Typ-Marker

- 🎯 Core Feature: **Kern-Funktionalität** (alle Nutzer brauchen es)
- 🔧 Enhancement: **Verbesserung** (optional, aber nützlich)
- 🔌 Plugin: **Plugin-Feature** (specialized use cases)
- 🧪 Experimental: **Beta/Testing** (nicht production-ready)

---

## 🔍 Status-Suche

Zum Finden aller Features mit einem bestimmten Status:

```bash
# Alle geplanten Features
grep "Status.*📋" docs/*.md

# Alle Features in Arbeit
grep "Status.*🚧" docs/*.md

# Alle abgeschlossenen Features
grep "Status.*✅" docs/*.md

# Alle Critical-Priorität Features
grep "Priorität.*🔴" docs/ROADMAP.md
```

---

## 📊 Status-Report (Beispiel)

### Core Features Status

| Status           | Anzahl | Prozent  |
| ---------------- | ------ | -------- |
| ✅ Abgeschlossen | 15     | 16%      |
| 🚧 In Arbeit     | 20     | 21%      |
| 📋 Geplant       | 60     | 63%      |
| **Total**        | **95** | **100%** |

---

## 🛠️ Wartung

**Wann Status aktualisieren:**

- Nach jedem Feature-Abschluss: 🚧 → ✅
- Bei Feature-Start: 📋 → 🚧
- Bei Scope-Änderungen: beliebig → ❌ (mit Begründung)
- Bei Blockierungen: 🚧 → ⏸️ (mit Grund)

**Review-Frequenz:**

- Wöchentlich: Status-Update für alle "In Arbeit" Features
- Monatlich: Review aller "Pausiert" Features (reaktivieren oder streichen?)
- Quarterly: Review aller "Geplant" Features (noch relevant?)

---

## ✅ Migration von Checkboxen zu Bullet-Points

### Vorher (Checkboxen-System)

**Format:**

```markdown
#### Feature Section

- [ ] System Requirements Check
- [ ] Environment Setup
- [ ] SQLite Database Creation
```

**Probleme:**

- ❌ **Ambiguität** - Checkboxen implizieren "To-do" statt Spezifikation
- ❌ **Doppelte Semantik** - Checkboxen + Status-Marker verwirrend
- ❌ **Visuelles Rauschen** - `[ ]` lenkt von Inhalt ab

---

### Nachher (Bullet-Points + Status-Marker)

**Format:**

```markdown
#### Feature Section

**Status:** 📋 Geplant
**Typ:** 🎯 Core Feature
**Version:** v1.0.0

- System Requirements Check
- Environment Setup
- SQLite Database Creation
```

**Vorteile:**

- ✅ **Klarheit** - Status explizit in Section-Header, nicht implizit in Checkboxen
- ✅ **Sauber** - Bullet-Points sind Standard für Spezifikations-Listen
- ✅ **Semantisch korrekt** - Features = Spezifikationen, nicht Tasks
- ✅ **Weniger Rauschen** - Fokus auf Inhalt, nicht auf UI-Element
- ✅ **Konsistenz** - OFFICIAL_PLUGINS.md nutzte bereits Bullet-Points

---

### Migration (2025-01-06)

**Durchgeführte Änderungen:**

1. **CORE_FEATURES.md** - ~300+ Checkboxen → Bullet-Points konvertiert
2. **ROADMAP.md** - ~200+ Checkboxen → Bullet-Points konvertiert
3. **OFFICIAL_PLUGINS.md** - Keine Änderung nötig (bereits Bullet-Points)

**Tool:** `sed -i '' 's/^- \[ \] /- /g' docs/*.md`

**Ergebnis:**

- Alle Feature-Listen nutzen jetzt **Bullet-Points** für Spezifikationen
- Status-Marker in **Section-Headern** für Progress-Tracking
- Konsistente Formatierung über alle 3 Dokumentations-Dateien

---

**Last Updated:** 2025-01-06
**Maintained By:** PineCMS Team
**Version:** 1.1.0 - Bullet-Points Migration
