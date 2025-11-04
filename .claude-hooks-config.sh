#!/usr/bin/env bash
# .claude-hooks-config.sh - PineCMS project-specific Claude hooks configuration

# ============================================================================
# ENABLE/DISABLE HOOKS
# ============================================================================

# Enable hooks globally for this project
export CLAUDE_HOOKS_ENABLED=true

# Enable debug output (set to 1 for troubleshooting)
export CLAUDE_HOOKS_DEBUG=0

# Stop on first issue instead of running all checks
export CLAUDE_HOOKS_FAIL_FAST=false

# ============================================================================
# PINECMS QUALITY COMMANDS
# ============================================================================

# PHP Quality Tools (composer.json scripts)
export CLAUDE_HOOKS_LARAVEL_FORMAT_CMD="composer format"
export CLAUDE_HOOKS_LARAVEL_LINT_CMD="composer analyse"
export CLAUDE_HOOKS_LARAVEL_TEST_CMD="composer test"

# JavaScript Quality Tools (package.json scripts)
export CLAUDE_HOOKS_JS_FORMAT_CMD="npm run format"
export CLAUDE_HOOKS_JS_FORMAT_CHECK_CMD="npm run format:check"
export CLAUDE_HOOKS_JS_LINT_CMD="npm run lint"
export CLAUDE_HOOKS_JS_LINT_FIX_CMD="npm run lint:fix"
export CLAUDE_HOOKS_JS_TYPE_CHECK_CMD="npm run type-check"
export CLAUDE_HOOKS_JS_TEST_CMD="npm run test:coverage"

# ============================================================================
# STACK DETECTION
# ============================================================================

# Force PineCMS stack (auto-detected by default)
# Options: pinecms-inertia-vue-primevue, inertia-vue, laravel
export CLAUDE_HOOKS_LARAVEL_STACK="pinecms-inertia-vue-primevue"

# ============================================================================
# PINECMS SPECIFIC CHECKS
# ============================================================================

# Forbidden patterns
export CLAUDE_HOOKS_LARAVEL_CHECK_RAW_SQL=true
export CLAUDE_HOOKS_LARAVEL_CHECK_DIRECT_GLOBALS=true
export CLAUDE_HOOKS_LARAVEL_CHECK_ENV_USAGE=true

# Vue/Inertia specific
export CLAUDE_HOOKS_CHECK_VUE_COMPOSITION_API=true
export CLAUDE_HOOKS_CHECK_INERTIA_PAGE_STRUCTURE=true

# Content management
export CLAUDE_HOOKS_PINECMS_CONTENT_DIR="content"
export CLAUDE_HOOKS_PINECMS_CHECK_YAML=true

# ============================================================================
# PERFORMANCE TUNING
# ============================================================================

# Limit file checking for very large repos (not needed for PineCMS)
# export CLAUDE_HOOKS_MAX_FILES=500

# ============================================================================
# CUSTOM PATHS
# ============================================================================

# Test paths
export CLAUDE_HOOKS_PHP_TEST_PATHS="tests/Unit,tests/Feature"
export CLAUDE_HOOKS_JS_TEST_PATHS="resources/js/__tests__"

# ============================================================================
# PROJECT-SPECIFIC SETTINGS
# ============================================================================

# Example: Different settings for CI environment
if [[ "${CI:-false}" == "true" ]]; then
    export CLAUDE_HOOKS_FAIL_FAST=true
    export CLAUDE_HOOKS_DEBUG=0
fi
