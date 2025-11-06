# PineCMS Claude Code Quick Reference

Schnellreferenz für die perfekte Symbiose aller Systeme.

---

## 🎯 System Layers (von außen nach innen)

```
USER
  ↓
ENTRY POINTS (Hooks, Commands)
  ↓
INTELLIGENCE (Skill-Rules, Instructions)
  ↓
EXECUTION (Skills, Agents)
  ↓
VALIDATION (PostToolUse Hooks)
  ↓
FEEDBACK LOOP
```

---

## 🚀 Common Workflows

### 1️⃣ Simple Feature (Single File)

```bash
# Just start coding
"Add email validation to User model"

→ skill-rules auto-activates: backend-dev-guidelines
→ Claude reads: backend.md, security.md
→ Claude writes: app/Models/User.php
→ PostToolUse hook validates
→ If errors: Claude fixes immediately
→ Done!
```

### 2️⃣ Complex Feature (Multi-Agent)

```bash
# Use /next command
/next "Implement content search with filters"

→ Command triggers: Research → Plan → Implement
→ Claude spawns agents:
  • backend-architect (Search service)
  • frontend-developer (Search UI)
  • test-engineer (Tests)
→ Each agent: Write → Hook validates → Fix → Continue
→ Final: /check for comprehensive validation
→ Done!
```

### 3️⃣ Bug Fix

```bash
# skill-rules auto-activates debugging skills
"Authentication redirects to wrong page"

→ Auto-activates: systematic-debugging (superpowers)
→ Claude reads: security.md, architecture.md
→ Claude follows 4-phase debugging:
  1. Reproduce
  2. Isolate
  3. Fix
  4. Verify
→ Hooks validate fix
→ Done!
```

### 4️⃣ Refactoring

```bash
# Use brainstorming + agents
/superpowers:brainstorm
"Refactor content service for better testability"

→ Skill guides design discussion
→ Claude creates plan
→ Spawns agents for parallel refactoring
→ Hooks ensure no regressions
→ Done!
```

---

## 🎭 When Each System Activates

### **Skills (Automatic)**

```
test-driven-development → When implementing features
systematic-debugging → When fixing bugs
brainstorming → When designing complex features
writing-plans → When /next or complex task
verification-before-completion → Before declaring done
```

### **Agents (On-Demand or by Commands)**

```
backend-architect → Backend design/implementation
frontend-developer → Vue/Inertia/PrimeVue work
fullstack-developer → Full-stack features
code-reviewer → Quality review (auto or /code-review)
test-engineer → Test creation/fixing
debugger → Complex debugging
```

### **Hooks (Always - After Every Edit)**

```
PostToolUse → After Edit/Write (ALWAYS runs)
  1. PHP hook tracks file
  2. Bash hook validates:
     - composer quality (PHP)
     - npm run quality (JS/Vue)
     - PineCMS specific checks
```

### **Commands (User-Triggered)**

```
/check → Comprehensive quality validation
/next → Structured implementation workflow
/dev-docs → Long-task documentation
/quality → Quick quality check
/code-review → Review with agent
/vibe-check → Metacognitive check
```

---

## 🎯 Decision Tree

```
┌─────────────────────────────────────┐
│ What are you trying to do?         │
└─────────────┬───────────────────────┘
              │
    ┌─────────┼─────────┐
    │         │         │
    ▼         ▼         ▼
  Simple   Complex   Quality
  Task     Feature   Check
    │         │         │
    │         │         └─→ /check
    │         │
    │         └─→ /next
    │              ↓
    │         Spawns Agents
    │              ↓
    └─────────────→ Implementation
                   ↓
              PostToolUse Hook
                   ↓
            ┌──────┴──────┐
            │             │
            ▼             ▼
        Errors?        Success?
            │             │
            │             └─→ Continue
            │
            └─→ Claude Fixes
                    ↓
               Re-validates
                    ↓
              Repeat until ✅
```

---

## 💡 Pro Tips

### Tip 1: Trust the Automation

```
❌ "Let me disable hooks for this"
✅ "Hooks caught an issue, let me fix it"

If hooks block, there's a REAL issue.
```

### Tip 2: Use /next for Complex Work

