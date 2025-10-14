# TaskMaster AI Configuration

## Overview

PineCMS uses **TaskMaster AI** for intelligent task management, PRD parsing, and development workflow automation. The configuration is optimized for use with **GLM-4.6** via **OpenRouter API**.

## Why GLM-4.6?

GLM-4.6 (by Zhipu AI) is chosen for its exceptional capabilities:

- **200K Context Window**: Handles large codebases and complex PRDs
- **Strong Coding Performance**: Excellent for Laravel/Vue/TypeScript tasks
- **Advanced Reasoning**: Superior in agent-based workflows
- **Cost-Effective**: 15% cheaper than comparable models
- **MIT Licensed**: Open-weight model with transparency
- **Performance**: Comparable to Claude Sonnet 4 and DeepSeek-V3.1

## Installation

### 1. Install TaskMaster AI

```bash
# Global installation (recommended)
npm install -g task-master-ai

# Or use via npx (no installation needed)
npx -y task-master-ai
```

### 2. Configure MCP Server

Copy the MCP configuration template:

```bash
cp .mcp.json.example .mcp.json
```

### 3. Configure OpenRouter API Key

Add your OpenRouter API key to `.env`:

```bash
# Copy from .env.example if needed
cp .env.example .env

# Add your API key
OPENROUTER_API_KEY=sk-or-v1-...
```

Get your API key from: https://openrouter.ai/keys

### 4. Verify Configuration

```bash
# Check Taskmaster configuration
task-master models

# Initialize project (if not done)
task-master init --name="PineCMS" --yes
```

## Configuration Files

### `.mcp.json` (MCP Server Configuration)

Enables TaskMaster AI in Claude Code, Cursor, Windsurf, etc.

**IMPORTANT**: This file is NOT tracked in git (listed in `.gitignore`).
Copy from template: `cp .mcp.json.example .mcp.json`

```json
{
  "mcpServers": {
    "task-master-ai": {
      "command": "npx",
      "args": ["-y", "task-master-ai"],
      "env": {
        "OPENROUTER_API_KEY": "${OPENROUTER_API_KEY}"
      }
    }
  }
}
```

The `${OPENROUTER_API_KEY}` variable is read from your local `.env` file.

### `.taskmaster/config.json` (Model Configuration)

Optimized settings for GLM-4.6:

```json
{
  "models": {
    "main": {
      "provider": "openrouter",
      "modelId": "z-ai/glm-4.6",
      "maxTokens": 16000,
      "temperature": 0.3,
      "baseURL": "https://openrouter.ai/api/v1"
    },
    "research": {
      "provider": "openrouter",
      "modelId": "z-ai/glm-4.6",
      "maxTokens": 12000,
      "temperature": 0.2,
      "baseURL": "https://openrouter.ai/api/v1"
    },
    "fallback": {
      "provider": "openrouter",
      "modelId": "deepseek/deepseek-chat",
      "maxTokens": 8000,
      "temperature": 0.3,
      "baseURL": "https://openrouter.ai/api/v1"
    }
  },
  "global": {
    "logLevel": "info",
    "debug": false,
    "defaultSubtasks": 5,
    "defaultPriority": "medium",
    "defaultTag": "master",
    "projectName": "PineCMS"
  }
}
```

### Configuration Rationale

#### Main Model (GLM-4.6)
- **Use**: Primary task generation, PRD parsing, code generation
- **maxTokens**: 16000 (utilizes 200K context efficiently)
- **temperature**: 0.3 (balanced between precision and creativity)

#### Research Model (GLM-4.6)
- **Use**: Research-backed operations with `--research` flag
- **maxTokens**: 12000 (focused research queries)
- **temperature**: 0.2 (more deterministic for research)

#### Fallback Model (DeepSeek-Chat)
- **Use**: Backup when main/research fails
- **maxTokens**: 8000 (cost-effective fallback)
- **temperature**: 0.3 (consistent with main)

## Directory Structure

```
.taskmaster/
├── config.json          # Model configuration (tracked in git)
├── state.json           # Runtime state (NOT tracked)
├── tasks/
│   └── tasks.json       # Task lists (tracked in git)
├── docs/
│   └── prd.txt          # Product Requirements (user creates)
├── templates/
│   └── example_prd.txt  # PRD template (tracked in git)
└── reports/             # Generated reports (NOT tracked)
    └── .gitkeep
```

## Usage

### Via MCP (Claude Code, Cursor, Windsurf)

In your AI chat interface:

```
Initialize taskmaster-ai in my project
```

Or:

```
Parse the PRD in .taskmaster/docs/prd.txt and create tasks
```

### Via CLI

```bash
# Initialize project
task-master init

# Create a task
task-master add "Implement user authentication"

# Parse PRD
task-master parse-prd

# List tasks
task-master list

# Update task
task-master update 1 --status in-progress

# Complete task
task-master complete 1

# Generate complexity report
task-master analyze-complexity
```

## Writing PRDs

Create your PRD at `.taskmaster/docs/prd.txt` using the template at `.taskmaster/templates/example_prd.txt`.

### PRD Best Practices for PineCMS

