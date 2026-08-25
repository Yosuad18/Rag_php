# DESIGN SYSTEM SPECIFICATION (ADESIGN.md)

This document establishes the official visual design language, theme specifications, color systems, and token definitions for the application interface.

---

## 🎨 DESIGN SYSTEM OVERVIEW

Our design system is built around consistency, accessibility (WCAG AA standard), and seamless dark/light mode adaptability. 

### Core Principles
* **Visual Hierarchy**: Clear spatial layout, typography scale, and intentional use of contrast to guide user attention.
* **Semantic Tokenization**: Abstract raw color hex values into semantic tokens for theme adaptability and rapid updates.
* **Component-First Architecture**: Standardized layout patterns and interactive states across all UI surfaces.

---

## 🛠️ STACK

The front-end design architecture relies on modern, utility-first tooling and component abstractions:

* **Framework**: React / Next.js
* **Styling Engine**: Tailwind CSS v3+ with CSS Variables engine
* **Component Primitives**: Radix UI / Shadcn UI primitives
* **Iconography**: Lucide React
* **Data Visualization**: Recharts / Tremor
* **Typography**: Inter (Primary Sans), JetBrains Mono (Code/Technical)

---

## 🎨 BASE COLORS (PALETTE)

Raw color values defined across neutral and brand spectra.

| Palette Name | Hex Code | Purpose / Application |
| :--- | :--- | :--- |
| **Slate 50** | `#F8FAFC` | App Background (Light) |
| **Slate 100** | `#F1F5F9` | Subtle Surface / Card Fill (Light) |
| **Slate 200** | `#E2E8F0` | Subtle Borders (Light) |
| **Slate 700** | `#334155` | Secondary Text (Light) / Subtle Borders (Dark) |
| **Slate 900** | `#0F172A` | Primary Text (Light) / App Background (Dark) |
| **Indigo 500** | `#6366F1` | Brand Primary Accent |
| **Indigo 600** | `#4F46E5` | Brand Primary Hover / Active |
| **Emerald 500**| `#10B981` | Success Indicator |
| **Amber 500**  | `#F59E0B` | Warning Indicator |
| **Rose 500**   | `#F43F5E` | Destructive / Danger Indicator |

---

## 🏷️ SEMANTIC TOKENS

Semantic mappings translate base colors into UI roles across Light and Dark themes.

### Light & Dark Mode Mappings

```css
@layer base {
  :root {
    --background: 0 0% 100%;
    --foreground: 222.2 84% 4.9%;

    --card: 0 0% 100%;
    --card-foreground: 222.2 84% 4.9%;

    --popover: 0 0% 100%;
    --popover-foreground: 222.2 84% 4.9%;

    --primary: 238.7 83.5% 66.7%;
    --primary-foreground: 210 40% 98%;

    --secondary: 210 40% 96.1%;
    --secondary-foreground: 222.2 47.4% 11.2%;

    --muted: 210 40% 96.1%;
    --muted-foreground: 215.4 16.3% 46.9%;

    --accent: 210 40% 96.1%;
    --accent-foreground: 222.2 47.4% 11.2%;

    --destructive: 0 84.2% 60.2%;
    --destructive-foreground: 210 40% 98%;

    --border: 214.3 31.8% 91.4%;
    --input: 214.3 31.8% 91.4%;
    --ring: 238.7 83.5% 66.7%;
  }

  .dark {
    --background: 222.2 84% 4.9%;
    --foreground: 210 40% 98%;

    --card: 222.2 84% 4.9%;
    --card-foreground: 210 40% 98%;

    --popover: 222.2 84% 4.9%;
    --popover-foreground: 210 40% 98%;

    --primary: 238.7 83.5% 66.7%;
    --primary-foreground: 222.2 47.4% 11.2%;

    --secondary: 217.2 32.6% 17.5%;
    --secondary-foreground: 210 40% 98%;

    --muted: 217.2 32.6% 17.5%;
    --muted-foreground: 215 20.2% 65.1%;

    --accent: 217.2 32.6% 17.5%;
    --accent-foreground: 210 40% 98%;

    --destructive: 0 62.8% 30.6%;
    --destructive-foreground: 210 40% 98%;

    --border: 217.2 32.6% 17.5%;
    --input: 217.2 32.6% 17.5%;
    --ring: 224.3 76.3% 48%;
  }
}