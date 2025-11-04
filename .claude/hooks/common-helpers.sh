#!/usr/bin/env bash
# common-helpers.sh - Shared utilities for PineCMS Claude Code hooks

# ============================================================================
# COLOR DEFINITIONS
# ============================================================================

export RED='\033[0;31m'
export GREEN='\033[0;32m'
export YELLOW='\033[0;33m'
export BLUE='\033[0;34m'
export CYAN='\033[0;36m'
export NC='\033[0m' # No Color

# ============================================================================
# LOGGING FUNCTIONS
# ============================================================================

log_debug() {
    [[ "${CLAUDE_HOOKS_DEBUG:-0}" == "1" ]] && echo -e "${CYAN}[DEBUG]${NC} $*" >&2
}

log_info() {
    echo -e "${BLUE}[INFO]${NC} $*" >&2
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $*" >&2
}

log_success() {
    echo -e "${GREEN}[OK]${NC} $*" >&2
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $*" >&2
}

# ============================================================================
# PERFORMANCE TIMING
# ============================================================================

time_start() {
    if [[ "${CLAUDE_HOOKS_DEBUG:-0}" == "1" ]]; then
        echo $(($(date +%s%N)/1000000))
    fi
}

time_end() {
    if [[ "${CLAUDE_HOOKS_DEBUG:-0}" == "1" ]]; then
        local start=$1
        local end=$(($(date +%s%N)/1000000))
        local duration=$((end - start))
        log_debug "Execution time: ${duration}ms"
    fi
}

# ============================================================================
# UTILITY FUNCTIONS
# ============================================================================

# Check if a command exists
command_exists() {
    command -v "$1" &> /dev/null
}

# Load project configuration
load_project_config() {
    # Project-level config (highest priority)
    [[ -f ".claude-hooks-config.sh" ]] && source ".claude-hooks-config.sh"

    # Always return success
    return 0
}

# ============================================================================
# ERROR TRACKING
# ============================================================================

declare -a CLAUDE_HOOKS_ERRORS=()
declare -i CLAUDE_HOOKS_ERROR_COUNT=0

add_error() {
    local message="$1"
    CLAUDE_HOOKS_ERROR_COUNT+=1
    CLAUDE_HOOKS_ERRORS+=("${RED}❌${NC} $message")
}

print_error_summary() {
    if [[ $CLAUDE_HOOKS_ERROR_COUNT -gt 0 ]]; then
        echo -e "\n${BLUE}═══ Summary ═══${NC}" >&2
        for item in "${CLAUDE_HOOKS_ERRORS[@]}"; do
            echo -e "$item" >&2
        done

        echo -e "\n${RED}Found $CLAUDE_HOOKS_ERROR_COUNT issue(s) that MUST be fixed!${NC}" >&2
        echo -e "${RED}════════════════════════════════════════════${NC}" >&2
        echo -e "${RED}❌ ALL ISSUES ARE BLOCKING ❌${NC}" >&2
        echo -e "${RED}════════════════════════════════════════════${NC}" >&2
        echo -e "${RED}Fix EVERYTHING above until all checks are ✅ GREEN${NC}" >&2
    else
        log_success "All checks passed! ✅"
    fi
}

# ============================================================================
# STANDARD HEADERS
# ============================================================================

print_header() {
    local title="$1"
    echo "" >&2
    echo -e "${BLUE}🔍 $title${NC}" >&2
    echo "────────────────────────────────────────────" >&2
}

# ============================================================================
# PROJECT DETECTION
# ============================================================================

is_laravel_project() {
    [[ -f "artisan" && -f "composer.json" ]]
}

is_node_project() {
    [[ -f "package.json" ]]
}

detect_pinecms_stack() {
    # Detect PineCMS specific stack
    if ! is_laravel_project; then
        return 1
    fi

    # Check for Inertia + Vue
    if [[ -f "package.json" ]]; then
        if grep -q "@inertiajs/vue3" package.json 2>/dev/null; then
            # Check for PrimeVue
            if grep -q "primevue" package.json 2>/dev/null; then
                echo "pinecms-inertia-vue-primevue"
                return 0
            fi
            echo "inertia-vue"
            return 0
        fi
    fi

    echo "laravel"
    return 0
}

# ============================================================================
# EXIT HANDLERS
# ============================================================================

exit_with_success() {
    log_success "All checks passed! Continue with your task."
    exit 0
}

exit_with_failure() {
    print_error_summary
    echo -e "\n${YELLOW}📋 NEXT STEPS:${NC}" >&2
    echo -e "${YELLOW}  1. Fix all issues listed above${NC}" >&2
    echo -e "${YELLOW}  2. Run the check again to verify${NC}" >&2
    echo -e "${YELLOW}  3. Continue only when all checks pass${NC}" >&2
    exit 2
}

# ============================================================================
# FILE IGNORE PATTERNS
# ============================================================================

should_ignore_file() {
    local file="$1"

    # Standard ignores
    local ignore_patterns=(
        "vendor/"
        "node_modules/"
        "bootstrap/cache/"
        "storage/"
        "public/build/"
        "public/hot"
        ".git/"
        ".idea/"
        "*.min.js"
        "*.min.css"
    )

    # Check against patterns
    for pattern in "${ignore_patterns[@]}"; do
        if [[ "$file" == *"$pattern"* ]]; then
            return 0
        fi
    done

    # Check .claude-hooks-ignore if exists
    if [[ -f ".claude-hooks-ignore" ]]; then
        while IFS= read -r pattern; do
            # Skip comments and empty lines
            [[ -z "$pattern" || "$pattern" == \#* ]] && continue

            if [[ "$file" == *"$pattern"* ]]; then
                return 0
            fi
        done < ".claude-hooks-ignore"
    fi

    return 1
}
