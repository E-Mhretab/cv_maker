# PDF Export Setup Guide

## For Local Development

### Option 1: Install wkhtmltopdf (Recommended for Production-like Testing)

Since wkhtmltopdf has been discontinued and is no longer available via Homebrew, you can:

1. **Download from GitHub Releases:**
   ```bash
   # Download the latest release for macOS
   curl -L https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox-0.12.6.1-2.macos-cocoa.pkg -o wkhtmltopdf.pkg
   
   # Install the package
   sudo installer -pkg wkhtmltopdf.pkg -target /
   ```

2. **Or use a Docker container:**
   ```bash
   # Create a simple wrapper script
   echo '#!/bin/bash
   docker run --rm -v "$(pwd):/app" wkhtmltopdf/wkhtmltopdf "$@"' > /usr/local/bin/wkhtmltopdf
   chmod +x /usr/local/bin/wkhtmltopdf
   ```

### Option 2: Use HTML Fallback (Current Behavior)

The system automatically falls back to HTML export when wkhtmltopdf is not available. The HTML version includes:

- ✅ **Print Button** - Direct print functionality
- ✅ **Save as PDF Instructions** - Clear guidance for manual PDF creation
- ✅ **Professional Layout** - Print-optimized styling
- ✅ **No Errors** - Graceful degradation

### Option 3: Use DomPDF Fallback

The system now tries DomPDF as a secondary fallback before HTML. This should work for basic PDF generation.

## For Live Server

The system will automatically:

1. ✅ **Detect wkhtmltopdf** - Find installation at common paths
2. ✅ **Generate High-Quality PDFs** - Professional output
3. ✅ **Handle Errors Gracefully** - Fallback to HTML if needed
4. ✅ **Log Issues** - Debug information for troubleshooting

## Current Behavior

### Local Development:
- **HTML Export** - Professional HTML with print instructions
- **Print Button** - Direct browser print functionality
- **Save as PDF** - Manual PDF creation via browser

### Live Server (with wkhtmltopdf):
- **PDF Export** - High-quality PDF generation
- **Professional Output** - Optimized for printing
- **Automatic Fallback** - HTML if PDF generation fails

## Testing the Export

1. **Click Export PDF** on any CV
2. **Local**: You'll get an HTML file with print instructions
3. **Live Server**: You'll get a proper PDF file

The HTML fallback is actually quite useful for local development as it provides a print-ready version that you can easily convert to PDF using your browser's print function.
