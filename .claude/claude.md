# PineCMS Project Guide

> **Note:** For Laravel Boost Guidelines, see root `CLAUDE.md`
> This file contains PineCMS-specific rules, workflows, and best practices.

---

## ⚠️ Modification Policy

**CRITICAL: Before ANY modification to this file:**

```bash
cp .claude/claude.md ".claude/claude.$(date +%Y%m%d_%H%M%S).md.backup"
```

**Backup location:** Keep backups in `.claude/` directory

**Backup retention:** Keep last 5 backups, delete older ones

**This rule applies to:**

- Content updates
- Structural changes
- Optimization iterations
- Adding/removing sections

**Why:** This file is auto-read on every session. Breaking it breaks the entire AI workflow.

---

## 🚀 Quick Reference

**Before ANY coding:**

1. `/clear` - Clear context for new task
2. Use `search-docs` tool - Fetch Laravel/Inertia/Vue docs (Laravel Boost MCP)
3. Use `context7` - For PrimeVue/TipTap/Alpine docs
4. Plan first (`Shift+Tab` for complex tasks)

**Common Commands:**

```bash
# PHP Quality Check
composer quality              # Format, analyze, test (all-in-one)

# JavaScript Quality Check
npm run quality               # Format, lint, type-check, test (all-in-one)
```

**After commit:** Always run `/clear` to reset context

**📚 Detailed Instructions:** See `.claude/instructions/` for comprehensive guidelines

---

## 🚨 CRITICAL Rules

### Forbidden Patterns

- ❌ **NEVER use Hooks** - This project uses Events only (Laravel event system)
- ❌ **NEVER skip `declare(strict_types=1);`** in PHP files
- ❌ **NEVER use `env()` directly** - ALWAYS use `config()` (after config:cache, env() returns null)
- ❌ **NEVER use CommonJS `require`** - Use ES modules
- ❌ **NEVER create inline styles** - Use TailwindCSS utility classes
- ❌ **NEVER commit `console.log`** - Remove debug statements
- ❌ **NEVER delete files/code without asking** - Always request user approval first
- ❌ **NEVER create .md files outside `docs/`** - Exception: README.md in project root

### Protected Areas (Ask Before Modifying)

- Database Migrations (schema changes need review)
- Security Code (authentication, authorization, encryption)
- Test Files (changes affect test validity)
- API Contracts (breaking changes affect consumers)
- Configuration (.env, config files with secrets)

---

## 🛠️ Tech Stack

**Backend:** PHP 8.3 | Laravel 12

**Storage:** SQLite (relational) + Flat-File (content/markdown) - **Hybrid Approach**

**Frontend (Public):** Blade Templates | PHP | TailwindCSS 4.1 | Alpine.js

**Admin Panel:** Vue 3.5 Composition API | Inertia.js 2.x | PrimeVue | Pinia | TailwindCSS 4.1

**Build:** Vite

**Testing:** PHPUnit | Vitest | Playwright

**Quality:** PHPStan (level 8) | ESLint | Prettier | Pint

---

## 🎯 Software Engineering Principles

Apply these principles when designing and implementing features:

**KISS (Keep It Simple)** - Prefer simple solutions, avoid over-engineering

