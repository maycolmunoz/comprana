---
name: Comprana
description: Dark-themed Latin-American e-commerce with bold red accents and generous rounded forms
colors:
  primary: "#16a34a"
  secondary: "#14b8a6"
  accent: "#22d3ee"
  red-accent: "#dc2626"
  base-100: "#1a1412"
  base-200: "#151110"
  base-300: "#0f0c0b"
  base-content: "#d6d3cd"
  neutral: "#2d3b36"
  neutral-content: "#dcdad7"
  info: "#3b82f6"
  success: "#22c55e"
  warning: "#f59e0b"
  error: "#ef4444"
typography:
  display:
    fontFamily: "Outfit, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(2.25rem, 6vw, 5rem)"
    fontWeight: 800
    lineHeight: 0.9
    letterSpacing: "-0.04em"
  headline:
    fontFamily: "Outfit, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.5rem, 4vw, 3.75rem)"
    fontWeight: 800
    lineHeight: 1
    letterSpacing: "-0.03em"
  title:
    fontFamily: "Outfit, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.5rem"
    fontWeight: 800
    lineHeight: 1.2
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Outfit, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Outfit, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.625rem"
    fontWeight: 700
    letterSpacing: "0.15em"
rounded:
  sm: "0.75rem"
  md: "1rem"
  lg: "1.5rem"
  xl: "2rem"
  full: "9999px"
spacing:
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#ffffff"
    rounded: "{rounded.lg}"
    padding: "12px 32px"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.base-content}"
    rounded: "{rounded.lg}"
    padding: "12px 32px"
  button-error:
    backgroundColor: "{colors.error}"
    textColor: "#ffffff"
    rounded: "{rounded.lg}"
    padding: "12px 32px"
  card:
    backgroundColor: "{colors.base-100}"
    textColor: "{colors.base-content}"
    rounded: "{rounded.xl}"
    padding: "32px"
  input:
    backgroundColor: "rgba(214, 211, 205, 0.05)"
    textColor: "{colors.base-content}"
    rounded: "{rounded.md}"
    padding: "12px 16px"
  badge:
    backgroundColor: "rgba(220, 38, 38, 0.1)"
    textColor: "#dc2626"
    rounded: "{rounded.full}"
    padding: "6px 16px"
  section-header:
    backgroundColor: "{colors.base-100}"
    textColor: "{colors.base-content}"
    rounded: "0"
    padding: "24px 32px"
---

# Design System: Comprana

## Overview

**Creative North Star: "The Dark Bazaar"**

Comprana's visual world is a night-market rendered in code: deep charcoal surfaces lit by ember-red accents, generous rounded forms that feel inviting rather than fragile, and typography that shouts in confident uppercase. The dark base palette (oklch 15–21% lightness) creates an immersive backdrop where content and products glow, not the interface itself. Red is punctuation — a bar on the page header, a badge on a product card, a glow on a call-to-action — never wallpaper.

Glassmorphism appears in sticky navbars and slide-overs (backdrop-blur-xl, translucent base-100), giving the dark world a layered, spatial quality without relying on heavy shadows. Elevation is conveyed through subtle shadow shifts (shadow-xl with red tinting) and hover lift, not drop shadows. The system is unapologetically bold: font-black at every heading level, uppercase tracking-widest labels, and prices rendered in italic display weight.

**Key Characteristics:**
- Dark immersive base (oklch 15–21%) with red accent punctuation
- Generous rounded radius (2xl–3xl / 1–2.5rem) on every interactive element
- Heavy uppercase typography with tight tracking
- Glassmorphism on overlays (backdrop-blur, translucent surfaces)
- Product-centric layout with prominent image cards and hover lift
- Latin-American retail warmth in copy and tone

## Colors

The palette is dark-first: deep charcoal surfaces with red accent used as controlled punctuation.

