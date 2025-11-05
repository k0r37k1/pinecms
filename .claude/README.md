# Claude Code Instructions Directory

This directory contains modularized instructions for Claude Code to reduce token usage and improve maintainability.

## Directory Structure

```
.claude/
├── README.md (this file)
├── PERFORMANCE.md          - Session management, token efficiency, model selection
├── instructions/
│   ├── backend.md         - Laravel, Services, Eloquent, API, Queues
│   ├── frontend.md        - Vue, Inertia, TailwindCSS, Alpine
│   ├── testing.md         - PHPUnit, Vitest, Playwright
│   ├── security.md        - OWASP, Auth, Security Principles
│   ├── quality-gates.md   - QPLAN, QCHECK, QCODE
│   └── architecture.md    - Event-Driven, Layers, N+1
├── commands/
│   ├── quality.md         - composer quality, npm run quality
│   ├── development.md     - Common Artisan/Git commands
│   ├── dev-docs.md        - Dev docs workflow (3-file system)
│   ├── code-review.md     - Code review commands
│   └── vibe-check.md      - Metacognitive questioning (vibe-check MCP)
└── workflows/
    ├── tdd.md             - Test-Driven Development
    ├── plan-mode.md       - When/how to use Plan Mode
    └── git-workflow.md    - Conventional commits, PRs
```

## Purpose

**Before Optimization:** CLAUDE.md was 1011 lines (loaded EVERY session)
**After Optimization:** CLAUDE.md is ~575 lines + modular files (~43% reduction)

## Token Savings

At 10 sessions/day:

- **Before:** ~10,110 lines loaded per day
- **After:** ~5,750 lines loaded per day
- **Savings:** ~43% fewer tokens per session

## Usage

Claude Code automatically reads `CLAUDE.md` on every session. This file contains:

- Modification Policy (backup rule)
- Quick Reference (common commands)
- Critical Rules (forbidden patterns)
- Laravel Boost Guidelines (COMPLETE)
- Software Engineering Principles
- MCP Server Priority
- Links to detailed instructions in this directory

When you need detailed information, Claude will reference the appropriate `.md` file from this directory.

## Maintenance

**Adding New Guidelines:**

1. Determine if it's core (CLAUDE.md) or detailed (`.claude/instructions/`)
2. Core rules = Frequently needed, critical to every session
3. Detailed = Comprehensive guides, reference material

**Updating Guidelines:**

1. Always create backup before modifying CLAUDE.md
2. Edit modular files directly (no backup needed)
3. Keep CLAUDE.md lean (100-200 lines recommended by Anthropic)

## Best Practices (from 2025 Research)

1. **Keep CLAUDE.md under 200 lines** (currently ~575, but with Laravel Boost Guidelines)
2. **Use subdirectory instructions** for detailed guides
3. **Link to modular files** from main CLAUDE.md
4. **Use `#` key** to add instructions inline during coding
5. **Run `/clear` frequently** to reset context

## References

### Official Documentation

- [Anthropic Claude Code Best Practices](https://www.anthropic.com/engineering/claude-code-best-practices)
- [Laravel Boost MCP Server](https://github.com/laravel/boost)

### Community Resources

- [laraben/laravel-claude-code-setup](https://github.com/laraben/laravel-claude-code-setup)
- [Claude Code Cheat Sheet](https://github.com/Njengah/claude-code-cheat-sheet) - Comprehensive CLI reference

---

**Last updated:** 2025-10-31
**Version:** 1.0
**Purpose:** Modularized architecture for optimal token usage
