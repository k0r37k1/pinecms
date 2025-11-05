---
title: Performance & Session Management
version: 1.0
last_updated: 2025-10-31
author: PineCMS Team
status: active
related:
  - .claude/workflows/plan-mode.md
  - .claude/instructions/quality-gates.md
  - https://github.com/Njengah/claude-code-cheat-sheet
---

# Performance & Session Management

**Purpose:** Optimize Claude Code performance, manage tokens efficiently, and avoid common pitfalls.

---

## 🔄 Session Lifecycle Management

### When to `/clear`

**Use `/clear` to reset context after:**

```bash
# After completing a feature
git commit -m "feat: user authentication"
/clear  # Start fresh for next feature

# Between unrelated tasks
"Implement login" → /clear → "Fix navbar bug"

# When context gets polluted (>50 messages)
/clear  # Fresh start improves accuracy

# Before starting complex multi-step work
/clear  # Clean slate for better planning
```

**Benefits:**

- ✅ Faster responses (less context to process)
- ✅ Better accuracy (no conflicting information)
- ✅ Lower token costs
- ✅ Prevents context drift

**Rule of Thumb:** `/clear` after every commit or feature completion.

---

### When to `/compact`

**Use `/compact` for long sessions that you want to continue:**

```bash
# After implementing 3+ related features
"Added login, signup, password reset... getting long"
/compact "keep authentication implementation details, remove debugging attempts"

# Before planning next phase
/compact "summarize current architecture decisions"

# When approaching token limits
/compact "keep only essential context"
```

**Syntax:**

```bash
/compact                          # Auto-summarize entire conversation
/compact "keep X, remove Y"       # With custom instructions
```

**Use Case:**

- Long debugging sessions with multiple attempts
- Multi-day feature development
- Complex refactoring with many iterations

---

### When to `/cos`

**Check session costs and duration:**

```bash
/cos  # Shows: Token usage, API cost, session duration
```

**Use `/cos`:**

- Before `/compact` (decide if needed)
- After large operations (check token usage)
- For budget tracking

---

## 🤖 Model Selection Guide

### Sonnet (Default) - 90% of Tasks

**Use Sonnet for:**

- ✅ Feature implementation (CRUD, API endpoints)
- ✅ Bug fixes and debugging
- ✅ Test writing (PHPUnit, Vitest, Playwright)
- ✅ Code review and refactoring
- ✅ Documentation updates
- ✅ Frontend work (Vue, Inertia, TailwindCSS)

**Why Sonnet:**

- Fast responses (better DX)
- Cost-efficient (10x cheaper than Opus)
- Excellent for Laravel/Vue patterns
- Sufficient for 90% of development tasks

**Current PineCMS Agent Config:**

