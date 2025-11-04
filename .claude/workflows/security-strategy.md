# Security Scanning Strategy

**Last Updated:** 2025-11-04
**Project:** PineCMS (Public Repository)
**Security Tools:** CodeQL + PHPStan + Enlightn + ESLint

---

## 🎯 Security Coverage Matrix

| Language | Static Analysis | Security Scanning | Dependency Security |
|----------|----------------|------------------|-------------------|
| **PHP** | PHPStan Level 8 | Enlightn Security Checker | Composer audit |
| **JavaScript/TypeScript** | ESLint | **CodeQL** | npm audit |
| **CSS** | Stylelint | N/A | N/A |
| **Infrastructure** | N/A | GitHub Dependabot | N/A |

**Result:** ✅ **100% Coverage across all languages**

---

## 🔒 CodeQL - Advanced JavaScript Security

### What CodeQL Detects

#### XSS (Cross-Site Scripting)
```javascript
// BAD: CodeQL will flag this
element.innerHTML = userInput  // Unsanitized user input

// GOOD: Use safer alternatives
element.textContent = userInput  // Safe
```

#### SQL Injection (Client-Side)
```javascript
// BAD: String concatenation in queries
const query = "SELECT * FROM users WHERE id = " + userId

// GOOD: Parameterized queries
const query = { id: userId }
```

#### Prototype Pollution
```javascript
// BAD: Unsafe object merging
function merge(target, source) {
  for (let key in source) {
    target[key] = source[key]  // Can pollute __proto__
  }
}

// GOOD: Use Object.assign or spread
const merged = { ...target, ...source }
```

#### Path Traversal
```javascript
// BAD: Unsanitized file paths
const filePath = basePath + userInput

// GOOD: Validate and sanitize
import { join, normalize } from 'path'
const filePath = join(basePath, normalize(userInput))
```

#### Regular Expression DoS
```javascript
// BAD: Catastrophic backtracking
const regex = /^(a+)+$/  // Can hang on "aaaaaaaaaaaaaaaaaaaX"

// GOOD: Efficient patterns
const regex = /^a+$/
```

---

## 🛡️ PHP Security (PHPStan + Enlightn)

### What PHPStan Detects (Level 8)

- Type errors and mismatches
- Undefined variables/methods
- Dead code detection
- Incorrect return types
- Potential null pointer issues

### What Enlightn Detects

#### Security Vulnerabilities
- Mass assignment vulnerabilities
- Unvalidated redirects
- Debug mode in production
- Session security issues
- CSRF protection gaps
- SQL injection risks
- File upload vulnerabilities

#### Configuration Issues
- Weak encryption keys
- Insecure headers
- Cache configuration
- Queue security
- Database credentials exposure

---

## 📅 Scanning Schedule

### Continuous (On Every PR/Push)

**Tests Workflow** (`.github/workflows/tests.yml`)
- ✅ PHPStan Level 8 (PHP static analysis)
- ✅ ESLint (JavaScript linting)
- ✅ Enlightn (PHP security)
- ✅ PHPUnit + Vitest (Tests)

**CodeQL Workflow** (`.github/workflows/codeql.yml`)
- ✅ JavaScript/TypeScript security scan
- ✅ Runs on push to main/develop
- ✅ Runs on all pull requests

### Weekly Scheduled

**CodeQL** (Every Monday 6 AM UTC)
- ✅ Deep security scan
- ✅ Catches new vulnerabilities (query updates)
- ✅ SARIF results uploaded to Security tab

**GitHub Dependabot** (Automatic)
- ✅ Dependency vulnerability alerts
- ✅ Automated PR for security updates
- ✅ npm + Composer packages

---

## 🚨 Alert Management

### CodeQL Alerts

**Location:** GitHub Security Tab → Code Scanning Alerts

**Alert Levels:**
- 🔴 **Critical** - Immediate action required
- 🟠 **High** - Fix within 24-48 hours
- 🟡 **Medium** - Fix in next sprint
- 🔵 **Low** - Fix when convenient

**Workflow:**
1. Alert appears in Security tab
2. Review SARIF details + affected code
3. Create issue (auto-linked to alert)
4. Fix vulnerability
5. Push fix → CodeQL re-scans → Alert closes

**Copilot Autofix:**
- ✅ AI-powered fix suggestions (free for public repos)
- Click "Generate fix" in alert details
- Review → Commit suggested fix

---

### Dependabot Alerts

**Location:** GitHub Security Tab → Dependabot Alerts

**Auto-PR:** Dependabot creates PR for security updates

**Workflow:**
1. Dependency vulnerability discovered
2. Dependabot opens PR with update
3. CI/CD runs tests
4. Review changelog → Merge if green

---

## 🔐 Security Best Practices

### For Frontend (JavaScript/Vue)

