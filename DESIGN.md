---
name: Maxfit Gym Website
description: Bold sports/fitness marketing site — red/black/white palette, diagonal-cut graphic accents, real gym photography
colors:
  primary: "#ED1B45"        # main CTA, active tab, links, accent
  secondary: "#141414"      # dark section backgrounds (schedule, footer, hero overlay base)
  tertiary: "#2B1140"       # hero gradient start (purple→black)
  background: "#F6F6F7"     # light section background
  surface: "#FFFFFF"        # card background
  surface-dark: "#1E1E1E"   # schedule row card
  text-primary: "#16151A"
  text-secondary: "#565A66" # adjusted from source ~#6E7180 (4.1:1) to meet WCAG AA
  text-on-dark: "#FFFFFF"
  text-on-dark-muted: "#A1A1AA"
  border: "#E5E7EB"
  success: "#10B981"
  warning: "#F59E0B"
  error: "#EF4444"
typography:
  h1:
    fontFamily: "Poppins"
    fontSize: 3.25rem      # ~52px
    fontWeight: 800
    lineHeight: 1.1
    letterSpacing: -0.01em
  h2:
    fontFamily: "Poppins"
    fontSize: 2.25rem      # ~36px
    fontWeight: 700
    lineHeight: 1.2
  h3:
    fontFamily: "Poppins"
    fontSize: 1.25rem      # ~20px
    fontWeight: 700
    lineHeight: 1.3
  body-md:
    fontFamily: "Inter"
    fontSize: 1rem
    fontWeight: 400
    lineHeight: 1.6
  label-sm:
    fontFamily: "Inter"
    fontSize: 0.8125rem    # ~13px
    fontWeight: 600
    letterSpacing: 0.02em
  stat-number:
    fontFamily: "Poppins"
    fontSize: 3rem
    fontWeight: 800
rounded:
  sm: 4px
  md: 10px
  lg: 16px
  full: 9999px
spacing:
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 64px
  4xl: 96px
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.full}"
    padding: "14px 32px"
    fontWeight: 600
  button-primary-hover:
    backgroundColor: "#D1123D"
    transform: "scale(1.02)"
  button-outline:
    backgroundColor: "transparent"
    borderColor: "{colors.primary}"
    textColor: "{colors.primary}"
    rounded: "{rounded.full}"
    padding: "10px 24px"
  card:
    backgroundColor: "{colors.surface}"
    rounded: "{rounded.lg}"
    shadow: "0 4px 12px rgba(0,0,0,0.06)"
    padding: "{spacing.lg}"
  badge-icon-circle:
    backgroundColor: "{colors.surface}"
    rounded: "{rounded.full}"
    size: "56px"
    shadow: "0 2px 8px rgba(0,0,0,0.1)"
  day-tab-active:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.text-on-dark}"
    rounded: "{rounded.full}"
    padding: "10px 20px"
  day-tab-inactive:
    backgroundColor: "#2A2A2A"
    textColor: "{colors.text-on-dark-muted}"
    rounded: "{rounded.full}"
    padding: "10px 20px"
  schedule-row:
    backgroundColor: "{colors.surface-dark}"
    rounded: "{rounded.md}"
    padding: "{spacing.lg}"
---

## Overview
Maxfit adalah website marketing untuk gym/fitness studio nyata. Target audience: orang umum yang cari kelas fitness (cycling, martial arts, yoga) dan mau lihat jadwal mingguan sebelum join. Tone: energetic, bold, high-intensity — bukan calm/premium seperti SaaS dashboard, tapi lebih ke "athletic aggression" ala poster gym.

## Colors
Primary (`#ED1B45`) HANYA dipakai untuk: CTA utama, active state (tab hari aktif, active nav), link "Read more", dan aksen graphic diagonal. Jangan dipakai sebagai warna dekorasi biasa. Dark section (`#141414`) dipakai khusus untuk section "high energy" (hero, schedule, footer) — section informasi netral (classes, FAQ) tetap pakai light background.

## Typography
Heading pakai Poppins extrabold/bold untuk kesan tegas dan atletik. Body pakai Inter regular supaya tetap readable di tengah visual yang berat. Jangan campur lebih dari 2 font family.

## Spacing & Layout
12-column grid, max-width ~1280px. Base spacing 8px grid. Section padding besar (64-96px) untuk landing-page feel; dalam card/component tetap gunakan token 16-24px.

## Shapes
Semua button & pill (tab, badge) pakai full radius (pill). Card pakai radius besar (16px) untuk kesan modern-friendly, kontras dengan diagonal-cut graphic accent yang sengaja tajam/skewed — itu bagian dari brand identity, jangan dibulatkan.

## Elevation & Depth
Card pakai subtle shadow. Diagonal banner & dark section tetap flat (depth dari kontras warna, bukan shadow) — konsisten dengan gaya sport-marketing yang berani lewat warna, bukan lewat softness.

## Components
Button primary selalu pill + warna primary. Button outline (mis. "Discover More") pakai border primary + text primary, transparent bg, dipakai di atas dark background. Badge icon circle selalu overlap di pojok kanan-bawah foto card, dengan shadow ringan supaya "floating".

## Rules to Never Break
- Jangan pakai pure black (`#000000`) atau pure white (`#FFFFFF`) untuk background/text besar — selalu soft variant, kecuali card kecil yang boleh pure white sebagai elevation signal.
- Text-secondary (gray) tidak boleh di bawah 4.5:1 contrast — pakai `#565A66`, bukan warna asli source (`#6E7180`) yang cuma ~4.1:1.
- Diagonal/skewed graphic shape adalah signature style — jangan diganti jadi radius biasa.
- Primary red hanya untuk 1 CTA utama per screen, tidak untuk multiple competing accents.
