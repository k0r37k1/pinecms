---
epic_id: 007
title: Theme System & Frontend
status: pending
priority: critical
estimated_duration: 1.5 weeks
actual_duration: null
week: Week 9-10
version: v1.0.0
tasks_total: 8
tasks_completed: 0
---

# Epic 007: Theme System & Frontend

## 🎯 Epic Goals

- Build flexible theme system with theme.json format
- Create theme loader and switcher service
- Develop default theme with Blade + Alpine.js + TailwindCSS
- Support multiple layouts (default, full-width, sidebar)
- Implement frontend event system (@event directive)
- Add dark/light mode toggle
- Build template selection UI for admin
- Configure theme asset compilation with Vite

## 📦 Features Delivered

- Theme structure (theme.json, layouts, templates, partials)
- Theme loader service
- Theme switcher (admin UI)
- Default theme (modern, responsive, accessible)
- Theme layouts (default, full-width, sidebar, custom)
- Frontend event system (Blade @event directive)
- Dark/light mode toggle
- Template selection UI
- Theme asset compilation (Vite integration)

## 📋 Tasks (8 total)

- [ ] 054 - Theme Structure (6h)
- [ ] 055 - Theme Loader & Switcher (7h)
- [ ] 056 - Default Theme (8h)
- [ ] 057 - Theme Layouts (6h)
- [ ] 058 - Frontend Event System (5h)
- [ ] 059 - Dark/Light Mode (5h)
- [ ] 060 - Template Selection UI (4h)
- [ ] 061 - Theme Asset Compilation (5h)

## 🔗 Dependencies

**Blocks:** Frontend display of content

**Requires:** Epic 003 (Content Management - posts, pages to display)

## ✅ Epic Success Criteria

- [ ] Theme structure defined (theme.json format documented)
- [ ] Theme loader loads and caches themes
- [ ] Theme switcher changes active theme
- [ ] Default theme renders posts and pages correctly
- [ ] Layouts work (default, full-width, sidebar)
- [ ] Frontend events dispatch and listen correctly
- [ ] Dark/light mode toggle persists preference
- [ ] Template selection UI lets admins choose templates
- [ ] Theme assets compile with Vite
- [ ] All 8 tasks completed with tests passing
- [ ] Quality gates passing

## 📚 References

**PRD**: `docs/prd/05-CORE-FEATURES.md` Section 7
**Timeline**: Week 9-10 (v1.0.0)
**Architecture**: Blade Templates + Alpine.js + TailwindCSS
**Performance**: Theme load < 500ms