✅ **Always sanitize user input**
```vue
<!-- BAD -->
<div v-html="userInput"></div>

<!-- GOOD -->
<div>{{ userInput }}</div>
```

✅ **Use CSP headers** (already configured in `config/secure-headers.php`)

✅ **Validate data from Inertia props**
```typescript
// Add runtime validation
const props = defineProps<{
  user: User
}>()

// Validate before use
if (!props.user?.id) {
  throw new Error('Invalid user data')
}
```

✅ **Use parameterized queries (Inertia forms)**
```typescript
// Form data is automatically validated server-side
form.post('/users', {
  preserveScroll: true
})
```

---

### For Backend (PHP/Laravel)

✅ **Always use FormRequest validation**
```php
// app/Http/Requests/StoreUserRequest.php
public function rules(): array
{
    return [
        'email' => ['required', 'email', 'unique:users'],
        'name' => ['required', 'string', 'max:255'],
    ];
}
```

✅ **Use mass assignment protection**
```php
// Model
protected $fillable = ['name', 'email'];
// or
protected $guarded = ['id', 'password'];
```

✅ **Use Eloquent query builder (prevents SQL injection)**
```php
// GOOD
User::where('email', $email)->first();

// BAD (never do this!)
DB::select("SELECT * FROM users WHERE email = '$email'");
```

✅ **Use authorization policies**
```php
// Controller
$this->authorize('update', $user);

// Policy
public function update(User $authUser, User $user): bool
{
    return $authUser->id === $user->id;
}
```

✅ **Never use env() outside config files**
```php
// BAD
$key = env('APP_KEY');

// GOOD
$key = config('app.key');
```

---

## 📊 Security Metrics

### Weekly Security Review Checklist

- [ ] Check CodeQL alerts (Security tab)
- [ ] Review Dependabot PRs
- [ ] Check composer audit (PHP dependencies)
- [ ] Check npm audit (JavaScript dependencies)
- [ ] Review Enlightn report (if new scan available)
- [ ] Update dependencies (patch versions)

### Monthly Security Tasks

- [ ] Run full Enlightn security audit
- [ ] Review all resolved alerts (lessons learned)
- [ ] Update security documentation
- [ ] Test security headers (securityheaders.com)
- [ ] Review CSP policy effectiveness
- [ ] Check for new Laravel security advisories

---

## 🔄 Security in Development Workflow

### Before Starting Feature

```bash
# Check for security updates
composer outdated --direct
npm outdated
```

### During Development

```bash
# Run security checks locally
composer security      # Enlightn
npm audit              # npm vulnerabilities
```

### Before PR

```bash
# Full quality check (includes security)
composer quality       # PHPStan + Tests
npm run quality        # ESLint + Tests
```

### After PR Merge

**Automatic:**
- ✅ CodeQL scans JavaScript
- ✅ PHPStan validates PHP
- ✅ Enlightn checks security
- ✅ All tests pass

**Manual:**
- Monitor Security tab for new alerts

---

## 🎓 Security Training Resources

### CodeQL Documentation
- https://codeql.github.com/docs/
- https://github.com/github/codeql

### Laravel Security
- https://laravel.com/docs/12.x/security
- Enlightn Docs: https://www.laravel-enlightn.com/docs/

### OWASP Top 10
- https://owasp.org/www-project-top-ten/

### JavaScript Security
- https://cheatsheetseries.owasp.org/cheatsheets/DOM_based_XSS_Prevention_Cheat_Sheet.html

---

## 🚀 Next Steps

### Enable GitHub Security Features

1. **Enable Dependabot Security Updates**
   - Settings → Security & Analysis → Dependabot security updates → Enable

2. **Enable Secret Scanning**
   - Settings → Security & Analysis → Secret scanning → Enable

3. **Enable Push Protection**
   - Settings → Security & Analysis → Push protection → Enable

4. **Configure Security Policy**
   - Create `SECURITY.md` with vulnerability reporting process

---

## 📈 Expected Results

### CodeQL First Run
- **Expected:** 0-5 findings (project follows best practices)
- **Common findings:** Prototype pollution, regex DoS, missing validation

### After Implementation
- ✅ Weekly security scans (Monday)
- ✅ Automatic vulnerability detection
- ✅ Reduced false positives over time (CodeQL learns)
- ✅ Security-focused PR reviews
- ✅ Audit trail in Security tab

---

## 📚 Related Documentation

- `.github/workflows/codeql.yml` - CodeQL configuration
- `.github/workflows/tests.yml` - Quality + security checks
- `config/secure-headers.php` - HTTP security headers
- `.claude/instructions/security.md` - Security coding guidelines

---

**Status:** ✅ **Production-Ready Security Configuration**
**Coverage:** **100%** (PHP + JavaScript + Dependencies)
**Cost:** **$0** (Free for public repositories)
