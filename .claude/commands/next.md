---
allowed-tools: all
description: Execute production-quality implementation with Research → Plan → Implement workflow
---

# 🚨 MANDATORY WORKFLOW - NO SHORTCUTS! 🚨

You are tasked with implementing: **$ARGUMENTS**

---

## CRITICAL SEQUENCE (NO SKIPPING!)

### 1️⃣ 🔍 RESEARCH FIRST

**YOU MUST SAY:** "Let me research the codebase and create a plan before implementing."

**What to research:**

- Existing patterns in codebase
- Related code and dependencies
- Architecture decisions
- Test coverage requirements
- Security implications

**For complex tasks say:**
"Let me ultrathink about this architecture before proposing a solution."

### 2️⃣ 📋 PLAN

Present a detailed plan:

- Overall architecture
- Files to create/modify
- Testing strategy
- Migration path (if applicable)
- Rollback strategy

**Get approval before proceeding!**

### 3️⃣ ✅ IMPLEMENT

Execute with validation checkpoints:

- Implement in small increments
- Validate after each major component
- Run linters after EVERY file edit
- Fix issues immediately

---

## USE MULTIPLE AGENTS

For tasks with independent parts:

```
"I'll spawn agents to tackle different aspects:
- Agent 1: Backend service layer
- Agent 2: Frontend components
- Agent 3: Tests
Let me coordinate these in parallel..."
```

---

## Completion Standards (NOT NEGOTIABLE)

### All Checks MUST Pass

- ✅ `composer quality` - ZERO warnings
- ✅ `npm run quality` - ZERO warnings
- ✅ All tests pass (PHP + JS + E2E)
- ✅ Feature fully implemented and working end-to-end
- ✅ No TODOs, placeholders, or "good enough" compromises

### Reality Checkpoints (MANDATORY)

- After EVERY 3 file edits → Run linters
- After each component → Validate it works
- Before saying "done" → Run FULL test suite
- If hooks fail → STOP and fix immediately

---

## Code Evolution Rules (PineCMS)

### Direct Implementation

- This is a feature branch → implement NEW solution directly
- DELETE old code when replacing it
- NO migration functions or compatibility layers
- NO versioned functions (e.g., `processDataV2`)
- When refactoring → replace entirely, everywhere

### Language-Specific Requirements

#### PHP/Laravel

- ❌ NO raw SQL - use Eloquent/Query Builder
- ❌ NO direct `$_GET`/`$_POST` - use Request validation
- ✅ Type hints on ALL methods
- ✅ Follow Laravel conventions
- ✅ Use Events (NOT Hooks!)
- ✅ Keep controllers thin → delegate to Services
- ✅ Self-documenting variable names (NO inline comments)
- ✅ Enum classes with methods (NO constants for UI)

#### Vue 3 + Inertia

- ✅ Composition API ONLY (NO Options API)
- ✅ TypeScript for ALL components
- ✅ `<script setup lang="ts">` syntax
- ✅ `useForm` for all forms
- ✅ Proper prop types with `defineProps<Props>()`
- ❌ NO direct API calls - use Inertia router

#### PrimeVue

- ✅ Auto-import components (configured in Vite)
- ✅ Use PrimeVue composables (`useToast`, `useConfirm`)
- ✅ Follow PrimeVue patterns
- ❌ NO custom styling - use PrimeVue theming

---

## PineCMS Specific Patterns

### Flat-File Content Management

```php
// Use spatie/yaml-front-matter
use Spatie\YamlFrontMatter\YamlFrontMatter;

$object = YamlFrontMatter::parseFile($path);
$matter = $object->matter();
$body = $object->body();
```

### Security (MANDATORY)

```php
// Form Request validation
class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:posts'],
        ];
    }
}
```

### Inertia Forms

```vue
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  content: '',
})

const submit = () => {
  form.post(route('posts.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>
```

---

## Documentation Requirements

### Reference Official Docs

- Link to Laravel docs for framework features
- Link to Inertia docs for SPA patterns
- Link to PrimeVue docs for components
- Document WHY decisions were made

Example:

```markdown
// Per Laravel docs on Eloquent relationships:
// https://laravel.com/docs/12.x/eloquent-relationships#one-to-many
public function posts(): HasMany
{
    return $this->hasMany(Post::class);
}
```

---

## Forbidden Patterns (PineCMS)

### ❌ NEVER DO THIS

```php
// Raw SQL
DB::select('SELECT * FROM users');

// Direct globals
$userId = $_GET['id'];

// env() outside config
$apiKey = env('API_KEY');

// Inline comments within methods
// Get the user ID
$userId = $request->user()->id;

// Class constants for UI
class Status {
    const ACTIVE = 'active';
}
```

### ✅ ALWAYS DO THIS

```php
// Eloquent
User::query()->where('active', true)->get();

// Request validation
$validated = $request->validated();

// config() helper
$apiKey = config('services.api.key');

// Self-documenting variable names
$currentUserId = $request->user()->id;

// Enum classes with methods
enum Status: string {
    case Active = 'active';

    public function getLabel(): string {
        return match($this) {
            self::Active => 'Active User',
        };
    }
}
```

---

## Procrastination Patterns (FORBIDDEN)

- ❌ "I'll fix linter warnings at the end" → NO, fix NOW
- ❌ "Let me get it working first" → NO, write clean code from start
- ❌ "This is good enough for now" → NO, do it right
- ❌ "Tests can come later" → NO, test as you go
- ❌ "I'll refactor in a follow-up" → NO, implement final design now

---

## Completion Checklist (ALL must be ✅)

- [ ] Research phase completed
- [ ] Plan reviewed and approved
- [ ] ALL linters pass (ZERO warnings)
- [ ] ALL tests pass
- [ ] Feature works end-to-end
- [ ] Old/replaced code DELETED
- [ ] Documentation complete
- [ ] Security validated
- [ ] Performance acceptable
- [ ] NO TODOs or FIXMEs remain

---

## STARTING NOW

**Beginning research phase to understand the codebase...**

(Remember: Hooks will verify everything. No shortcuts.)
