# AGENTS.md

This document defines the system prompt directives, execution behaviors, and coding standards for AI agents operating on this codebase.

---

## 🚨 CRITICAL RULES

1. **Do Not Overwrite or Delete Without Permission**: Never delete, move, or modify existing files outside the scope of the assigned task without explicit authorization.
2. **Strict Scope Control**: Implement *only* what is requested. Do not perform unasked refactoring or introduce unnecessary dependencies.
3. **Preserve Existing Patterns**: Always analyze and adhere to existing code conventions, folder structures, and naming practices used in the project.
4. **No Placeholders or Stubs**: Do not leave `// TODO`, `/* Implement later */`, or incomplete functions in production-bound code unless explicitly asked to draft a mock.
5. **Security First**: Never expose API keys, credentials, or secrets in code or git commits. Use environment variables.
6. **Graceful Failures**: Ensure all network calls, file I/O, and database operations include robust error handling and proper logging.

---

## 🎯 PLANNING MODE

Activate this mindset before making any architectural or multi-file changes:

* **Context Gathering**: Read relevant files, configuration, and documentation prior to writing a single line of code.
* **Impact Analysis**: Identify potential breaking changes, side effects, and dependencies affected by the proposed task.
* **Step-by-Step Breakdown**:
  1. Define the goal clearly.
  2. Map out files to be created, modified, or removed.
  3. Outline key data models or type definitions required.
  4. Present the implementation plan to the user for approval if the change is structural or complex.
* **Risk Assessment**: Call out potential bottlenecks, edge cases, or performance concerns up front.

---

## 🛠️ CHANGE/EDIT MODE

Follow these execution principles when making edits:

* **Atomic & Minimal Edits**: Keep changes targeted. Avoid large diffs that span unrelated parts of the codebase.
* **Type Safety & Strict Validation**: Ensure full TypeScript/type definition compliance without resorting to `any` types unless strictly necessary.
* **Contextual Imports**: Maintain clean, explicit, and organized import/export structures following project standards.
* **Backwards Compatibility**: Ensure modifications do not break existing public interfaces, APIs, or existing features unless explicitly tasked to do so.
* **Documentation**: Update docstrings, inline comments, or READMEs when modifying signatures or core business logic.

---

## 🗄️ DATABASE SCHEMA CHANGES

When updating or introducing database schemas:

* **Migration Integrity**: Always generate proper migration files (e.g., Prisma, Drizzle, TypeORM, Alembic). Never modify production database schemas directly without a migration strategy.
* **Backward Compatibility**:
  * Never drop columns or tables in a single step if live services depend on them.
  * Favor adding nullable columns or default values over breaking schema updates.
* **Indexing & Performance**: Add foreign keys and indexes to frequently queried fields (e.g., IDs, lookup keys, timestamps).
* **Data Sanitization & Types**: Ensure database schema types map 1:1 with application-level data models and interfaces.

---

## 🧪 TESTING

Maintain software quality across all changes:

* **Test-Driven Thinking**: Write or update tests whenever adding a new feature or fixing a bug.
* **Coverage Requirements**:
  * **Unit Tests**: For utility functions, algorithms, and isolated pure logic.
  * **Integration Tests**: For API endpoints, database access layers, and key state transitions.
* **Non-Flaky Tests**: Avoid non-deterministic assertions (e.g., hardcoded time delays or race-condition prone setups).
* **Self-Verification**: Run the test suite (`npm test`, `pytest`, etc.) locally before submitting code changes to verify zero regressions.

---

## 🎨 UI DESIGN

Guidelines for frontend components, design systems, and user interfaces:

* **Design System Consistency**: Use project-defined visual primitives (Tailwind classes, CSS modules, design tokens, component libraries). Avoid inline custom CSS.
* **Accessibility (a11y)**:
  * Ensure full keyboard navigation support (`tabIndex`, aria tags).
  * Use semantic HTML elements (`<button>`, `<main>`, `<nav>`, `<header>`).
  * Maintain sufficient contrast ratios for text and visual elements.
* **Responsiveness**: Design mobile-first and verify layout adaptability across screen sizes (mobile, tablet, desktop).
* **State Management**:
  * Keep local UI state close to where it is used.
  * Handle loading, error, empty, and success states explicitly for all visual async operations.