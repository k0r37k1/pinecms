#!/usr/bin/env bash
# pinecms-lint.sh - Automated quality checks for PineCMS after file edits

set -euo pipefail

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Source helpers
source "${SCRIPT_DIR}/common-helpers.sh"

# Load configuration
load_project_config

# Check if hooks are enabled
if [[ "${CLAUDE_HOOKS_ENABLED:-true}" == "false" ]]; then
    log_debug "Hooks disabled via config"
    exit 0
fi

# Start timing
START_TIME=$(time_start)

print_header "PineCMS Quality Check - Running after file edit..."

# Detect project stack
STACK=$(detect_pinecms_stack)
log_info "Detected stack: ${STACK}"

# Track if we need to run checks
RUN_PHP_CHECKS=false
RUN_JS_CHECKS=false

# Check what files were modified (from PHP hook tracking)
if [[ -f ".claude/hooks/.edited-files" ]]; then
    while IFS= read -r file; do
        # Skip if file should be ignored
        if should_ignore_file "$file"; then
            log_debug "Ignoring file: $file"
            continue
        fi

        # Determine what checks to run based on file type
        case "$file" in
            *.php)
                RUN_PHP_CHECKS=true
                log_debug "PHP file edited: $file"
                ;;
            *.js|*.ts|*.vue|*.jsx|*.tsx)
                RUN_JS_CHECKS=true
                log_debug "JS/TS/Vue file edited: $file"
                ;;
            *.css|*.scss)
                RUN_JS_CHECKS=true
                log_debug "Style file edited: $file"
                ;;
        esac
    done < ".claude/hooks/.edited-files"
else
    # If no tracking file, run all checks
    RUN_PHP_CHECKS=true
    RUN_JS_CHECKS=true
    log_debug "No edit tracking found, running all checks"
fi

# ============================================================================
# PHP QUALITY CHECKS
# ============================================================================

if [[ "$RUN_PHP_CHECKS" == "true" ]] && is_laravel_project; then
    log_info "Running PHP quality checks..."

    # Check if composer exists
    if ! command_exists composer; then
        log_warning "Composer not found, skipping PHP checks"
    else
        # Run Laravel Pint (formatting)
        if composer show | grep -q "laravel/pint"; then
            log_info "  → Running Laravel Pint..."
            if ! composer format --quiet 2>/dev/null; then
                add_error "Laravel Pint formatting failed - run: composer format"
            fi
        fi

        # Run PHPStan (static analysis)
        if composer show | grep -q "phpstan"; then
            log_info "  → Running PHPStan..."
            if ! composer analyse --quiet 2>&1 | grep -q "No errors"; then
                add_error "PHPStan analysis failed - run: composer analyse"
            fi
        fi
    fi
fi

# ============================================================================
# JAVASCRIPT QUALITY CHECKS
# ============================================================================

if [[ "$RUN_JS_CHECKS" == "true" ]] && is_node_project; then
    log_info "Running JavaScript quality checks..."

    # Check if npm exists
    if ! command_exists npm; then
        log_warning "npm not found, skipping JS checks"
    else
        # Run Prettier (formatting check)
        log_info "  → Running Prettier check..."
        if ! npm run format:check --silent 2>/dev/null; then
            add_error "Prettier formatting issues - run: npm run format"
        fi

        # Run ESLint
        log_info "  → Running ESLint..."
        if ! npm run lint --silent 2>/dev/null; then
            add_error "ESLint issues found - run: npm run lint:fix"
        fi

        # Run TypeScript check
        log_info "  → Running TypeScript check..."
        if ! npm run type-check --silent 2>/dev/null; then
            add_error "TypeScript errors found - run: npm run type-check"
        fi
    fi
fi

# ============================================================================
# PINECMS SPECIFIC CHECKS
# ============================================================================

if [[ "$RUN_PHP_CHECKS" == "true" ]] && is_laravel_project; then
    # Check for forbidden patterns
    log_info "Checking PineCMS specific patterns..."

    # Check for raw SQL usage
    if grep -r "DB::select\|DB::statement" app/ 2>/dev/null | grep -v "^#" | grep -q .; then
        add_error "Raw SQL detected - use Eloquent or Query Builder instead"
    fi

    # Check for direct env() usage outside config
    if grep -r "env(" app/ routes/ 2>/dev/null | grep -v "^#" | grep -q .; then
        add_error "Direct env() usage detected - use config() instead"
    fi

    # Check for Vue Options API in resources/js
    if [[ -d "resources/js" ]]; then
        if grep -r "export default {" resources/js/Pages/ resources/js/Components/ 2>/dev/null | grep -q "data()"; then
            add_error "Vue Options API detected - use Composition API with <script setup> instead"
        fi
    fi
fi

# ============================================================================
# SUMMARY AND EXIT
# ============================================================================

time_end "$START_TIME"

if [[ $CLAUDE_HOOKS_ERROR_COUNT -eq 0 ]]; then
    exit_with_success
else
    exit_with_failure
fi
