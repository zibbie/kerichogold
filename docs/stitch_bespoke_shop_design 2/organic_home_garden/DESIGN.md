---
name: Organic Home & Garden
colors:
  surface: '#f7faf5'
  surface-dim: '#d8dbd6'
  surface-bright: '#f7faf5'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f4f0'
  surface-container: '#ecefea'
  surface-container-high: '#e6e9e4'
  surface-container-highest: '#e0e3df'
  on-surface: '#191c1a'
  on-surface-variant: '#424842'
  inverse-surface: '#2d312e'
  inverse-on-surface: '#eff2ed'
  outline: '#737972'
  outline-variant: '#c2c8c0'
  surface-tint: '#4a654e'
  primary: '#4a654e'
  on-primary: '#ffffff'
  primary-container: '#8ba88e'
  on-primary-container: '#233d29'
  inverse-primary: '#b0ceb2'
  secondary: '#8e4d33'
  on-secondary: '#ffffff'
  secondary-container: '#feaa8a'
  on-secondary-container: '#783c24'
  tertiary: '#645e50'
  on-tertiary: '#ffffff'
  tertiary-container: '#a8a091'
  on-tertiary-container: '#3c372b'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#cceace'
  primary-fixed-dim: '#b0ceb2'
  on-primary-fixed: '#07200f'
  on-primary-fixed-variant: '#334d38'
  secondary-fixed: '#ffdbce'
  secondary-fixed-dim: '#ffb59a'
  on-secondary-fixed: '#380d00'
  on-secondary-fixed-variant: '#71361e'
  tertiary-fixed: '#ebe2d0'
  tertiary-fixed-dim: '#cec6b5'
  on-tertiary-fixed: '#1f1b11'
  on-tertiary-fixed-variant: '#4c463a'
  background: '#f7faf5'
  on-background: '#191c1a'
  surface-variant: '#e0e3df'
typography:
  h1:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  h2:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.3'
  h3:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.4'
  h4:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Be Vietnam Pro
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontFamily: Be Vietnam Pro
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-md:
    fontFamily: Be Vietnam Pro
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Be Vietnam Pro
    fontSize: 12px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.02em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  gutter: 24px
  margin: 32px
  container-max-width: 1280px
---

## Brand & Style

The design system is rooted in the concept of "Organic Functionalism." It prioritizes an approachable, human-centric interface that mirrors the warmth of a well-tended home. The personality is grounded and sustainable, steering away from clinical minimalism or aggressive modernism. 

Instead of stark whites and high-contrast blacks, the design system utilizes a soft, light-adaptive palette that feels natural. The visual language evokes a sense of comfort and reliability, designed to make users feel capable and inspired—whether they are seasoned gardeners or first-time plant parents. The overall style is modern but tactile, favoring soft edges and breathable, functional layouts over rigid, editorial structures.

## Colors

The palette for the design system is inspired by natural elements: flora, clay, and grain. 

- **Primary (Sage Green):** Used for main actions, active states, and brand highlights. It represents growth and sustainability.
- **Secondary (Terracotta):** Used for accents, notifications, or seasonal call-outs. It provides warmth and a tactile, earthy contrast.
- **Tertiary (Oatmeal):** This is the primary surface color. It replaces pure white to reduce eye strain and provide a "homely" backdrop.
- **Neutral (Deep Charcoal):** A soft, dark green-tinted charcoal used for typography to ensure high legibility without the harshness of pure black.

Avoid using high-saturation "neon" colors or pure grayscale. All shades should feel slightly desaturated and "baked" by the sun.

## Typography

Typography in this design system is selected for its friendliness and accessibility. 

**Plus Jakarta Sans** is used for all headings. Its slightly rounded terminals and open apertures provide a cheerful, modern look that remains highly legible. **Be Vietnam Pro** serves as the workhorse for body copy and labels. It offers a clean, contemporary aesthetic that balances the more expressive headers, ensuring that long-form content (like gardening guides or product descriptions) is easy to digest.

Maintain a generous line height for body text to enhance the feeling of "airiness" and approachability. Uppercase should be used sparingly, reserved primarily for small labels or badges.

## Layout & Spacing

The design system utilizes a **fixed grid** model to provide a sense of structure and containment, akin to a well-organized garden. 

A 12-column grid is standard for desktop, with a maximum container width of 1280px. Spacing follows a strict 8px base unit (8, 16, 24, 32, 48, 64) to ensure rhythm. While whitespace is encouraged to keep the UI from feeling cluttered, avoid "luxury" gaps; every section should feel connected and purposeful. Margins and gutters are kept wide enough to allow elements to breathe without appearing disconnected.

## Elevation & Depth

Hierarchy is established through **tonal layers** and **ambient shadows**. 

Instead of traditional heavy drop shadows, the design system uses soft, diffused shadows with a slight color tint derived from the Sage or Terracotta palettes. This prevents the UI from feeling "floating" or artificial. 

- **Level 0 (Base):** Oatmeal neutral background.
- **Level 1 (Cards/Containers):** Flat white or light sage with a subtle 1px border in a slightly darker tone.
- **Level 2 (Interactive/Floating):** Ambient, low-opacity shadow (e.g., 8% opacity of the Deep Charcoal) to suggest interactability.

Avoid glassmorphism or overly glossy textures; the depth should feel matte and physical.

## Shapes

The shape language is defined by **softness and organic curves**. 

The design system uses a standard roundedness of 8px (0.5rem) for smaller elements like buttons and input fields, while larger containers like cards and image wrappers use 16px (1rem). This removes the "sharpness" often associated with corporate or tech-heavy sites, making the shop feel more inviting and safe. Avoid fully circular "pill" shapes for buttons unless they are icon-only, to maintain a balance between "friendly" and "functional."

## Components

The design system's components prioritize ease of use and clarity.

- **Buttons:** Solid fills in Sage for primary actions, and Terracotta for secondary "buy" or "special" actions. Corners are rounded at 8px.
- **Input Fields:** Soft Oatmeal backgrounds with a subtle Sage border on focus. No sharp corners. Labels should always be visible above the field.
- **Cards:** Low-elevation containers with 16px corner radii. Used for product listings, where the image is the hero. Include a small "Sustainable" or "Organic" badge as a standard chip component.
- **Chips:** Used for filtering and categories (e.g., "Drought Tolerant," "Pet Friendly"). These should have a light sage background and 8px rounded corners.
- **Checkboxes & Radios:** Softly rounded squares/circles with a thick Sage stroke when active, providing a clear tactile "check" feel.
- **Progress Steppers:** Used for checkout or gardening guides; use soft green lines and numbered circles to keep the user oriented.