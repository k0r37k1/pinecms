# Branch Protection Configuration

**Project:** PineCMS (Open Source)
**Strategy:** Feature Branches + Pull Requests + Automated CI/CD
**Solo Developer:** Yes (with professional workflow)

---

## Why Branch Protection for Open Source?

✅ **For Contributors:**
- Clear feature development history
- Professional contribution workflow
- Automated quality checks visible to all
- Easy to review and understand changes

✅ **For You (Solo Maintainer):**
- Protection from accidental broken commits
- CI/CD runs automatically before merge
- Clean, reviewable git history
- Easy rollback if issues arise
- Good habits for when team grows

---

## Recommended Configuration

### Branch: `main`

```text
☑ Require a pull request before merging
  ☐ Require approvals: 0 (not needed for solo maintainer)
  ☑ Dismiss stale pull request approvals when new commits are pushed
  ☐ Require review from Code Owners (optional for future)

☑ Require status checks to pass before merging
  ☑ Require branches to be up to date before merging
  Status checks that must pass:
    - quality-check (runs composer quality && npm run quality)
    - Any other CI workflows you add

☐ Require conversation resolution before merging (optional)

☐ Require signed commits (optional, adds security)

☐ Require linear history (optional, prevents merge commits)

Rules applied to everyone including administrators:
  ☐ Allow force pushes (keep disabled)
  ☐ Allow deletions (keep disabled)

☑ Do not allow bypassing the above settings ⚠️ CRITICAL
  (This makes rules apply to YOU as repository owner!)
  (Note: This replaced "Include administrators" in 2023)
```

---

## Setup Instructions

### Option 1: Web Interface (Recommended)

1. Navigate to: https://github.com/k0r37k1/pinecms/settings/branches
2. Click: "Add branch protection rule"
3. Branch name pattern: `main`
4. Configure settings as shown above
5. Click: "Create" at the bottom

### Option 2: GitHub CLI (Advanced)

**Note:** Currently not available via simple CLI command due to API complexity.
Use web interface for initial setup.

---

## Workflow After Protection Enabled

### For Code Changes

```bash
# 1. Create feature branch
git checkout -b feature/new-feature

# 2. Make changes and commit
git add .
git commit -m "feat: add new feature"

# 3. Run quality checks locally (optional but recommended)
composer quality && npm run quality

# 4. Push branch
git push -u origin feature/new-feature

# 5. Create Pull Request
gh pr create \
  --title "feat: Add new feature" \
  --body "Description of changes"

# 6. Wait for CI/CD to pass (automated)

# 7. Merge PR (via GitHub UI or CLI)
gh pr merge --squash  # or --merge, --rebase
```

### For Documentation Changes (Exception)

Documentation updates can still be pushed directly to `main`:

```bash
# Direct push allowed for:
- docs/**/*.md
- README.md
- Typo fixes in comments
- .gitignore updates

git add docs/
git commit -m "docs: update installation guide"
git push origin main
```

---

## Testing the Protection

After enabling, test that direct pushes are blocked:

```bash
# This should FAIL with branch protection error:
git checkout main
echo "test" >> test.txt
git add test.txt
git commit -m "test: direct push"
git push origin main

# Expected error:
# remote: error: GH006: Protected branch update failed
```

If you see this error, protection is working correctly! ✅

---

## CI/CD Integration

Your GitHub Actions workflows should be configured to:

1. **Run on Pull Requests:**
   ```yaml
   on:
     pull_request:
       branches: [main]
   ```

2. **Run Quality Checks:**
   - PHP: Laravel Pint, PHPStan, PHPUnit
   - JavaScript: ESLint, Prettier, TypeScript, Vitest

3. **Report Status:**
   - Must complete successfully before PR can be merged
   - Failed checks block the merge

---

## Future Contributors

When the project grows and accepts external contributions:

1. **Enable:** "Require review from Code Owners"
2. **Create:** `.github/CODEOWNERS` file
3. **Update:** "Require approvals" to 1 or more
4. **Consider:** Requiring signed commits

---

## Status

- [x] Documentation created
- [ ] Branch protection enabled on GitHub
- [ ] Tested with dummy commit
- [ ] Verified CI/CD integration

---

**Last Updated:** 2025-10-28
**Configured By:** Solo maintainer (k0r37k1)