```
❌ "Just implement X" (ad-hoc)
✅ "/next Implement X" (structured)

Research → Plan → Implement = Better results
```

### Tip 3: Let Skills Auto-Activate

```
❌ Manually invoking skills each time
✅ Just describe what you want

skill-rules.json handles activation automatically
```

### Tip 4: Spawn Agents for Parallel Work

```
❌ "Do this, then this, then this"
✅ "Spawn agents for backend, frontend, tests"

Agents work in parallel = Faster completion
```

### Tip 5: /check Before PR

```
❌ "Looks good, let's commit"
✅ "/check" then commit

Comprehensive validation prevents CI failures
```

---

## 🔧 Configuration Quick Access

### Enable/Disable Hooks

```bash
# In .claude-hooks-config.sh
export CLAUDE_HOOKS_ENABLED=false  # Disable
export CLAUDE_HOOKS_ENABLED=true   # Enable (default)
```

### Debug Mode

```bash
# See what hooks are doing
export CLAUDE_HOOKS_DEBUG=1
```

### Ignore Files

```bash
# Edit .claude/hooks/.claude-hooks-ignore
vendor/**
node_modules/**
your-custom-ignores/**
```

### Force Stack Detection

```bash
# In .claude-hooks-config.sh
export CLAUDE_HOOKS_LARAVEL_STACK="pinecms-inertia-vue-primevue"
```

---

## 🎯 Cheat Sheet

| Want to...                | Use...                    | Result                               |
| ------------------------- | ------------------------- | ------------------------------------ |
| Implement simple feature  | Just code                 | Auto: Skills + Hooks validate        |
| Implement complex feature | `/next`                   | Research → Plan → Agents → Validate  |
| Fix bug                   | Describe issue            | Auto: debugging skill + fix workflow |
| Design complex system     | `/superpowers:brainstorm` | Guided design discussion             |
| Validate everything       | `/check`                  | Zero-tolerance quality check         |
| Review code               | `/code-review`            | Agent reviews architecture           |
| Track long task           | `/dev-docs`               | 3-file system (plan/context/tasks)   |
| Meta-check approach       | `/vibe-check`             | Prevent tunnel vision                |

---

## 🚨 When Things Go Wrong

### Hook Blocks Unexpectedly

```bash
# 1. See what's failing
bash .claude/hooks/pinecms-lint.sh

# 2. Run quality checks manually
composer quality
npm run quality

# 3. Fix issues
# 4. Try again
```

### Skill Not Activating

```bash
# Check skill-rules.json
# Ensure keywords/patterns match your prompt
# Or explicitly invoke: /superpowers:brainstorm
```

### Agent Produces Wrong Code

```bash
# Hooks will catch it!
# If hook blocks, Claude will fix
# If passes but wrong logic → describe issue
```

### Debug Mode Not Working

```bash
# Set in terminal before running
export CLAUDE_HOOKS_DEBUG=1

# Or add to .claude-hooks-config.sh permanently
```

---

## 📊 System Health Check

### Verify Everything is Working

```bash
# 1. Check hooks
bash .claude/hooks/pinecms-lint.sh
# Should run without errors (or show specific issues)

# 2. Check skills
/help
# Should show superpowers commands

# 3. Check commands
ls .claude/commands/
# Should show: check.md, next.md, etc.

# 4. Check quality tools
composer quality
npm run quality
# Both should work
```

---

## 🔗 Full Documentation

- **System Architecture** → `.claude/SYSTEM-ARCHITECTURE.md`
- **Hooks System** → `.claude/hooks/README.md`
- **Workflows** → `.claude/workflows/`
- **Commands** → `.claude/commands/`
- **Instructions** → `.claude/instructions/`
- **Core Guidelines** → `CLAUDE.md` + `.claude/CLAUDE.md`

---

## 🎉 Remember

**The system is designed to:**

1. ✅ Guide you (Skills + Instructions)
2. ✅ Execute efficiently (Agents + Commands)
3. ✅ Validate automatically (Hooks)
4. ✅ Prevent errors (Zero tolerance)
5. ✅ Learn continuously (vibe-check + constitution)

**You don't fight the system, you work WITH it!** 🚀

---

**Last Updated:** 2025-10-31
**Version:** 1.0 - Integrated System
