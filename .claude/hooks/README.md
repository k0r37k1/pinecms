# PineCMS Claude Hooks System

Automated quality gates that run after every file edit to ensure code quality, prevent errors, and enforce PineCMS best practices.

## 🎯 Overview

This hooks system combines **PHP hooks** (for tracking) and **Bash hooks** (for quality checks):

- **PHP Hooks** → Track edited files, manage state
- **Bash Hooks** → Run quality checks (composer/npm commands)

## 📁 Structure

```
.claude/hooks/
├── README.md                      # This file
├── common-helpers.sh              # Shared utilities (colors, logging, detection)
├── pinecms-lint.sh                # Main quality check hook (runs after edits)
├── .claude-hooks-ignore           # Files to ignore in hooks
├── pre-commit-check.php           # PHP hook (test enforcement) ⭐ NEW!
├── post-tool-use.php              # PHP hook (tracks edited files)
├── stop.php                       # PHP hook (runs at session end)
└── user-prompt-submit.php         # PHP hook (skills auto-activation)
```

## 🔧 Configuration

### Global Config (Project Root)

**`.claude-hooks-config.sh`** - Project-specific settings:

```bash
# Enable/disable hooks
export CLAUDE_HOOKS_ENABLED=true

# Quality commands
export CLAUDE_HOOKS_LARAVEL_FORMAT_CMD="composer format"
export CLAUDE_HOOKS_LARAVEL_LINT_CMD="composer analyse"

# Stack detection
export CLAUDE_HOOKS_LARAVEL_STACK="pinecms-inertia-vue-primevue"

# Specific checks
export CLAUDE_HOOKS_CHECK_VUE_COMPOSITION_API=true
export CLAUDE_HOOKS_CHECK_RAW_SQL=true
```

### Hook Configuration (settings.local.json)

Hooks run automatically at different lifecycle points:

```json
{
    "hooks": {
        "PreToolUse": [
            {
                "matcher": "Bash",
                "hooks": [{ "type": "command", "command": "php .claude/hooks/pre-commit-check.php" }]
            }
        ],
        "PostToolUse": [
            {
                "matcher": "Edit|Write",
                "hooks": [
                    { "type": "command", "command": "php .claude/hooks/post-tool-use.php" },
                    { "type": "command", "command": "bash .claude/hooks/pinecms-lint.sh" }
                ]
            }
        ]
    }
}
```

## 🚀 How It Works

### Workflow

```
1. User submits prompt
   ↓
2. UserPromptSubmit → Skills auto-activation
   ↓
3. Claude edits/writes files
   ↓
4. PostToolUse → Track files + Run quality checks
   ↓
5. Claude attempts commit
   ↓
6. PreToolUse → Check if tests passed ⭐ NEW!
   ↓
7. If tests failed → BLOCK commit
   ↓
8. If tests passed → Allow commit
   ↓
9. Stop → Final build check report
```

### ⭐ NEW: Test Enforcement (PreToolUse Hook)

**Purpose:** Prevents committing code without passing tests

**How it works:**

1. Intercepts ALL `git commit` commands
2. Checks for test marker file: `/tmp/pinecms-tests-passed`
3. **Blocks commit** if marker doesn't exist (tests not run/passed)
4. **Allows commit** if marker exists (tests passed)

**Create test marker:**

```bash
# Option 1: Quick test + marker
composer test && npm test && touch /tmp/pinecms-tests-passed

# Option 2: Use /build-and-fix (auto-creates marker)
/build-and-fix

# Option 3: Full quality check
composer quality && npm run quality && touch /tmp/pinecms-tests-passed
```

**Example Output (Blocked):**

```
❌ COMMIT BLOCKED: Tests must pass before committing!

Please run one of the following:

  Option 1 (Recommended):
  /build-and-fix           # Fix all errors systematically

  Option 2 (Quick):
  composer test && npm test && touch /tmp/pinecms-tests-passed
```

**Example Output (Allowed):**

```
✅ Tests passed - proceeding with commit
```

**Bypass (if needed):**

```bash
touch /tmp/pinecms-tests-passed
```

**Benefits:**

- ✅ Prevents broken code from being committed
- ✅ Enforces test-driven development workflow
- ✅ Catches errors before they enter git history
- ✅ Encourages quality gates compliance

### What Gets Checked

#### PHP Files

- ✅ Laravel Pint (formatting)
- ✅ PHPStan (static analysis level 8)
- ✅ Deptrac (architecture)
- ✅ No raw SQL
- ✅ No direct `env()` usage

