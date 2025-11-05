# Hooks Workflow Guide

## 🎯 How Hooks Work in PineCMS

### Automatic Trigger Flow

```
1. Claude Edits/Writes File
   ↓
2. PostToolUse Hook Triggers (settings.local.json)
   ↓
3. PHP Hook Executes (.claude/hooks/post-tool-use.php)
   - Tracks edited file path
   - Writes to .claude/hooks/.edited-files
   ↓
4. Bash Hook Executes (.claude/hooks/pinecms-lint.sh)
   - Reads .claude/hooks/.edited-files
   - Determines what checks to run
   - Runs composer/npm quality commands
   ↓
5. Hook Returns
   - Exit 0 → Success, continue
   - Exit 2 → Errors found, BLOCK and show fixes needed
```

## 🔧 Integration Points

### 1. PHP Hooks (Existing)

- `user-prompt-submit.php` → Skills auto-activation
- `post-tool-use.php` → File tracking
- `stop.php` → Session end checks

### 2. Bash Hooks (NEW - from richardhowes)

- `pinecms-lint.sh` → Quality gates
- `common-helpers.sh` → Utilities

### 3. Commands (NEW)

- `/check` → Comprehensive quality verification
- `/next` → Research → Plan → Implement workflow

## 📋 Workflow Examples

### Example 1: Edit PHP File

```bash
# User: Edit app/Services/ContentService.php

PostToolUse Hook:
  1. PHP hook tracks: "app/Services/ContentService.php"
  2. Bash hook runs:
     ✓ Detects PHP file
     ✓ Runs composer format
     ✓ Runs composer analyse
     ✓ Checks for raw SQL
     ✓ Checks for env() usage

# If issues found:
Exit 2 → Claude BLOCKS
Shows: "❌ PHPStan failed - Fix X issues"

# If clean:
Exit 0 → Claude continues
```

### Example 2: Edit Vue Component

```bash
# User: Edit resources/js/Pages/Posts/Index.vue

PostToolUse Hook:
  1. PHP hook tracks: "resources/js/Pages/Posts/Index.vue"
  2. Bash hook runs:
     ✓ Detects Vue file
     ✓ Runs npm run format:check
     ✓ Runs npm run lint
     ✓ Runs npm run type-check
     ✓ Checks for Options API

# If issues found:
Exit 2 → Claude BLOCKS
Shows: "❌ TypeScript errors - Fix X issues"

# If clean:
Exit 0 → Claude continues
```

### Example 3: Using /check Command

```bash
# User: /check

Command executes:
  1. Reads .claude/commands/check.md
  2. Claude runs ALL quality checks:
     - composer quality
     - npm run quality
     - PineCMS specific validation
  3. Claude spawns agents to FIX issues in parallel
  4. Re-runs checks until ✅ GREEN
  5. Reports: "All checks passed!"
```

### Example 4: Using /next Command

```bash
# User: /next "Implement content versioning"

Command executes:
  1. Reads .claude/commands/next.md
  2. Claude MUST research first
  3. Creates detailed plan
  4. Waits for approval
  5. Implements with hooks validating each step
  6. Final /check before declaring complete
```

## 🎯 Quality Gates in Action

### Scenario: Claude Adds Raw SQL

```php
// Claude writes this code:
DB::select('SELECT * FROM posts WHERE active = 1');

// PostToolUse Hook runs:
✗ Bash hook detects raw SQL
✗ Exit 2 (BLOCK)

// Claude sees:
"❌ Raw SQL detected - use Eloquent or Query Builder instead"

// Claude MUST fix:
Post::query()->where('active', true)->get();

// Hook runs again:
✓ No raw SQL found
✓ PHPStan passes
✓ Exit 0 (CONTINUE)
```

### Scenario: Claude Uses Options API

```vue
<!-- Claude writes this code: -->
<script>
export default {
  data() {
    return { count: 0 }
  }
}
</script>

// PostToolUse Hook runs:
✗ Bash hook detects Options API
✗ Exit 2 (BLOCK)

// Claude sees:
"❌ Vue Options API detected - use Composition API with <script setup>"

// Claude MUST fix:
<script setup lang="ts">
import { ref } from 'vue'
const count = ref(0)
</script>

// Hook runs again:
✓ Composition API used
✓ TypeScript check passes
✓ Exit 0 (CONTINUE)
```

## 🔍 Debugging Hooks

### Test Hooks Manually

```bash
# Test bash hook directly
bash .claude/hooks/pinecms-lint.sh

# With debug output
CLAUDE_HOOKS_DEBUG=1 bash .claude/hooks/pinecms-lint.sh

# Create test file to simulate
echo "app/Services/Test.php" > .claude/hooks/.edited-files
bash .claude/hooks/pinecms-lint.sh

# Clean up
rm .claude/hooks/.edited-files
```

### Check Hook Configuration

```bash
# Source config
source .claude-hooks-config.sh

# Verify variables
echo "Enabled: $CLAUDE_HOOKS_ENABLED"
echo "Stack: $CLAUDE_HOOKS_LARAVEL_STACK"
echo "Check Raw SQL: $CLAUDE_HOOKS_CHECK_RAW_SQL"
```

### Disable Hooks Temporarily

```bash
# In .claude-hooks-config.sh
export CLAUDE_HOOKS_ENABLED=false

# Or environment variable
export CLAUDE_HOOKS_ENABLED=false
```

## 🚀 Performance Tips

### Optimize Hook Execution

1. **Use .claude-hooks-ignore** - Skip unnecessary files
2. **Enable fail-fast** - Stop at first error (faster feedback)
3. **Limit file checks** - For large repos

```bash
# In .claude-hooks-config.sh
export CLAUDE_HOOKS_FAIL_FAST=true
export CLAUDE_HOOKS_MAX_FILES=100
```

### Parallel Quality Checks

Hooks run sequentially, but commands can spawn agents:

```bash
# /check command spawns:
- Agent 1: Fix PHP issues
- Agent 2: Fix JS issues
- Agent 3: Fix tests
# All in parallel!
```

## 📊 Integration Summary

### Before (Manual Process)

```
Claude writes code
  ↓
User notices issue later
  ↓
User asks Claude to fix
  ↓
Multiple rounds of back-and-forth
  ↓
Eventually fixed
```

### After (Automated Hooks)

```
Claude writes code
  ↓
Hook catches issue IMMEDIATELY
  ↓
Claude sees error, fixes it
  ↓
Hook validates fix
  ↓
Continues only when ✅ GREEN
```

**Result:** Zero bugs slip through, immediate feedback, enforced standards.

## 🎓 Best Practices

1. **Trust the hooks** - If blocked, there's a real issue
2. **Fix immediately** - Don't accumulate errors
3. **Use /check before PR** - Ensure everything passes
4. **Use /next for complex features** - Structured workflow
5. **Keep config updated** - Share standards with team

## 🔗 Related Files

- `.claude/hooks/README.md` - Hook system documentation
- `.claude/commands/check.md` - /check command
- `.claude/commands/next.md` - /next command
- `.claude-hooks-config.sh` - Project configuration
- `CLAUDE.md` - Main project guidelines

## 📚 References

- [richardhowes/claude-code-laravel](https://github.com/richardhowes/claude-code-laravel)
- [Claude Code Hooks Docs](https://docs.claude.com/en/docs/claude-code/hooks)
- [beyondcode/claude-hooks-sdk](https://github.com/beyondcode/claude-hooks-sdk)
