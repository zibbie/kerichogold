# Task: Debugging Visibility and Layout Issues
**ID:** 2026_04_27_DEBUG_01
**Assignee:** Agent_1_Architect

## Context
Changes applied to `strona_glowna.tp` and `custom_checkout.css` are not visible in the browser, even after cache clearing and container restarts. This happens on both `localhost:8080` and VPS.

## Your Mission
1. **Analyze the Template Path:** Verify if `szablony/standardowy.rwd.v2/strona_glowna.tp` is indeed the file being used by the engine. Check `index.php` and `klasy/Szablon.php` (if exists) for any overriding logic.
2. **Docker Mount Verification:** Check if local edits in the workspace are correctly reflected inside the `nevro-wm-web-1` container. 
3. **Draft the Fix:** Create a plan for Agent 2 to apply a "Nuclear Cache Bypass" (e.g., hardcoding a visible marker like `!!! ARCHITECT WAS HERE !!!` and using a fresh CSS versioning string).

## Instructions
- Read `ops/SKILL.md` before starting.
- After analysis, update your status in `ops/Outboxes/Agent_1_Architect/report.md`.