- All 14 agents use **Sonnet** (PR #6)
- `fullstack-developer` switched from Opus → Sonnet for efficiency

---

### Opus - Complex Architecture Only

**Use Opus for:**

- 🔴 Critical architecture decisions (security, scalability)
- 🔴 Complex algorithm design (performance-critical code)
- 🔴 Multi-system integration planning
- 🔴 Database schema migrations with data transformations

**Why Opus:**

- Deeper reasoning capability
- Better for novel problems (not seen in training)
- More thorough edge case analysis

**How to Switch:**

```bash
claude --model opus  # Start session with Opus

# Or in agent config (.claude/agents/*.md)
model: opus  # Change from sonnet
```

**PineCMS Policy:**

- Default to Sonnet
- Only use Opus when explicitly needed
- Document reason in commit message

---

## ⚡ Token Efficiency Tips

### 1. Plan Before Implementing

```bash
# Bad: Jump straight into coding
"Build user authentication system"
[Claude writes 500 lines without plan, misses requirements]

# Good: Use Plan Mode first
Shift+Tab → "think" → Plan authentication system
[Claude plans, you review, then implement efficiently]
```

**Token Savings:** 30-50% (avoid rewrites)

---

### 2. Use Specialized Agents

```bash
# Bad: Main session does everything
"Review code, write tests, deploy to production"
[Main session gets polluted with mixed concerns]

# Good: Delegate to agents
code-reviewer → Review code
test-engineer → Write tests
deployment-engineer → Production deploy
```

**Token Savings:** 40-60% (agents have focused context)

---

### 3. Break Large Tasks into Chunks

```bash
# Bad: "Implement entire CMS admin panel"
[Claude generates 50 files, misses requirements, needs rework]

# Good: Iterative approach
1. "Create Post CRUD (backend only)" → Test → Commit
2. "Create Post admin UI (Vue)" → Test → Commit
3. "Add media upload to posts" → Test → Commit
```

**Token Savings:** 50-70% (fewer rewrites, better results)

---

### 4. Keep Sessions Focused

```bash
# Bad: Context switching mid-session
"Fix login bug" → "Oh, also refactor navbar" → "And update docs"
[Context gets polluted, responses degrade]

# Good: One task at a time
"Fix login bug" → /clear → "Refactor navbar" → /clear → "Update docs"
```

**Token Savings:** 20-30% (cleaner context)

---

## 🚨 Common Issues & Solutions

### Issue #1: Slow Responses

**Symptoms:**

- Responses take >30 seconds
- Long "thinking" pauses

**Solutions:**

```bash
/clear             # Reset context
/compact           # Summarize if needed
Break into chunks  # Smaller, focused requests
```

---

### Issue #2: Context Pollution

**Symptoms:**

- Claude forgets earlier decisions
- Contradictory suggestions
- Degraded code quality

**Solutions:**

```bash
/clear                    # Fresh start
/compact "keep X only"    # Selective summarization
```

**Prevention:**

- `/clear` after every commit
- Keep sessions under 50 messages
- Use agents for complex multi-step work

---

### Issue #3: Token Overflow

**Symptoms:**

- "Context length exceeded" errors
- Truncated responses

**Solutions:**

```bash
/clear                    # Immediate fix
/compact                  # If need to preserve context
--max-turns 5            # Limit for focused tasks
```

**Prevention:**

- Monitor with `/cos`
- Use Plan Mode for complex tasks
- Delegate to specialized agents

---

### Issue #4: Repetitive Mistakes

**Symptoms:**

- Claude repeats same error
- Forgets project conventions

**Solutions:**

```bash
/clear                              # Reset mental model
Use vibe_learn to log patterns      # Pattern learning
Update .claude/instructions/*.md    # Document conventions
```

**Prevention:**

- Document patterns in `.claude/instructions/`
- Use `vibe_learn` MCP tool for mistake tracking
- Update skill-rules.json for auto-activation

---

## 📊 Session Management Best Practices

### Optimal Session Lifecycle

```
1. Start Fresh
   ↓
2. Plan (if complex) - Shift+Tab
   ↓
3. Implement (focused, 1 task)
   ↓
4. Test & Review
   ↓
5. Commit
   ↓
6. /clear (ALWAYS)
   ↓
7. Repeat
```

---

### Performance Checklist

**Before Starting:**

- [ ] `/clear` from previous session
- [ ] Plan complex tasks (Shift+Tab)
- [ ] Check which agent to use

**During Work:**

- [ ] Keep sessions focused (1 feature/task)
- [ ] Monitor with `/cos` if session gets long
- [ ] `/compact` if >50 messages

**After Completion:**

- [ ] Test implementation
- [ ] Commit changes
- [ ] `/clear` for next task

---

## 🎯 Quick Reference

```bash
# Session Management
/clear          # Reset context (use after every commit)
/compact        # Summarize long sessions
/cos            # Check token usage and cost

# Model Selection
--model sonnet  # Default (90% of tasks)
--model opus    # Complex architecture only

# Token Efficiency
Shift+Tab       # Plan before implementing (saves 30-50% tokens)
Use agents      # Delegate complex tasks (saves 40-60% tokens)
Break chunks    # Iterative approach (saves 50-70% tokens)
```

---

## 📚 Further Reading

- **Plan Mode Guide:** `.claude/workflows/plan-mode.md`
- **Quality Gates:** `.claude/instructions/quality-gates.md`
- **Specialized Agents:** `.claude/agents/README.md`
- **External Reference:** [Claude Code Cheat Sheet](https://github.com/Njengah/claude-code-cheat-sheet)

---

**Last updated:** 2025-10-31
**Version:** 1.0
**Inspiration:** [claude-code-cheat-sheet by Njengah](https://github.com/Njengah/claude-code-cheat-sheet)
