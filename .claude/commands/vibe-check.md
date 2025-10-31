---
title: Vibe Check MCP Commands
version: 1.0
last_updated: 2025-10-31
author: PineCMS Team
status: active
related:
  - .claude/instructions/quality-gates.md
  - .claude/workflows/plan-mode.md
---

# Vibe Check MCP Commands

Metacognitive questioning system to prevent tunnel vision and cascading errors.

---

## vibe_check - Metacognitive Questioning

**Use BEFORE implementing complex features to validate approach.**

```javascript
// Basic usage
vibe_check({
  goal: "What you're trying to achieve",
  plan: "Your detailed implementation plan"
})

// With uncertainties
vibe_check({
  goal: "Implement real-time collaboration",
  plan: "1. Install Soketi\n2. Configure broadcasting\n3. Create channels",
  uncertainties: [
    "Should conflict resolution be backend or frontend?",
    "How to handle connection drops?"
  ],
  taskContext: "PineCMS Laravel 12 + Vue 3"
})
```

**Returns:** Probing questions that reveal hidden assumptions, pitfalls, and overlooked edge cases.

---

## vibe_learn - Pattern Learning

**Use AFTER mistakes/successes to prevent recurring issues.**

```javascript
// Log a mistake
vibe_learn({
  mistake: "Forgot to eager load relationships, caused N+1 queries",
  category: "Premature Implementation",
  solution: "Added ->with('posts') to query",
  type: "mistake"
})

// Log a preference
vibe_learn({
  mistake: "User prefers Eloquent query scopes over Repository pattern",
  category: "Preference",
  type: "preference"
})

// Log a success
vibe_learn({
  mistake: "Event-driven approach for post-save actions worked perfectly",
  category: "Success",
  solution: "Used Laravel Events instead of Observers",
  type: "success"
})
```

**Categories:**
- Complex Solution Bias
- Feature Creep
- Premature Implementation
- Misalignment
- Overtooling
- Preference
- Success
- Other

---

## Constitution Management

**Set session-specific rules that Claude must follow.**

```javascript
// Add a rule
update_constitution({
  sessionId: "feature-auth-2025-10-31",
  rule: "Always write tests first (TDD)"
})

// Replace all rules
reset_constitution({
  sessionId: "feature-auth-2025-10-31",
  rules: [
    "Always write tests first",
    "Never use Repository pattern",
    "Prefer Eloquent query scopes"
  ]
})

// Check current rules
check_constitution({
  sessionId: "feature-auth-2025-10-31"
})
```

---

## When to Use

### ✅ Use vibe_check for:
- Complex features (3+ files or >1 hour work)
- Before major refactoring
- When uncertain about approach
- To verify alignment with user intent

### ✅ Use vibe_learn for:
- After fixing mistakes
- After discovering better approaches
- When user corrects misunderstanding
- To log project preferences

### ✅ Use constitution for:
- Enforcing TDD or other methodologies
- Project-specific constraints
- Session-specific rules

### ❌ Don't use for:
- Trivial changes (typos, formatting)
- Well-understood routine tasks
- After already implementing (too late!)

---

## Integration Examples

### Example 1: Pre-Implementation Check

```
User: "Add multi-tenant support to the app"

Claude: Let me validate the approach first...

[Uses vibe_check with plan]

Vibe Check questions:
- "How will existing data get tenant_id?"
- "What about performance impact on all queries?"
- "How will you test tenant isolation?"

Claude: Good points! Let me update the plan...
```

### Example 2: Learning from Mistakes

```
User: "Actually, let's use Eloquent scopes instead."

Claude: [Refactors to Eloquent scopes]

[Uses vibe_learn to log preference]

Claude: Noted! I'll remember this preference.
```

---

## Quick Reference

| Tool | Purpose | When |
|------|---------|------|
| `vibe_check` | Validate approach | BEFORE coding |
| `vibe_learn` | Log patterns | AFTER mistakes/successes |
| `update_constitution` | Add session rule | Start of feature |
| `check_constitution` | View rules | Anytime |

---

**Documentation:** https://github.com/PV-Bhat/vibe-check-mcp-server
