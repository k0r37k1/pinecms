# Tool Coordination Strategy

**Last Updated:** 2025-11-04
**Tools:** CodeRabbit (Lite) + Claude Code + GitHub Actions + CodeQL

---

## 🎯 Tool Responsibilities

### CodeRabbit (Automated AI Reviews)

**Plan:** Lite ($12/mo)
**When:** Automatically on every PR

**Lite Plan Features:**

- ✅ Unlimited PR reviews
- ✅ AI-powered code analysis (patterns, best practices)
- ✅ Multi-file context awareness
- ✅ Learns from past reviews (knowledge_base)
- ✅ Enforces project guidelines (CLAUDE.md, Spatie)
- ✅ Real-time Web Query (latest docs)
- ✅ Code Graph Analysis

**Lite Plan Limitations:**

- ❌ No SAST tools integration (PHPStan, ESLint, Semgrep)
- ❌ No docstrings auto-generation
- ❌ No Jira/Linear integration

**Workaround:** GitHub Actions runs PHPStan/ESLint separately

**Focus Areas:**

- Code quality & architecture patterns
- Laravel/Vue best practices
- Logic errors & code smells
- Documentation completeness
- Convention consistency

**Trigger:** Automatic on PR creation/update
**Configuration:** `.coderabbit.yaml`

---

### Claude Code (Interactive Development)

**When:** On-demand via @mention or workflow
**Strengths:**

- ✅ Complex architectural decisions
- ✅ Interactive problem solving
- ✅ Code generation with context
- ✅ Bug investigation & fixes
- ✅ Workflow automation

**Focus Areas:**

- Implementation guidance
- Debugging support
- Feature planning
- Architecture reviews
- CI/CD troubleshooting

**Trigger:** `@claude` in PR/Issue comments
**Workflows:**

- `.github/workflows/claude.yml` (on-demand)
- `.github/workflows/claude-code-review.yml` (automated PR reviews)

---

### GitHub Actions (CI/CD Pipeline)

**When:** On push/PR to main/develop
**Strengths:**

- ✅ Deterministic quality gates
- ✅ Fast feedback loops
- ✅ Parallel execution
- ✅ Artifact generation

**Focus Areas:**

- Tests (PHPUnit, Vitest, Playwright)
- Static analysis (PHPStan Level 8, ESLint)
- Code formatting (Pint, Prettier)
- Security checks (Enlightn, CodeQL)
- Dependency security (Composer audit)
- Architecture (Deptrac)
- Code coverage (Codecov)

**Critical Role with Lite Plan:**
Since CodeRabbit Lite doesn't integrate SAST tools, GitHub Actions is the **primary quality gate** for:

- PHPStan (static analysis)
- ESLint (JS linting)
- Security scanning (Enlightn + CodeQL)

**Trigger:** Automatic on push/PR
**Workflows:**

- `.github/workflows/tests.yml` (Quality + Tests)
- `.github/workflows/codeql.yml` (Security)

---

### CodeQL (Advanced Security Scanning)

**Plan:** Free for Public Repos
**When:** On push/PR + Weekly schedule (Monday 6 AM UTC)

**Capabilities:**

- ✅ JavaScript/TypeScript vulnerability scanning
- ✅ XSS detection (DOM-based, reflected, stored)
- ✅ SQL injection detection
- ✅ Code injection vulnerabilities
- ✅ Path traversal detection
- ✅ SSRF (Server-Side Request Forgery)
- ✅ Prototype pollution (JavaScript)
- ✅ Regular expression DoS

**Language Coverage:**

- ✅ **JavaScript/TypeScript** (Vue, Inertia, TipTap)
- ❌ **PHP** (Not supported - use PHPStan + Enlightn)

**Query Suite:** security-extended (comprehensive)

**Benefits for Public Repos:**

- ✅ GitHub Advanced Security (free)
- ✅ Weekly scheduled scans
- ✅ SARIF results in Security tab
- ✅ Automated vulnerability alerts
- ✅ Copilot Autofix (AI-powered fixes)

**Trigger:**

- Push to main/develop
- Pull requests
- Weekly cron (Monday 6 AM UTC)

**Configuration:** `.github/workflows/codeql.yml`

---

## 🔄 Complementary Workflow

### Step 1: Development Phase

```bash
# Local development with quality checks
composer quality  # PHP formatting, analysis, tests
npm run quality   # JS formatting, linting, type-check, tests
```

