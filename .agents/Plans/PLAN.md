# AI Project Planner — Architecture Plan

## Project Location

`opencode/nextjs-app/` (subdirectory of this repo)

## Tech Stack

- **Framework**: Next.js 14+ (App Router, TypeScript)
- **Styling**: Tailwind CSS v3+
- **UI Library**: shadcn/ui (Radix primitives)
- **Visualization**: Custom SVG/CSS entity-relationship diagram (no ReactFlow)
- **AI (Phase 2)**: OpenAI SDK (`ai` + `@ai-sdk/openai`)

## Routes

| Route | Purpose |
|---|---|
| `/` | Single page — input form at top, generated brief sections below |

Brief is persisted to `localStorage` keyed by nanoid. No dynamic routes needed.

## Layout Flow

Single page, inline results. Input at top, brief sections render below after generation.

## Project Structure

```
nextjs-app/
├── app/
│   ├── layout.tsx              # Root layout with ThemeProvider
│   ├── page.tsx                # Single-page app (input + results)
│   ├── globals.css             # Tailwind + shadcn theme vars
│   └── api/
│       └── generate/
│           └── route.ts        # (Phase 2) OpenAI proxy route
├── components/
│   ├── ui/                     # shadcn primitives (button, card, badge, textarea, etc.)
│   ├── idea-input.tsx          # Textarea + submit button
│   ├── generated-brief.tsx     # Orchestrator: renders all sections
│   ├── brief-section.tsx       # Reusable section wrapper (title + content)
│   ├── app-summary.tsx
│   ├── target-users.tsx
│   ├── core-features.tsx
│   ├── tech-stack.tsx
│   ├── pages-routes.tsx
│   ├── data-model-diagram.tsx  # SVG/CSS entity-relationship diagram
│   ├── build-phases.tsx
│   ├── risks-list.tsx
│   └── starter-prompt.tsx      # Copyable code block
├── lib/
│   ├── types.ts                # All TypeScript interfaces
│   ├── mock-data.ts            # Sample ProjectBrief for UI development
│   └── generate-brief.ts       # (Phase 2) OpenAI SDK call
├── hooks/
│   └── use-local-storage.ts    # Persist brief to localStorage
├── tailwind.config.ts
├── next.config.ts
├── tsconfig.json
└── package.json
```

## Component Tree & Data Flow

```
Layout (ThemeProvider + shadcn Toaster)
└── page.tsx
    ├── idea-input.tsx
    │   └── on submit → calls generateBrief() → returns ProjectBrief
    └── generated-brief.tsx   (only renders when brief exists)
        ├── brief-section (app-summary.tsx)
        ├── brief-section (target-users.tsx)
        ├── brief-section (core-features.tsx)
        ├── brief-section (tech-stack.tsx)
        ├── brief-section (pages-routes.tsx)
        ├── brief-section (data-model-diagram.tsx)
        ├── brief-section (build-phases.tsx)
        ├── brief-section (risks-list.tsx)
        └── brief-section (starter-prompt.tsx)
```

**Data flow:**
1. `page.tsx` holds `brief: ProjectBrief | null` state
2. `idea-input.tsx` fires `onGenerate` prop with raw idea text
3. Parent calls `generateBrief(idea)` — Phase 1 returns mock data; Phase 2 calls `/api/generate`
4. Result set to state and persisted to `localStorage`
5. `generated-brief.tsx` renders all read-only child sections

## Type Definitions

```typescript
interface ProjectBrief {
  id: string
  title: string
  appSummary: string
  targetUsers: string[]
  coreFeatures: { name: string; description: string }[]
  techStack: { category: string; items: { name: string; reason: string }[] }[]
  pagesRoutes: { path: string; name: string; description: string }[]
  dataModel: {
    entities: DataEntity[]
    relationships: DataRelationship[]
  }
  buildPhases: { phase: number; name: string; tasks: string[] }[]
  risks: { risk: string; mitigation: string; severity: 'low' | 'medium' | 'high' }[]
  starterPrompt: string
  createdAt: string
}

interface DataEntity {
  id: string
  name: string
  fields: { name: string; type: string; description: string }[]
}

interface DataRelationship {
  from: string
  to: string
  type: '1:1' | '1:N' | 'N:M'
  label: string
}
```

## Data Model Diagram (SVG/CSS Approach)

Lightweight entity diagram built with Tailwind cards + CSS connector lines:

```
┌─────────────┐    1:N    ┌─────────────┐
│   Project   │──────────▶│   Feature   │
│  id, title  │           │  id, name   │
│  summary... │           │  priority   │
└─────────────┘           └─────────────┘
       │                        │
       │ 1:1                    │ N:M
       ▼                        ▼
┌─────────────┐           ┌─────────────┐
│   Brief     │           │    Tech     │
│  briefId    │           │  stackName  │
│  content... │           │  category   │
└─────────────┘           └─────────────┘
```

- Each entity is a `div` with a header row + field rows using Tailwind borders
- Relationships drawn with CSS pseudo-elements or inline SVG
- Responsive: stacks vertically on mobile, side-by-side with connector lines on `md:+`

## States per Component

Every brief section handles:
- **Loading**: Skeleton placeholder during generation
- **Empty**: Not rendered (conditional on brief existence)
- **Error**: Toast notification with retry
- **Success**: Full brief content rendered

## Implementation Steps

### Step 1: Scaffold Next.js + shadcn
```bash
npx create-next-app@latest nextjs-app --typescript --tailwind --eslint --app --import-alias "@/*"
cd nextjs-app
npx shadcn@latest init -d
npx shadcn@latest add button card badge textarea separator toast
```

### Step 2: Define types & mock data
- Write `lib/types.ts`
- Write `lib/mock-data.ts` with a realistic sample brief

### Step 3: Build UI components bottom-up
- `brief-section.tsx` wrapper
- All section components (summary, users, features, tech, routes, phases, risks, prompt)
- `data-model-diagram.tsx` with SVG/CSS entity graph

### Step 4: Wire the page
- `idea-input.tsx` with textarea + button
- `generated-brief.tsx` orchestrator
- `page.tsx` state management + localStorage persistence

### Step 5: Polish
- Loading skeleton while generating (simulated delay for mock)
- Error state (retry button)
- Empty state (hero prompt before first generation)
- Dark mode toggle

### Step 6 (Phase 2): OpenAI integration
- Create `/api/generate/route.ts`
- Install `openai` SDK + `ai` package + `@ai-sdk/openai`
- Use `generateObject` with Zod schema for structured output
- Stream tokens progressively into brief sections
- Replace `lib/generate-brief.ts` mock with real API call

## OpenAI Integration Details (Phase 2)

```typescript
// lib/generate-brief.ts
import { openai } from '@ai-sdk/openai'
import { generateObject } from 'ai'
import { z } from 'zod'

const briefSchema = z.object({ ... })

export async function generateBrief(idea: string): Promise<ProjectBrief> {
  const { object } = await generateObject({
    model: openai('gpt-4o'),
    schema: briefSchema,
    prompt: `You are a senior technical product manager. Given the app idea below, produce a detailed project brief covering all sections: summary, users, features, tech stack, pages, data model, build phases, risks, and a final starter prompt for a coding agent.

Idea: "${idea}"`
  })
  return { ...object, id: nanoid(), createdAt: new Date().toISOString() }
}
```

`/api/generate` route wraps this server-side to protect API key. Client calls `fetch('/api/generate', { method: 'POST', body: JSON.stringify({ idea }) })`.
