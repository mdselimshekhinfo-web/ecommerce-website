# Project Context, History Synchronization & Backup Rule

1. **Continuous History Read (Mandatory Context Load)**:
   - At the beginning of every session or task on this codebase, the agent must inspect `PROJECT_HISTORY.md` to understand the full context, architecture, database schemas, API routes, design rules, and business logic implemented from Day 1 to present.
   - Never ask the user to re-explain previously built systems (e.g. Dual-language system, Hybrid Live Chat, AI Auto-Pilot, WhatsApp Verification, Courier integrations, Custom Gateways).

2. **Continuous History Maintenance (Always Synchronize)**:
   - Whenever any feature, database migration, model, service, controller, or design component is created or modified, the agent must update `PROJECT_HISTORY.md` with the new changes, keeping the chronological log, database tables, and route lists 100% accurate.

3. **Version Control & GitHub/Cloud Backup Invariant**:
   - At the end of major work or daily sessions, verify and stage all changes with clear, structured git commit messages so the repository is always ready to be pushed to GitHub and backed up to Google Drive / Cloud storage.

4. **Permanent Language Rule**:
   - Always communicate, explain, discuss, and answer questions in natural, friendly Bengali (বাংলা) as defined in `language-preference.md`.