1. **Be Specific**: Detail Laravel/Vue components, routes, migrations
2. **Security First**: Include CSP rules, validation, authorization
3. **Testing Requirements**: Specify unit, feature, E2E, mutation tests
4. **Architecture**: Mention Events (NOT Hooks), Services, Repositories
5. **Dependencies**: List PHP packages (Composer) and NPM packages

### Example PRD Structure

```markdown
# Feature: Content Versioning

## Overview
Enable version control for flat-file content with restore capabilities.

## Requirements
- Store content versions in .versions/ subdirectory
- Track author, timestamp, commit message
- Provide restore UI in admin panel

## Technical Specs
### Backend (Laravel)
- Service: ContentVersionService
- Repository: ContentVersionRepository
- Events: ContentVersionCreated, ContentVersionRestored

### Frontend (Vue/Inertia)
- Component: ContentVersionHistory.vue
- Page: ContentVersionsPage.vue

### Tests
- Unit: ContentVersionServiceTest
- Feature: ContentVersionControllerTest
- E2E: content-versioning.spec.ts
```

## Alternative Models

If you prefer different models, edit `.taskmaster/config.json`:

### Option 1: Claude Sonnet (via Anthropic)

```json
{
  "models": {
    "main": {
      "provider": "anthropic",
      "modelId": "claude-sonnet-4-20250514",
      "maxTokens": 16000,
      "temperature": 0.3
    }
  }
}
```

Requires: `ANTHROPIC_API_KEY` in `.env`

### Option 2: DeepSeek-V3 (via OpenRouter)

```json
{
  "models": {
    "main": {
      "provider": "openrouter",
      "modelId": "deepseek/deepseek-chat",
      "maxTokens": 16000,
      "temperature": 0.3,
      "baseURL": "https://openrouter.ai/api/v1"
    }
  }
}
```

### Option 3: Mixed Strategy

```json
{
  "models": {
    "main": "z-ai/glm-4.6",           // GLM for coding
    "research": "perplexity/sonar-pro", // Perplexity for research
    "fallback": "deepseek/deepseek-chat" // DeepSeek for fallback
  }
}
```

Requires: `OPENROUTER_API_KEY`, `PERPLEXITY_API_KEY` in `.env`

## Troubleshooting

### "AI service call failed"

**Cause**: Missing or invalid API key

**Solution**:
1. Check `.env` has `OPENROUTER_API_KEY`
2. Verify key is valid at https://openrouter.ai/keys
3. Ensure `.mcp.json` references the env variable correctly

### "Model not found"

**Cause**: Incorrect model ID

**Solution**:
1. Verify model ID: `z-ai/glm-4.6` (case-sensitive)
2. Check OpenRouter model availability: https://openrouter.ai/models
3. Update `.taskmaster/config.json` with correct model ID

### "Context length exceeded"

**Cause**: maxTokens too high for model

**Solution**:
1. Reduce `maxTokens` in `.taskmaster/config.json`
2. GLM-4.6 max: ~200K tokens (use 16K for safety)
3. DeepSeek max: ~64K tokens (use 8K for safety)

### MCP Server not working

**Cause**: MCP server not enabled or misconfigured

**Solution**:
1. Restart your IDE (Claude Code, Cursor, etc.)
2. Verify `.mcp.json` syntax is valid JSON
3. Check `npx -y task-master-ai` runs without errors
4. Ensure API key is in `.env` or `.mcp.json` env block

## Cost Optimization

### Token Usage Strategies

1. **Lower maxTokens**: Reduce from 16000 to 8000 for simple tasks
2. **Use Fallback**: DeepSeek is cheaper for routine operations
3. **Adjust Temperature**: Lower temperature (0.1-0.2) for deterministic tasks
4. **Cache PRDs**: Parse PRD once, reuse tasks

### Model Cost Comparison (OpenRouter)

| Model | Input (per 1M tokens) | Output (per 1M tokens) |
|-------|----------------------|------------------------|
| GLM-4.6 | $0.15 | $0.60 |
| DeepSeek-Chat | $0.14 | $0.28 |
| Claude Sonnet 4 | $3.00 | $15.00 |

**Savings**: GLM-4.6 is **20x cheaper** than Claude Sonnet 4

## Integration with PineCMS Workflow

### 1. Planning Phase
```bash
# Create PRD
vim .taskmaster/docs/feature-name.txt

# Parse PRD to generate tasks
task-master parse-prd
```

### 2. Development Phase
```bash
# List tasks
task-master list

# Start working on task
task-master update 1 --status in-progress

# Complete task
task-master complete 1
```

### 3. Code Review Phase
```bash
# Generate complexity report
task-master analyze-complexity

# Review task dependencies
task-master list --show-dependencies
```

### 4. Testing Phase
```bash
# Tag tasks by test type
task-master add-tag testing

# Track test coverage tasks
task-master list --tag testing
```

## Resources

- **TaskMaster AI Docs**: https://docs.task-master.dev/
- **OpenRouter Docs**: https://openrouter.ai/docs
- **GLM-4.6 Announcement**: https://z.ai/blog/glm-4.6
- **PineCMS Development Workflow**: `.claude/CLAUDE.md`

## Support

For issues with:
- **TaskMaster AI**: https://github.com/eyaltoledano/claude-task-master/issues
- **OpenRouter**: https://openrouter.ai/docs/support
- **PineCMS Workflow**: Open issue in PineCMS repo