**Tools:** Local tooling (Pint, PHPStan, ESLint, Vitest)

---

### Step 2: PR Creation

```bash
# Create PR (triggers all tools)
gh pr create --title "feat: add new feature" --body "Description"
```

**Triggered:**

1. **GitHub Actions (tests.yml)** - Quality + Tests (2-5 min)
2. **GitHub Actions (codeql.yml)** - Security scan (3-5 min)
3. **CodeRabbit** - Automated code review (1-2 min)
4. **Claude Code Review** - Architectural review (optional, 3-5 min)

---

### Step 3: Review Phase

**CodeRabbit Review (Automatic):**

- ✅ Posts inline comments on issues
- ✅ Suggests fixes with code snippets
- ✅ Categorizes issues (security, performance, style)
- ✅ Estimates review effort

**Developer Actions:**

- Review CodeRabbit comments
- Address critical issues immediately
- Use `@coderabbitai` commands for clarification
    - `@coderabbitai help` - Show commands
    - `@coderabbitai review` - Re-review after fixes
    - `@coderabbitai ask <question>` - Specific questions

**Claude Code Review (On-Demand):**

- Use for: Complex architecture questions, design patterns
- Trigger: Comment `@claude please review the architecture`
- Response: Detailed analysis referencing CLAUDE.md guidelines

---

### Step 4: CI/CD Validation

**GitHub Actions Status:**

- ✅ All checks must pass before merge
- ✅ Coverage reports uploaded to Codecov
- ❌ Failed checks block merge (branch protection)

---

### Step 5: Merge

**Requirements:**

- ✅ CodeRabbit review addressed
- ✅ GitHub Actions passed
- ✅ (Optional) Claude review approved

```bash
# Merge when ready
gh pr merge --squash
```

---

## 🎨 Use Case Examples

### Use Case 1: Simple Bug Fix

**Steps:**

1. Fix locally → `composer quality`
2. Create PR → CodeRabbit reviews automatically
3. Address comments → Merge

**Tools Used:** CodeRabbit + GitHub Actions

---

### Use Case 2: New Feature (Complex)

**Steps:**

1. Planning: `@claude` for architecture discussion
2. Implementation: Local development + quality checks
3. PR Creation: All tools triggered
4. Review: CodeRabbit (code quality) + Claude (architecture)
5. Merge after all approvals

**Tools Used:** Claude (planning) + CodeRabbit (review) + GitHub Actions (validation)

---

### Use Case 3: Refactoring (Large)

**Steps:**

1. Planning: Claude for refactoring strategy
2. Implementation in small PRs (easier reviews)
3. Each PR: CodeRabbit reviews patterns
4. Final review: Claude validates overall architecture

**Tools Used:** All tools (phased approach)

---

## ⚙️ Configuration Integration

### CodeRabbit Knows About

```yaml
# .coderabbit.yaml → knowledge_base.code_guidelines
- CLAUDE.md (root) - Laravel Boost Guidelines
- .claude/CLAUDE.md - PineCMS-specific rules
- .claude/spatie-laravel-guidelines.md - Industry standards
```

**Result:** CodeRabbit enforces your project conventions automatically!

### Claude Knows About

```
# Automatic context loading
- CLAUDE.md (auto-read on session start)
- .claude/CLAUDE.md (auto-read on session start)
- Laravel Boost Guidelines (embedded in CLAUDE.md)
```

**Result:** Claude follows same standards as CodeRabbit!

---

## 🚀 Best Practices

### 1. Draft PRs for Early Feedback

```bash
# Create draft PR for early CodeRabbit review
gh pr create --draft --title "WIP: feature"
```

**Benefit:** Get feedback before code is complete

**Configuration:** `auto_review.drafts: true` in `.coderabbit.yaml`

---

### 2. Iterative Reviews

```bash
# After fixing CodeRabbit comments
git add . && git commit -m "fix: address review comments"
git push

# Request re-review
# Comment: @coderabbitai review
```

**Benefit:** Validates fixes immediately

---

### 3. Specific Questions

```bash
# Ask CodeRabbit
# Comment: @coderabbitai is this N+1 query prevention correct?

# Ask Claude
# Comment: @claude how should I structure this service layer?
```

**Benefit:** Get expert opinions on specific issues

---

### 4. Combine Tools Strategically