### Primary
- **Forest Green** (#16a34a): The daisyUI primary — used for interactive states in the admin panel, success indicators, and select UI elements in the store. Its green hue sits outside the red accent system, reserved for positive/affirmative actions.

### Secondary
- **Deep Teal** (#14b8a6): Secondary accent in admin and form contexts. Rare in the storefront.

### Accent
- **Cyan** (#22d3ee): Tertiary informational accent, used sparingly in admin status indicators.

### Red Accent
- **Blood Ember** (#dc2626): The system's signature color. Used for the brand mark ("NA" in COMPRA**NA**), active nav states, product badges, call-to-action highlights, page header bars, footer section titles, price symbols, and hover state transitions. This red is the visual heartbeat — never fills large surfaces, always punctuates.

### Neutral
- **Dark Forest** (#2d3b36): Mid-tone neutral for secondary surfaces and subtle borders in the admin theme.
- **Cool Paper** (#d6d3cd): The base-content text color — a warm light gray that reads cleanly on dark surfaces without the harshness of pure white.

### Backgrounds
- **Base 100** (#1a1412): Primary surface color. Cards, modals, navigation, and content containers sit on this warm near-black.
- **Base 200** (#151110): Secondary surface. Page background, footer, and recessed areas.
- **Base 300** (#0f0c0b): Deepest surface. Used for slide-over overlays and deepest layering.

### Semantic
- **Info** (#3b82f6): Processing status, informational badges.
- **Success** (#22c55e): Delivered status, positive confirmations.
- **Warning** (#f59e0b): In-transit status, cautionary states.
- **Error** (#ef4444): Not-delivered status, destructive actions, danger buttons.

### Named Rules
**The Blood Ember Rule.** Red accent appears on ≤15% of any given screen. Its rarity is its power. When every element is red, none of them are.

## Typography

**Display Font:** Outfit (with ui-sans-serif, system-ui, sans-serif fallback)
**Body Font:** Outfit (same stack — single-family system)

**Character:** Outfit is a geometric sans with personality: clean and modern but not sterile. The tight tracking and heavy weights give it a confident, almost editorial quality that suits a bold e-commerce brand. A single type family keeps the system honest — hierarchy is built through weight and size alone.

### Hierarchy
- **Display** (800 weight, clamp(2.25rem, 6vw, 5rem), line-height 0.9): Hero headlines on the landing page. All-caps, ultra-tight tracking (-0.04em). The "COMPRA**NA**" lockup.
- **Headline** (800 weight, clamp(1.5rem, 4vw, 3.75rem), line-height 1): Section titles ("Explora Nuestras Colecciones"), page headers. All-caps, tight tracking.
- **Title** (800 weight, 1.5rem, line-height 1.2): Card titles, product names, modal headings. All-caps, tight tracking.
- **Body** (400 weight, 1rem, line-height 1.6): Descriptions, paragraph text, form labels. Normal case, relaxed leading for readability on dark backgrounds.
- **Label** (700 weight, 0.625rem, letter-spacing 0.15em): Micro labels, category badges, footer section headers, price prefixes. All-caps, wide tracking. The "CATÁLOGO SELECTO" / "PRECIO" / "NAVEGACIÓN" layer.

### Named Rules
**The One Weight Rule.** Headlines are always 800. Body is always 400. Labels are always 700. No in-between weights exist in this system. This eliminates hierarchy ambiguity.

## Layout

Max-width container: 1280px (max-w-7xl). Horizontal padding: 24px (px-6) on mobile, expanding to 32px on large screens.

The page structure is a sticky translucent navbar (backdrop-blur-xl, h-20) pinned to the top, a full-width optional header with page title and red accent bar, the main content area (min-h-[60vh] on index pages), and a full-width footer on base-200.

Grid behavior: product listings use responsive card grids (default Tailwind grid/flex). The landing page is a 60/40 split (text left, hero image right) with a clip-path diagonal on the image that collapses to stacked on mobile. Slide-over panels occupy the right half on desktop, full-width on mobile.

Section headers use a full-width base-100 band with bottom border, containing max-w-7xl content — a hero-style intro block per page.

### Spacing Rhythm
Content blocks use 32px (space-y-8) vertical rhythm. Card internal padding: 32px (p-8) on mobile, 48px (p-12) on desktop. Footer grid gap: 48px. The system is generous — no cramped layouts.

## Elevation & Depth

The system is flat-by-default with tonal layering. Depth is conveyed through:
1. **Shadow shifts on hover:** Cards use `shadow-xl shadow-red-900/5` at rest, escalating to `shadow-2xl shadow-red-600/5` on hover with a -2px translate-y lift.
2. **Backdrop-blur glassmorphism:** Sticky nav, modal overlays, slide-over panels, and the footer use `backdrop-blur-xl` with translucent base-100 backgrounds.
3. **Border opacity:** Very subtle `border-base-content/5` borders define edges without creating visual weight.

### Shadow Vocabulary
- **Card rest** (`box-shadow: 0 20px 25px -5px rgba(220, 38, 38, 0.05)`): Product cards and section cards at rest.
- **Card hover** (`box-shadow: 0 25px 50px -12px rgba(220, 38, 38, 0.05)`): Product cards on hover, with -2px lift.
- **Modal** (`box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25)`): Modals and slide-overs. No red tint — pure black for separation.
- **Toast** (`box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25)`): Notification toasts. Same as modal.

### Named Rules
**The Red Shadow Rule.** Shadow-red-900/5 is the signature — it tints every card shadow with the faintest ember glow. Remove it and the cards lose their warmth. Never use red shadows larger than card-level; reserve it for content containers.

## Shapes

Generous and confident. The radius vocabulary:
- **Buttons and inputs:** 1rem (rounded-2xl) — soft rectangles that feel tactile.
- **Cards and containers:** 2rem (rounded-4xl) on product cards; 2.5rem (rounded-[2.5rem]) on section cards and modals — distinctly rounded, almost pillow-like.
- **Badges and pills:** Full radius (rounded-full) — circular tags and status badges.
- **Avatar circles and icon containers:** Full radius with fixed sizes (w-8 h-8, w-10 h-10).
- **Table elements:** No radius — clean edges for data tables.

The diagonal clip-path on the landing page hero image (`clip-path: polygon(15% 0, 100% 0%, 100% 100%, 0% 100%)`) is a one-off geometric accent that collapses on mobile.

## Components

### Buttons
- **Shape:** rounded-2xl (1rem radius), bold uppercase labels, letter-spacing 0.15em.
- **Primary:** Forest green background, white text, shadow-lg shadow-primary/20. Hover: scale-110 on icon buttons, scale-105 on full buttons with transition-all.
- **Ghost:** Transparent background, base-content text. Hover: subtle bg-base-content/10. Used for navigation and secondary actions.
- **Danger:** Error background, white text. Used for destructive actions (delete account, etc.).
- **Circle variant:** btn-circle for icon-only buttons (cart icon, close button, profile avatar).

### Cards (Section Card)
- **Shape:** rounded-[2.5rem], overflow-hidden.
- **Background:** base-100.
- **Border:** 1px solid base-content/5.
- **Shadow:** shadow-xl shadow-red-900/5.
- **Internal padding:** p-8 on mobile, p-12 on desktop.

### Product Cards
- **Shape:** rounded-4xl, overflow-hidden, relative for overlay positioning.
- **Background:** base-100.
- **Hover:** shadow-2xl shadow-red-600/5, -translate-y-2, product image scale-110.
- **Section badge:** White/70 backdrop-blur-md, red text, rounded-full, positioned absolute top-4 left-4.
- **Out-of-stock overlay:** White/60 backdrop-blur with diagonal rotated "AGOTADO" stamp.

### Inputs
- **Style:** bg-base-content/5 background, base-content/20 border, rounded-2xl.
- **Focus:** border-primary with ring-primary/20 ring.
- **Labels:** Separate input-label component, uppercase tracking-widest text-[10px].

### Badges / Chips
- **Shape:** rounded-full, px-3 py-1.5.
- **Default:** bg-base-content/5, base-content text.
- **Status variants:** Soft tinted backgrounds (bg-info/10, bg-success/10, etc.) with matching text and border colors.
- **Typography:** Font-black, uppercase, tracking-widest, text-[10px].

### Navigation
- **Style:** Sticky, backdrop-blur-xl, bg-base-100/90, h-20, border-b border-base-content/10.
- **Links:** Ghost buttons with active state indicated by bg-base-content/10 and text-red-600.
- **Mobile:** Slide-down panel with Alpine.js transitions, same styling scaled to full-width.
- **Brand mark:** "COMPRA**NA**" with red accent on "NA", font-black, tracking-tighter.

### Modals
- **Shape:** rounded-[2.5rem], overflow-hidden.
- **Overlay:** bg-base-content/40 with backdrop-blur-sm.
- **Entry:** opacity + translateY/scale transition (300ms ease-out).
- **Close:** Ghost circle button with rotate-90 hover transition.

### Slide-Over Panel
- **Shape:** Right-anchored, max-w-2xl on desktop, full-width on mobile.
- **Top accent:** 6px gradient bar (from-red-600 to-red-400).
- **Header:** Sticky with backdrop-blur-sm, border-bottom.
- **Content area:** bg-base-200/30 with overflow-y-auto.

### Page Header
- **Element:** Vertical red bar (w-1.5 h-8 bg-red-600, rounded-full) with glow shadow, followed by font-black uppercase tracking-tighter title.
- **Context:** Used in the optional header slot of the app layout.

### Toast Notifications
- **Shape:** rounded-3xl, fixed bottom-5 right-5, max-w-sm.
- **Background:** base-100 with backdrop-blur-xl.
- **Status icon:** Rounded square container (rounded-2xl) with tinted background (success/10, error/10, warning/10).
- **Entry:** Translate from bottom-right with opacity.

### Empty State
- **Shape:** Full-width centered, rounded-full icon container (w-24 h-24 bg-base-content/5).
- **Typography:** Font-black uppercase title, text-[10px] tracking-widest description.
- **CTA:** Primary button with rounded-2xl.

### Footer
- **Background:** base-200/50 with backdrop-blur-md, border-t border-base-content/5.
- **Grid:** 12-column grid with 4/2/2/4 column distribution.
- **Section headers:** text-[10px] uppercase tracking-[0.2em] text-red-600.
- **Links:** Hover transitions to red-600 with translate-x-1.

## Component Architecture

The storefront follows a three-layer contract:

| Layer | Responsibility | Examples |
| --- | --- | --- |
| Blade pages and layouts | Compose a screen, resolve data, own the document shell | `products/index`, `orders/create`, `layouts/app` |
| Mary UI atoms | Buttons, inputs, badges, icons, modals, dropdowns, cards | `x-mary-button`, `x-mary-input`, `x-mary-modal` |
| `x-base.*` compositions | Repeated, stateless visual patterns with Comprana semantics | `x-base.page-header`, `x-base.section-card`, `x-base.empty-state`, `x-base.status-badge`, `x-base.price` |
| Livewire islands | Server state, validation, and actions that change data | `products.list`, `products.details`, `carts.list`, `orders.list` |

Mary UI is a Blade + Alpine library, not a Livewire library. Using it outside a Livewire component is correct and expected.

### Rules

1. If Mary has the component, use Mary; do not reimplement it.
2. If a pattern repeats and carries Comprana semantics, add or reuse a stateless `x-base.*` component.
3. Use Livewire only when the server owns state, validation, or a data-changing action.
4. Blade pages contain composition, not new business logic.
5. Do not wrap Mary in project-specific wrappers without a repeated visual reason.
6. `x-base.*` components never use `wire:model` or Livewire state.
7. Auth and profile forms stay as normal POST requests; use Mary inputs and `x-base.input-error`.
8. Use `route()` and `wire:navigate` consistently for internal navigation.
9. Modals use Mary; delete duplicate custom modals instead of maintaining both.
10. Keep one `h1` per page, `h2` for sections, and `h3` for cards.
11. Livewire and Alpine must load on every page, including pages without Livewire components; the shared head calls `app('livewire')->forceAssetInjection()`.

## Do's and Don'ts

### Do:
- **Do** use red accent as punctuation — active states, badges, section titles, price symbols. Never as a background fill.
- **Do** keep headlines at font-black (800 weight) with uppercase and tight tracking.
- **Do** use backdrop-blur-xl on any overlay surface (nav, modals, slide-overs, footers).
- **Do** use rounded-[2.5rem] for section-level containers and rounded-2xl for interactive elements.
- **Do** apply shadow-red-900/5 to card shadows to maintain the ember warmth.
- **Do** use text-[10px] uppercase tracking-widest for all micro-labels and category tags.

### Don't:
- **Don't** use red as a background color for large surfaces. It's punctuation, not wallpaper.
- **Don't** introduce font weights between 400, 700, and 800. The system is binary.
- **Don't** use sharp corners (rounded-none or rounded-sm) on interactive elements.
- **Don't** add drop shadows to elements smaller than a card. Toasts and badges float by positioning, not shadow.
- **Don't** use pure white (#ffffff) for body text. base-content (#d6d3cd) is the correct text color.
- **Don't** break the max-w-7xl container width. The dark world needs contained edges.