#### JavaScript/TypeScript/Vue Files

- ✅ Prettier (formatting)
- ✅ ESLint (linting)
- ✅ TypeScript (type checking)
- ✅ Vue Composition API only
- ✅ No Options API

#### PineCMS Specific

- ✅ Flat-file YAML integrity
- ✅ Security patterns
- ✅ Inertia best practices
- ✅ PrimeVue patterns

## 🛠️ Manual Testing

### Test Individual Hooks

```bash
# Test bash lint hook directly
bash .claude/hooks/pinecms-lint.sh

# Test with debug mode
CLAUDE_HOOKS_DEBUG=1 bash .claude/hooks/pinecms-lint.sh

# Test PHP quality checks
composer quality

# Test JS quality checks
npm run quality
```

### Test Configuration

```bash
# Source config and check variables
source .claude-hooks-config.sh
echo "Stack: $CLAUDE_HOOKS_LARAVEL_STACK"
echo "Debug: $CLAUDE_HOOKS_DEBUG"
```

## 🎯 Integration with Commands

### `/check` Command

Runs comprehensive quality verification:

- `composer quality` (PHP)
- `npm run quality` (JS)
- PineCMS specific validation
- **Zero tolerance** - all errors MUST be fixed

### `/next` Command

Structured workflow:

1. Research codebase
2. Create plan
3. Implement with hooks validating

## 🔍 Debugging

### Enable Debug Mode

```bash
# Temporary (one session)
export CLAUDE_HOOKS_DEBUG=1

# Permanent (in .claude-hooks-config.sh)
export CLAUDE_HOOKS_DEBUG=1
```

Debug output shows:

- Detected stack
- Files being checked
- Commands being run
- Execution time

### Check Hook Status

```bash
# Check if hooks are running
ls -la .claude/hooks/

# Check permissions
ls -l .claude/hooks/*.sh

# View recent hook output (if logged)
tail -f .claude/hooks/.hook-output 2>/dev/null
```

### Common Issues

**Hook not running:**

```bash
# Check executable permissions
chmod +x .claude/hooks/*.sh
```

**Command not found:**

```bash
# Check if tools are installed
composer --version
npm --version
php --version
```

**False positives:**

```bash
# Add to .claude/hooks/.claude-hooks-ignore
vendor/**
node_modules/**
```

## 📝 Ignore Patterns

Edit `.claude/hooks/.claude-hooks-ignore`:

```
# Custom ignores
legacy-code/**
temp-experiments/**
*.draft.php
```

## 🔄 Updating Hooks

### Update from richardhowes/claude-code-laravel

```bash
# Download latest
curl -o .claude/hooks/common-helpers-new.sh https://raw.githubusercontent.com/richardhowes/claude-code-laravel/main/hooks/common-helpers.sh

# Review changes
diff .claude/hooks/common-helpers.sh .claude/hooks/common-helpers-new.sh

# Apply if good
mv .claude/hooks/common-helpers-new.sh .claude/hooks/common-helpers.sh
chmod +x .claude/hooks/common-helpers.sh
```

## 🎓 Best Practices

1. **Never disable hooks without reason** - They prevent errors
2. **Fix issues immediately** - Don't accumulate technical debt
3. **Add patterns to ignore carefully** - Only ignore generated files
4. **Keep config in version control** - Share standards with team
5. **Update hooks regularly** - Get latest improvements

## 🚨 Troubleshooting

### Hooks blocking unexpectedly

Check what's failing:

```bash
bash .claude/hooks/pinecms-lint.sh
```

### Performance issues

Enable timing:

```bash
CLAUDE_HOOKS_DEBUG=1 bash .claude/hooks/pinecms-lint.sh
```

### Disable temporarily

```bash
# In .claude-hooks-config.sh
export CLAUDE_HOOKS_ENABLED=false
```

## 🔗 Related Documentation

- `CLAUDE.md` - Main project guidelines
- `.claude/commands/check.md` - Comprehensive quality check
- `.claude/commands/next.md` - Workflow for implementation
- `.claude/workflows/hooks.md` - Advanced hook workflows

## 📚 References

- [richardhowes/claude-code-laravel](https://github.com/richardhowes/claude-code-laravel) - Original inspiration
- [Claude Code Hooks](https://docs.claude.com/en/docs/claude-code/hooks) - Official documentation
- [beyondcode/claude-hooks-sdk](https://github.com/beyondcode/claude-hooks-sdk) - PHP hooks library