**DRY (Don't Repeat Yourself)** - Extract repeated code, use Laravel's built-in features

**YAGNI (You Aren't Gonna Need It)** - Implement only what's currently needed

**SOLID Principles:**

- **S** - Single Responsibility (one reason to change)
- **O** - Open/Closed (extend, don't modify)
- **L** - Liskov Substitution (subtypes substitutable)
- **I** - Interface Segregation (focused interfaces)
- **D** - Dependency Inversion (depend on abstractions)

**Separation of Concerns:**

- Business logic in Services
- Data access in Repositories/Models
- HTTP handling in Controllers
- Presentation in Views/Components
- Side effects in Event Listeners

**📚 Details:** `.claude/instructions/architecture.md`

---

## 🔌 MCP Server Priority

Use MCP servers in this order for optimal results:

1. **Laravel Boost** - Laravel/PHP ecosystem (`search-docs`, `tinker`, `database-query`, `browser-logs`)
2. **filesystem** - Flat-file operations (Critical for PineCMS content management)
3. **Laravel MCP Companion** - Alternative Laravel docs (`search_laravel_docs_with_context`)
4. **context7** - Modern framework documentation (PrimeVue, TipTap, Alpine.js)
5. **chrome-devtools** - Browser automation and debugging
6. **eslint** - JavaScript/TypeScript linting
7. **playwright** - End-to-end testing
8. **gitmcp** - Git/GitHub operations

---

## 🤖 Specialized Agents

**14 agents available** via `Task` tool - See `.claude/agents/` for full descriptions

**Development:** frontend-developer, backend-architect, fullstack-developer, ui-ux-designer

**Tools:** code-reviewer, test-engineer, debugger, error-detective, context-manager

**Specialists:** task-decomposition-expert, database-architect, architect-reviewer, deployment-engineer, security-auditor

**Usage:**

- Simple tasks → Implement directly
- Complex tasks → Use specialized agents
- **Multi-step features → Start with task-decomposition-expert for planning**

---

## 🆕 New Best Practices (2025)

### Inline Instructions

Press `#` to give Claude instructions that auto-incorporate into this file. Useful for documenting commands, files, style guidelines while coding.

### Subagent Limitations

- ❌ NO thinking mode (can't monitor progress)
- ❌ NO stepwise plans (single-shot execution only)
- ⚠️ Requires explicit MCP tool permissions

### Test-Driven Development (MANDATORY)

**Be explicit about TDD to prevent mock implementations:**

```text
"I'm doing TDD. Write failing test first, don't create mock implementation."
```

**RED → GREEN → REFACTOR cycle:**

1. Write failing test
2. Minimal implementation to pass
3. Refactor with confidence

**📚 Details:** `.claude/workflows/tdd.md`

### Plan Mode (`Shift+Tab`)

**Use for:**

- Complex multi-step implementations
- Large-scale refactoring
- Architecture decisions
- Features requiring multiple agents

**📚 Details:** `.claude/workflows/plan-mode.md`

### MCP Debugging

```bash
claude --mcp-debug  # Troubleshoot MCP configuration issues
```

---

## 🎯 Advanced Workflow Systems

**Inspired by: "Claude Code is a Beast - Tips from 6 Months" (Reddit r/ClaudeCode)**

### Skills Auto-Activation System

**Problem:** Skills/Instructions get ignored even when relevant.

**Solution:** Automatic skill activation via hooks + `skill-rules.json`

**How it works:**

- `UserPromptSubmit` hook analyzes your prompt BEFORE Claude sees it
- Matches keywords, intent patterns, file paths against `.claude/skill-rules.json`
- Injects skill reminders into Claude's context automatically
- No more "Did you check the guidelines?" - it happens automatically

**Configuration:** `.claude/skill-rules.json` (6 skills configured)

- `backend-dev-guidelines` - Controllers, Services, Repositories, Events
- `frontend-dev-guidelines` - Vue, Inertia, PrimeVue, TailwindCSS
- `testing-guidelines` - PHPUnit, Vitest, Playwright, TDD
- `security-guidelines` - OWASP, Auth, XSS/CSRF prevention
- `architecture-guidelines` - SOLID, Event-Driven, N+1 prevention
- `quality-gates` - QPLAN, QCHECK, QCODE workflows

**Example:**

```text
You: "Create a new controller for user management"

[Hook activates backend-dev-guidelines automatically]

Claude: 🎯 SKILL ACTIVATION CHECK
   • backend-dev-guidelines
     Laravel backend patterns: Controllers, Services, Repositories
     📄 See: .claude/instructions/backend.md

   [Implements following all backend patterns]
```

---

### Dev Docs Workflow (#PreventLostPlot)

**Problem:** Claude loses the plot during large tasks, forgets the plan after 30 minutes.

**Solution:** 3-File Dev Docs System

**For EVERY large task (3+ steps), create:**

```text
~/dev/active/[task-name]/
├── [task-name]-plan.md      # Approved strategic plan
├── [task-name]-context.md   # Key files, decisions
└── [task-name]-tasks.md     # Checklist (auto-updated)
```

**Workflow:**

1. **Planning:** Use `Shift+Tab` or `/dev-docs` to create comprehensive plan
2. **Review:** Catch silly mistakes early
3. **Create Docs:** `/create-dev-docs [task-name]` → Creates 3 files
4. **Implement:** Small chunks (1-2 tasks at a time)
5. **Update:** `/update-dev-docs` before `/clear`
6. **Continue:** "Continue with [task-name]" in new session

**Why it works:**

- ✅ Claude never loses track of plan
- ✅ Seamless continuation across sessions/compactions
- ✅ Clear, trackable progress
- ✅ No tangents or forgotten tasks

**Commands:** See `.claude/commands/dev-docs.md`

---

### Code Review Workflow

**Problem:** Errors and bad patterns slip through unnoticed.

**Solution:** Proactive code review with agents + slash commands

**Commands:**

- `/code-review` - Launch `code-reviewer` agent for architectural review
- `/build-and-fix` - Run all build checks, fix errors systematically
- `/quick-quality` - Fast quality check (no tests)
- `/pr-ready` - Full pre-PR checklist

**Best Practice:** Have Claude review its own code periodically during implementation.

**Benefits:**

- Catches critical errors early
- Finds security flaws
- Ensures architecture consistency
- Saves headaches later

**Example:**

```text
[After implementing user auth]

You: /code-review

Agent: Found 2 critical issues:
- Missing try-catch around Socialite call
- API call not wrapped in error handling

Shall I fix these issues?
```

**Details:** `.claude/commands/code-review.md`

---

### Hooks System (#NoMessLeftBehind)

**Problem:** Build errors left unnoticed, discovered hours later.

**Solution:** Automated quality checks via **PHP hooks** (beyondcode/claude-hooks-sdk)

**Hook Pipeline:**

```text
Claude finishes responding
  ↓
PostToolUse: Track which files were edited (PHP)
  ↓
Stop: Run build checks on modified areas (PHP)
  ↓
  - TypeScript check (if frontend modified)
  - PHPStan check (if backend modified)
  ↓
Show errors immediately + gentle error handling reminder
  ↓
Result: Zero errors left behind
```

**Configured Hooks (PHP):**

- `UserPromptSubmit` → Skills auto-activation (`.claude/hooks/user-prompt-submit.php`)
- `PostToolUse` → File edit tracking (`.claude/hooks/post-tool-use.php`)
- `Stop` → Build checks + error reminders (`.claude/hooks/stop.php`)

**Technology:** PHP hooks using `beyondcode/claude-hooks-sdk` (Laravel-native, type-safe)

**Configuration:** `.claude/settings.local.json` → `hooks` section

**Result:**

- ❌ Never: "Claude left 10 TypeScript errors"
- ✅ Always: Errors caught and fixed immediately

---

### Key Principles from Reddit Post

**1. Planning is King**

- NEVER start without planning (minimum: `Shift+Tab`)
- "Wouldn't have a builder start without drawing plans first"

**2. Context is Everything**

- "Ask not what Claude can do for you, ask what context you can give to Claude"
- Bad outputs = Usually bad prompting (especially when tired)

**3. Re-prompt Often**

- Use `Double-ESC` to access previous prompts
- Try again with knowledge of what you DON'T want

**4. Step In When Needed**

- 30 min Claude struggle vs. 2 min manual fix? → Fix it yourself
- AI isn't magic for logic puzzles and human intuition

**5. Review Everything**

- Implement in small chunks → Review → Continue
- Have Claude review its own code (proactively!)
- Catch mistakes early, not during PR review

**6. No Vibe-Coding**

- Always: Plan → Review → Iterate → Explore approaches
- Never: "Just do it" without understanding

---

## 📚 Detailed Instructions

**This file contains ONLY core rules. For comprehensive guidelines:**

### Coding Standards (Industry Standard)

**Follow Spatie Laravel Guidelines:** `.claude/spatie-laravel-guidelines.md`

This is the **industry standard** for Laravel development, maintained by Spatie. Follow these conventions for all PHP/Laravel code:
- PSR-1, PSR-2, PSR-12 compliance
- Laravel naming conventions (routes, controllers, commands)
- Type declarations and docblock standards
- Happy path last pattern (early returns)
- Validation, authorization, testing patterns

**Priority:** These standards override generic PHP practices but work alongside Laravel Boost Guidelines.

### Instructions

- `.claude/instructions/backend.md` - Laravel, Services, Eloquent, API, Queues
- `.claude/instructions/frontend.md` - Vue, Inertia, TailwindCSS, Alpine
- `.claude/instructions/testing.md` - PHPUnit, Vitest, Playwright
- `.claude/instructions/security.md` - OWASP, Auth, Security Principles
- `.claude/instructions/quality-gates.md` - QPLAN, QCHECK, QCODE
- `.claude/instructions/architecture.md` - Event-Driven, Layers, N+1

### Commands

- `.claude/commands/quality.md` - composer quality, npm run quality
- `.claude/commands/development.md` - Common Artisan/Git commands
- `.claude/commands/dev-docs.md` - Dev docs workflow (Reddit Best Practices)
- `.claude/commands/code-review.md` - Code review commands

### Workflows

- `.claude/workflows/tdd.md` - Test-Driven Development
- `.claude/workflows/plan-mode.md` - When/how to use Plan Mode
- `.claude/workflows/git-workflow.md` - Conventional commits, PRs

### Advanced

- `.claude/skill-rules.json` - Skills auto-activation configuration
- `.claude/hooks/` - Hook implementations
- `.claude/REDDIT-BEST-PRACTICES.md` - Complete Reddit guide

---

**Last updated:** 2025-10-30

**Version:** 2.4 - PHP Hooks Integration

**Project:** PineCMS - Security & Privacy-First Flat-File Hybrid CMS

**What's New in v2.4:**

- 🔧 **PHP Hooks** - Migrated from Node.js to PHP using `beyondcode/claude-hooks-sdk` (NEW!)
- ⭐ **Spatie Laravel Guidelines** - Industry standard coding conventions
- 📂 Separated Laravel Boost Guidelines (root `CLAUDE.md`) from project rules (this file)
- 🎯 Skills Auto-Activation System (skill-rules.json + PHP hooks)
- 📝 Dev Docs Workflow (3-File System for large tasks)
- ✅ Code Review Commands (/code-review, /build-and-fix, /pr-ready)
- 🔍 Hooks System (#NoMessLeftBehind - automatic build checks via PHP)
- 📚 Reddit Best Practices integration from "Claude Code is a Beast"
