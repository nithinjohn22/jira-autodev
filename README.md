# Jira → Claude Code Agent

Automatically implements Jira tickets using Claude AI. When you label a Jira ticket `ai-draft`, this pipeline:

1. Triggers a GitHub Actions workflow via Jira Automation
2. Claude reads the ticket, explores the codebase, writes the fix
3. Opens a **draft PR** on a new branch
4. Comments the PR link back on the Jira ticket
5. Notifies your Microsoft Teams channel

**You review and merge — Claude never touches `main` directly.**

---

## Architecture

```
Jira ticket (label: ai-draft)
        │
        ▼ Jira Automation "Send web request"
GitHub repository_dispatch
        │
        ▼ .github/workflows/jira-claude.yml
anthropics/claude-code-action@v1
  ├─ Reads Jira ticket via Atlassian MCP
  ├─ Explores codebase
  ├─ Implements fix on new branch
  ├─ Runs composer test
  ├─ Opens draft PR
  └─ Comments PR link on Jira ticket
        │
        ▼
Teams webhook notification → you review the draft PR
```

---

## One-time setup (do this in order)

### 1. Create a free Atlassian / Jira account

1. Go to [atlassian.com](https://atlassian.com) → **Get it free**
2. Create a Jira Software project (free tier supports up to 10 users)
3. Note your **site name** — it's the part before `.atlassian.net` in your URL.
   - Example: if your Jira is `nithinjohn.atlassian.net`, your site name is `nithinjohn`

### 2. Generate an Atlassian API token

1. Log in to [id.atlassian.com](https://id.atlassian.com)
2. Go to **Security** → **API tokens** → **Create API token**
3. Label it `jira-claude-agent` and copy the token — you won't see it again

### 3. Install the Claude Code GitHub App

From your terminal in this repo directory:

```bash
claude /install-github-app
```

This installs the GitHub App and sets `ANTHROPIC_API_KEY` as a repo secret automatically.

### 4. Add GitHub repository secrets

Go to your repo → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**

Add these five secrets:

| Secret name | Value |
|---|---|
| `ANTHROPIC_API_KEY` | Your Anthropic API key from [console.anthropic.com](https://console.anthropic.com) |
| `ATLASSIAN_SITE_NAME` | Your site subdomain (e.g. `nithinjohn`) |
| `ATLASSIAN_USER_EMAIL` | The email you use to log into Atlassian |
| `ATLASSIAN_API_TOKEN` | The token you created in step 2 |
| `TEAMS_WEBHOOK_URL` | Your Teams incoming webhook URL (see below) |

**Getting a Teams webhook URL:**
1. In Teams, go to the channel you want notified
2. Click `···` → **Connectors** → **Incoming Webhook** → **Configure**
3. Name it `Jira Claude Agent`, copy the URL

### 5. Enable branch protection on `main`

1. Repo → **Settings** → **Branches** → **Add branch protection rule**
2. Branch name pattern: `main`
3. Check **Require a pull request before merging** → **Require approvals: 1**
4. Save

This is the safety net — Claude can open PRs but can never merge to `main`.

### 6. Create the Jira Automation rule

In Jira: **Project settings** → **Automation** → **Create rule**

**Trigger:** Issue updated
**Condition:** Label added = `ai-draft`
**Action:** Send web request
- URL: `https://api.github.com/repos/nithinjohn22/jira--code-agent/dispatches`
- Method: `POST`
- Headers:
  ```
  Authorization: Bearer <YOUR_GITHUB_PAT>
  Accept: application/vnd.github+json
  Content-Type: application/json
  ```
- Body:
  ```json
  {
    "event_type": "jira_ticket_assigned",
    "client_payload": { "issue_key": "{{issue.key}}" }
  }
  ```

**Creating the GitHub PAT:**
1. GitHub → **Settings** → **Developer settings** → **Personal access tokens** → **Fine-grained tokens**
2. Set repository access to `nithinjohn22/jira--code-agent`
3. Permissions: **Contents** = Read & Write, **Pull requests** = Read & Write
4. In Jira Automation, store the PAT as a **secured value** (not plain text)

---

## Using it

1. Create or open a Jira ticket with a clear description and acceptance criteria
2. Add the label **`ai-draft`** to the ticket
3. Watch **Actions** tab in GitHub — the workflow starts within seconds
4. Claude will:
   - Create branch `feature/PROJ-123-short-slug`
   - Implement the change and run tests
   - Open a draft PR titled `[PROJ-123] Short description`
   - Comment the PR link on the Jira ticket
   - Notify Teams

5. Review the draft PR, request changes if needed, then merge when happy

---

## Project structure

```
.
├── src/
│   └── Utils/
│       └── ErrorHandler.php     # Base error-handling utilities
├── tests/
│   └── Utils/
│       └── ErrorHandlerTest.php
├── .claude/
│   ├── settings.json            # Claude permission allowlist
│   └── mcp-atlassian.json       # Atlassian MCP server config
├── .github/
│   └── workflows/
│       └── jira-claude.yml      # The automation workflow
├── CLAUDE.md                    # Conventions Claude reads before coding
├── composer.json
└── phpunit.xml
```

---

## Safety guardrails

| Control | Protection |
|---|---|
| Label-gated trigger (`ai-draft`) | You choose which tickets Claude attempts |
| `--max-turns 25` | Caps token spend on unexpectedly complex tickets |
| `permissionMode: allowlist` | Claude can only use approved tools |
| Draft PR + branch protection | Nothing reaches `main` without human approval |
| Scoped Atlassian token | Read + comment only — not admin access |

---

## Running tests locally

```bash
composer install
composer test
```