**CodeRabbit for:** Systematic issues (patterns, style, security)
**Claude for:** Creative solutions (architecture, complex logic)
**GitHub Actions for:** Binary pass/fail (tests, build)

**Benefit:** Each tool does what it's best at

---

## 📊 Tool Comparison

| Aspect         | CodeRabbit (Lite)   | Claude Code     | GitHub Actions   | CodeQL                  |
| -------------- | ------------------- | --------------- | ---------------- | ----------------------- |
| **Trigger**    | Automatic           | On-demand       | Automatic        | Automatic + Weekly      |
| **Speed**      | 1-2 min             | 3-5 min         | 2-5 min          | 3-5 min                 |
| **Depth**      | Patterns & logic    | Deep analysis   | Binary pass/fail | Vulnerability-focused   |
| **Context**    | Multi-file          | Codebase-wide   | File-specific    | Data flow analysis      |
| **Learning**   | Knowledge base      | Session-based   | N/A              | Query evolution         |
| **Languages**  | All (AI)            | All (AI)        | PHP (PHPStan)    | JS/TS only              |
| **SAST Tools** | ❌ (Pro only)       | ✅ Via MCP      | ✅ Native        | ✅ Built-in             |
| **Security**   | General             | Advisory        | Checks           | ⭐ Advanced             |
| **Best For**   | Architecture review | Problem solving | Quality gates    | Vulnerability detection |

**Key Insight:** With CodeQL added, you have **complete security coverage**:

- **PHP Security:** PHPStan (static) + Enlightn (runtime)
- **JavaScript Security:** CodeQL (advanced) + ESLint (style)
- **Architecture Review:** CodeRabbit (patterns) + Claude (deep)

---

## 💡 Lite Plan Strategy

### Maximizing Value with CodeRabbit Lite

**What Lite Does Well:**

1. ✅ **Architecture Review** - Multi-file context, pattern detection
2. ✅ **Convention Enforcement** - Uses knowledge_base + guidelines
3. ✅ **Logic Analysis** - Catches bugs AI can spot (N+1, race conditions)
4. ✅ **Documentation Review** - Completeness, clarity, accuracy
5. ✅ **Learning** - Improves over time from past reviews

**What Lite Doesn't Do (Requires Workaround):**

| Missing Feature     | Workaround      | Tool                      |
| ------------------- | --------------- | ------------------------- |
| PHPStan integration | GitHub Actions  | `composer analyse`        |
| ESLint integration  | GitHub Actions  | `npm run lint`            |
| Semgrep security    | GitHub Actions  | Enlightn Security Checker |
| Auto-docstrings     | Manual + Claude | Ask Claude for docblocks  |

### Recommended Workflow with Lite

**Phase 1: Local Development**

```bash
# Run ALL quality checks locally (compensates for no SAST in CodeRabbit)
composer quality  # Pint + PHPStan + Tests
npm run quality   # ESLint + Prettier + TypeScript + Tests
```

**Phase 2: PR Creation**

```bash
gh pr create --draft --title "feat: feature name"
```

**Phase 3: Review Coordination**

**CodeRabbit (Lite) reviews:**

- ✅ Architecture patterns (SOLID, DRY, separation of concerns)
- ✅ Laravel/Vue conventions (Spatie Guidelines)
- ✅ Logic errors (N+1, missing validations, edge cases)
- ✅ Multi-file consistency

**GitHub Actions validates:**

- ✅ PHPStan Level 8 (type safety, static analysis)
- ✅ ESLint (code style, best practices)
- ✅ Tests (unit, feature, E2E)
- ✅ Security (Enlightn)

**Result:** Complete coverage despite Lite limitations!

---

## 🔧 Maintenance

### Monthly Review

1. **Check CodeRabbit learnings:**
    - Visit: <https://app.coderabbit.ai/repos/k0r37k1/pinecms>
    - Review: Recurring suggestions → Update guidelines

2. **Update GitHub Actions:**
    - Check for dependency updates
    - Review action versions

3. **Update Guidelines:**
    - Sync changes across CLAUDE.md files
    - Update .coderabbit.yaml if patterns change

---

## 📚 Resources

- **CodeRabbit Docs:** <https://docs.coderabbit.ai/>
- **Claude Code Docs:** <https://docs.claude.com/en/docs/claude-code>
- **GitHub Actions Docs:** <https://docs.github.com/actions>

---

**Status:** ✅ Fully Configured
**Last Review:** 2025-11-04
