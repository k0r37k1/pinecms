# AI Code Review - Quick Start Guide

**Setup Zeit:** ~5 Minuten | **Testing:** 1 Test-PR

---

## ✅ Setup Checklist

### 1. Qodo Merge (Already Done! ✅)

- [x] Workflow erstellt: `.github/workflows/qodo-review.yml`
- [x] Config erstellt: `.qodo.toml`
- [x] Läuft automatisch bei jedem PR

**Test it:** Erstelle einen PR → Qodo kommentiert automatisch

---

### 2. CodeRabbit (5 Min Setup)

#### Step 1: Install GitHub App

```bash
open https://app.coderabbit.ai/login
```

#### Step 2: Login & Authorize

1. Click "Login with GitHub"
2. Authorize CodeRabbit

#### Step 3: Select Repository

1. Choose your organization/account
2. Select "pinecms" repository
3. Click "Install & Authorize"

#### Step 4: Verify Installation

- Check `.coderabbit.yaml` exists (✅ already created)
- CodeRabbit will comment on your next PR

**Done!** 🎉

---

## 🧪 Test Both Tools (Create Test PR)

### Create Test PR

```bash
# 1. Create test branch
git checkout -b test/ai-code-review

# 2. Make a deliberate "bad" change (to trigger reviews)
cat > app/Http/Controllers/TestController.php << 'EOF'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    // Missing return type
    public function test(Request $request)
    {
        // Direct env() usage (bad!)
        $apiKey = env('API_KEY');

        // No validation (bad!)
        $data = $request->all();

        // Inline query (bad!)
        $users = \DB::table('users')->get();

        return response()->json($data);
    }
}
EOF

# 3. Commit & push
git add app/Http/Controllers/TestController.php
git commit -m "test: trigger AI code review"
git push origin test/ai-code-review

# 4. Create PR
gh pr create --title "Test: AI Code Review" --body "Testing Qodo Merge + CodeRabbit"
```

---

## 📝 Expected Results

### Qodo Merge Will Comment

```
✨ PR Review Summary

Issues Found: 4

🔴 Critical:
- Direct env() usage on line 11
  Suggestion: Use config() instead

⚠️ High:
- Missing return type hint on line 9
  Suggestion: public function test(Request $request): JsonResponse

💡 Medium:
- No input validation on line 14
  Suggestion: Create TestRequest FormRequest

📊 Code Quality Score: 6/10

[Generate Tests] [Improve Code] [Ask Question]
```

---

### CodeRabbit Will Comment

```
🤖 CodeRabbit Review

Security Issues: 2
Best Practice Violations: 3

---

app/Http/Controllers/TestController.php

Line 11: ❌ Critical
env('API_KEY') should not be used directly.
Use config('app.api_key') instead.

Line 14: ⚠️ High
Mass assignment vulnerability with $request->all().
Create a FormRequest for validation.

Line 17: ⚠️ Medium
Using DB::table() directly. Use Eloquent models.

Suggestions: 3/3 can be auto-applied
[Apply All Suggestions]
```

---

## 🎯 Commands to Try

### In the Test PR

```bash
# Qodo Commands
@qodo-merge ask "What's wrong with this code?"
@qodo-merge test
@qodo-merge improve

# CodeRabbit Commands
@coderabbitai explain line 11
@coderabbitai ask "Is this secure?"
@coderabbitai help
```

---

## 🧹 Cleanup (After Testing)

```bash
# Close and delete test PR
gh pr close test/ai-code-review --delete-branch

# Remove test file
git checkout main
git branch -D test/ai-code-review
rm app/Http/Controllers/TestController.php
```

---

## 🚀 Daily Usage

### Every PR You Create

1. **Push code** → Both tools review automatically (~5 min)
2. **Read reviews** → Check comments from both tools
3. **Fix issues** → Address critical/high priority items
4. **Ask questions** → Use `@qodo-merge` or `@coderabbitai`
5. **Merge** → Once reviews are satisfied

### Pro Tips

- **Read both reviews** - They complement each other
- **Fix batches** - Don't fix one issue at a time
- **Learn patterns** - Notice recurring suggestions
- **Ask why** - Both tools explain their suggestions

---

## 📊 Monitoring

### Check Review Stats

**Qodo Merge:**

```bash
# Check workflow runs
gh run list --workflow=qodo-review.yml

# View specific run
gh run view <run-id>
```

**CodeRabbit:**

```bash
# Open dashboard
open https://app.coderabbit.ai/dashboard
```

---

## ⚙️ Customization

### Adjust Review Strictness

**Make Qodo Less Strict:**

```toml
# Edit .qodo.toml
[pr_reviewer]
num_code_suggestions = 3  # Default: 5
```

**Make CodeRabbit Less Strict:**

```yaml
# Edit .coderabbit.yaml
reviews:
  profile: "chill"  # Default: "assertive"
```

---

## 🆘 Troubleshooting

### Qodo Not Working?

```bash
# Check workflow status
gh run list --workflow=qodo-review.yml --status=failure

# Re-trigger manually in PR
@qodo-merge review
```

### CodeRabbit Not Working?

```bash
# Check GitHub App installation
open https://github.com/settings/installations

# Verify .coderabbit.yaml syntax
yamllint .coderabbit.yaml

# Re-trigger in PR
@coderabbitai review
```

---

## 📚 Next Steps

✅ **Setup Complete!**
✅ **Test PR Created**
⏳ **Wait for Reviews** (~5 min)
⏳ **Read & Learn from Feedback**
⏳ **Integrate into Daily Workflow**

---

**Ready? Create your test PR now!** 🚀

```bash
git checkout -b test/ai-code-review
# ... follow steps above
```
