# FinalPDF v2.0.4 - Clean Installation Instructions

## ⚠️ Important: Clean Reinstall Required

If you're seeing a blank settings page, follow these steps for a clean installation:

### Step 1: Remove Old Plugin
1. Go to **WordPress Admin → Plugins**
2. **Deactivate** any existing PDF Generator or FinalPDF plugin
3. Click **Delete** to completely remove the old plugin files
4. Clear your WordPress object cache (if using caching plugins)

### Step 2: Install Fresh v2.0.4
1. Download **finalpdf-2.0.4.zip** from this Replit
2. Go to **WordPress Admin → Plugins → Add New → Upload Plugin**
3. Choose the **finalpdf-2.0.4.zip** file
4. Click **Install Now**
5. Wait for installation to complete
6. Click **Activate Plugin**

### Step 3: Verify Installation
1. Go to **WordPress Admin → Plugins**
2. Confirm version shows: **2.0.4**
3. Check that plugin name is: **FinalPDF**

### Step 4: Access Settings
1. Click **FinalPDF** in the left admin menu
2. Click **General Settings**
3. The settings page should now display with:
   - **Two columns on desktop** (labels left, controls right)
   - **Single column on mobile** (stacked layout)

### Step 5: Clear Browser Cache
1. Open your browser's Developer Tools (F12)
2. Right-click the Refresh button
3. Select **Empty Cache and Hard Reload**

## Troubleshooting

### If page is still blank:
1. Enable WordPress debugging in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
2. Check `/wp-content/debug.log` for PHP errors
3. Look for "finalpdf-admin-dashboard.php" errors

### If you see JavaScript errors:
- These are likely from OTHER plugins (admin-help.js, AdminNotice.js)
- These don't affect FinalPDF functionality
- Consider temporarily deactivating other plugins to test

### Check Plugin Directory
SSH into your server and verify:
```bash
ls -la wp-content/plugins/finalpdf/admin/partials/
```

You should see: `finalpdf-admin-dashboard.php`

## Expected Version Info

After successful installation:
- **Plugin Name:** FinalPDF
- **Version:** 2.0.4
- **Author:** FinalDoc
- **Company URL:** finaldoc.io

## Need Help?

If you still have issues after following these steps, please provide:
1. Screenshot of WordPress Plugins page showing version number
2. Contents of `/wp-content/debug.log`
3. Screenshot of the blank page with Developer Console open

---
**FinalPDF v2.0.4** - Professional PDF Generation for WordPress  
© 2025 FinalDoc | hello@finaldoc.io
