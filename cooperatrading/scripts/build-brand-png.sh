#!/usr/bin/env bash
# Rasterize the SVG brand assets to PNG so the PDF proforma template
# (which is rendered by DomPDF) can embed them cleanly via GD instead of
# relying on DomPDF's limited SVG support.
#
# Requirements: rsvg-convert (librsvg2-bin / librsvg)
#
# Usage:  bash scripts/build-brand-png.sh

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SRC="$ROOT/public/assets/brand"

if ! command -v rsvg-convert >/dev/null 2>&1; then
    echo "rsvg-convert is required. Install with:" >&2
    echo "  - Debian/Ubuntu: sudo apt-get install -y librsvg2-bin" >&2
    echo "  - Alpine:        apk add rsvg-convert" >&2
    echo "  - macOS:         brew install librsvg" >&2
    exit 1
fi

if [[ ! -d "$SRC" ]]; then
    echo "Brand directory not found: $SRC" >&2
    exit 1
fi

# icon-02: used as the proforma PDF header logo (wide, ~600px)
if [[ -f "$ROOT/public/icon-02.svg" ]]; then
    rsvg-convert -w 600 -f png -o "$SRC/icon-02.png" "$ROOT/public/icon-02.svg"
    echo "  -> $SRC/icon-02.png"
fi

# logo-horizontal: alternate horizontal logo (~600px wide)
if [[ -f "$SRC/logo-horizontal.svg" ]]; then
    rsvg-convert -w 600 -f png -o "$SRC/logo-horizontal.png" "$SRC/logo-horizontal.svg"
    echo "  -> $SRC/logo-horizontal.png"
fi

# logo-mark: square mark used for favicons, OG images, etc. (~240px wide)
if [[ -f "$SRC/logo-mark.svg" ]]; then
    rsvg-convert -w 240 -f png -o "$SRC/logo-mark.png" "$SRC/logo-mark.svg"
    echo "  -> $SRC/logo-mark.png"
fi

echo "Brand PNGs generated."
