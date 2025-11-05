# Test-Driven Development (TDD) Workflow

## Why TDD with AI?

TDD becomes **even more powerful** with Claude Code:

- Prevents AI from creating mock implementations
- Ensures functionality is verifiable
- Catches issues earlier in development
- Creates living documentation

## TDD Cycle: RED → GREEN → REFACTOR

### 1. RED - Write Failing Test

**Be explicit about doing TDD:**

```
"I'm doing test-driven development.
Write a failing test for [feature],
but DON'T create mock implementations."
```

**Example:**

```php
// tests/Feature/PostServiceTest.php
public function test_can_create_post_with_valid_data(): void
{
    $data = [
        'title' => 'Test Post',
        'content' => 'Test content',
        'author_id' => User::factory()->create()->id,
    ];

    $post = $this->postService->create($data);

    $this->assertInstanceOf(Post::class, $post);
    $this->assertEquals('Test Post', $post->title);
    $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
}
```

**Run test - Should FAIL:**

```bash
php artisan test --filter=test_can_create_post_with_valid_data
```

### 2. GREEN - Minimal Implementation

Write **only enough code** to make test pass:

```php
// app/Services/PostService.php
public function create(array $data): Post
{
    return Post::create($data);
}
```

**Run test - Should PASS:**

```bash
php artisan test --filter=test_can_create_post_with_valid_data
```

### 3. REFACTOR - Optimize with Safety Net

Now improve code with **confidence** (tests protect you):

```php
// Refactored with event dispatching
public function create(array $data): Post
{
    $post = Post::create($data);

    event(new PostCreated($post));

    Cache::tags(['posts'])->flush();

    return $post;
}
```

**Run test again - Should still PASS:**

```bash
php artisan test --filter=test_can_create_post_with_valid_data
```

## TDD Best Practices with Claude

### 1. Start with Expected Input/Output

**Good Prompt:**

```
Create a test for UserService::register() that:
- Input: ['name' => 'John', 'email' => 'john@example.com', 'password' => 'secret']
- Output: User instance with hashed password
- Side effects: Sends welcome email, creates default settings

Write the test first, then implement.
```

### 2. Test All Paths

```php
// Happy path
public function test_can_register_user_with_valid_data(): void { }

// Failure path
public function test_cannot_register_user_with_duplicate_email(): void { }

// Edge case
public function test_trims_whitespace_from_email(): void { }
```

### 3. Be Explicit About TDD

❌ Bad: "Implement user registration"
✅ Good: "I'm doing TDD. Write failing test for user registration, then minimal implementation to pass."

### 4. Use Factories, Not Manual Setup

```php
// ❌ Bad
$user = new User();
$user->name = 'Test';
$user->email = 'test@example.com';
$user->save();

// ✅ Good
$user = User::factory()->create();

// ✅ Better - Custom state
$admin = User::factory()->admin()->create();
```

## When to Use TDD

✅ **Use TDD for:**

- New features with clear requirements
- Bug fixes (write test that reproduces bug first)
- Refactoring existing code (tests = safety net)
- Business logic (services, repositories)
- API endpoints

❌ **Skip TDD for:**

- UI/styling changes
- Simple CRUD without business logic
- Prototyping/exploration
- Emergency hotfixes (write tests after)

## TDD with Frontend (Vitest)

### Vue Component TDD

```typescript
// 1. RED - Write failing test
describe('PostForm', () => {
    it('should emit submit event with form data', async () => {
        const wrapper = mount(PostForm)

        await wrapper.find('input[name="title"]').setValue('Test Post')
        await wrapper.find('textarea[name="content"]').setValue('Content')
        await wrapper.find('form').trigger('submit')

        expect(wrapper.emitted()).toHaveProperty('submit')
        expect(wrapper.emitted('submit')[0]).toEqual([{
            title: 'Test Post',
            content: 'Content'
        }])
    })
})

// 2. GREEN - Implement component
// 3. REFACTOR - Optimize
```

## TDD Workflow Summary

```
1. Describe feature/bug to Claude
   ↓
2. Request: "I'm doing TDD, write failing test first"
   ↓
3. Run test - Verify it FAILS
   ↓
4. Implement minimal code to pass
   ↓
5. Run test - Verify it PASSES
   ↓
6. Refactor with confidence
   ↓
7. Run test again - Still PASSES
   ↓
8. Commit with passing tests
```

## Common TDD Mistakes

### Mistake #1: Not Running Tests

❌ Write test, implement, commit
✅ Write test, RUN test (fails), implement, RUN test (passes), commit

### Mistake #2: Testing Implementation

❌ `expect($service->method())->toHaveBeenCalled()`
✅ `expect($result)->toBe($expected)`

### Mistake #3: Over-Mocking

❌ Mock everything
✅ Only mock external dependencies (APIs, filesystems)

### Mistake #4: Not Being Explicit

❌ "Implement feature X"
✅ "I'm doing TDD. First write a failing test for X, don't create mock implementation."
