# Performance Optimization Guide

## Implemented Optimizations ✅

The following optimizations are already active in the theme:

### 1. jQuery Deregistration (~30KB saved)
- Automatically disabled on frontend
- Kept for admin and Elementor editor
- Can be enabled via: **WordPress Admin → Kontakt → "Włącz jQuery na froncie"**

### 2. WordPress Emoji Removal (~5KB saved)
- Removed emoji detection script
- Removed emoji styles
- Removed DNS prefetch

### 3. Critical Font Preloading
- Source Sans Pro (body font)
- Playfair Display (heading font)
- Improves LCP (Largest Contentful Paint)

### 4. Automatic Cache Busting
- Uses `filemtime()` for CSS/JS versioning
- Browser cache updates automatically on file changes

### 5. PhotoSwipe Integration
- Modern ES module loading
- Zoom controls with mouse wheel, pinch, keyboard
- Optimized for mobile and desktop

## Manual Configuration Required ⚠️

### 1. .htaccess Cache Headers

**File location:** Root WordPress directory (not theme directory)

Add the contents of `HTACCESS_ADDITIONS.txt` to your `.htaccess` file.

**Where to add:**
- AFTER the `# END NON_LSCACHE` block
- BEFORE the `# BEGIN WordPress` block

This sets proper cache expiration for images, fonts, CSS, and JS.

### 2. LiteSpeed Cache - LCP Image Optimization

**Critical Issue:** Your LCP (Largest Contentful Paint) image has both:
- ✅ `fetchpriority="high"` (correct)
- ❌ `data-lazyloaded="1"` (incorrect - causes delay!)

**Fix in LiteSpeed Cache:**

1. Go to **LiteSpeed Cache → Page Optimization → Media**
2. Find **"Lazy Load Images Excludes"**
3. Add: `fetchpriority`
4. Save settings

This excludes images with `fetchpriority="high"` from lazy loading.

### 3. Image Optimization (Optional but Recommended)

**Current Issues from PageSpeed:**
- Images could be 80KB smaller with WebP/AVIF format
- Some images are larger than displayed dimensions

**Solutions:**

**Option A: LiteSpeed Cache (Free)**
1. Go to **LiteSpeed Cache → Image Optimization**
2. Request API key (free)
3. Enable WebP conversion
4. Enable image compression

**Option B: Plugin**
- **ShortPixel** - Free tier available
- **Imagify** - Good compression
- **EWWW Image Optimizer** - Free alternative

### 4. Render-Blocking CSS

**Status:** Normal and expected ⚠️

The CSS file (~24KB) blocks rendering to prevent FOUC (Flash of Unstyled Content).

**Why not fixed:**
- Inlining critical CSS is complex
- Risk of breaking Elementor styles
- Current size is acceptable
- LiteSpeed Cache already minifies/combines CSS

**Advanced optimization (not recommended):**
- Critical CSS extraction (risky with Elementor)
- Manual inline critical styles (high maintenance)

## Performance Checklist

Use this checklist after deploying:

- [ ] jQuery disabled on frontend (check Network tab)
- [ ] Emoji script removed (check Network tab)
- [ ] Fonts preloaded (check Network tab - should load early)
- [ ] .htaccess cache rules added
- [ ] LiteSpeed Cache: LCP image excluded from lazy load
- [ ] Image optimization enabled (WebP conversion)
- [ ] Test with PageSpeed Insights
- [ ] Test with GTmetrix

## Expected Results

After all optimizations:

- **JavaScript:** -35KB (jQuery + emoji)
- **LCP:** Improved (font preload + no lazy load)
- **Cache:** 1 year for images/fonts, 1 month for CSS/JS
- **Mobile Score:** Should improve significantly
- **Desktop Score:** Should be 90+

## Troubleshooting

### Issue: Elementor editor broken
**Solution:** jQuery might be disabled incorrectly. This shouldn't happen (there are multiple checks), but if it does:
1. Go to **WordPress Admin → Kontakt**
2. Enable **"Włącz jQuery na froncie"**
3. Clear LiteSpeed Cache

### Issue: Images not loading in lightbox
**Solution:**
1. Check browser console for errors
2. Clear LiteSpeed Cache
3. Test in incognito mode

### Issue: Fonts not loading
**Solution:**
1. Check Network tab - fonts should have `crossorigin` attribute
2. Clear LiteSpeed Cache
3. Check if font URLs in `functions.php` are correct

### Issue: Cache not working
**Solution:**
1. Verify .htaccess rules were added
2. Check if `mod_expires` is enabled on server
3. Test with browser DevTools (check Response Headers)
4. LiteSpeed Cache should handle most caching anyway

## Monitoring Performance

### Recommended Tools

1. **Google PageSpeed Insights**
   - https://pagespeed.web.dev/
   - Test both mobile and desktop

2. **GTmetrix**
   - https://gtmetrix.com/
   - More detailed waterfall analysis

3. **WebPageTest**
   - https://www.webpagetest.org/
   - Advanced filmstrip view

### Key Metrics to Watch

- **LCP (Largest Contentful Paint):** < 2.5s (good)
- **FID (First Input Delay):** < 100ms (good)
- **CLS (Cumulative Layout Shift):** < 0.1 (good)
- **Total Page Size:** < 1MB (ideal)
- **Requests:** < 50 (ideal)

## Notes

- LiteSpeed Cache does most heavy lifting (minify, combine, cache)
- These theme optimizations complement LiteSpeed Cache
- Always test in incognito after changes
- Clear cache after updates
