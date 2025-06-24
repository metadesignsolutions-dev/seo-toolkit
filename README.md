# Best SEO Toolkit

A comprehensive SEO plugin for October CMS that provides advanced meta tag management, Open Graph, Twitter cards, schema JSON-LD, redirects, robots.txt, and sitemap features. Developed by Meta Design Solutions.

---

## Features

### **Core SEO Features**
- **Meta Tags Management** - Title, description, and keywords for each page
- **Open Graph Tags** - Facebook and social media optimization
- **Twitter Cards** - X (Twitter) social media optimization
- **Schema JSON-LD** - Structured data for search engines
- **SEO Score Analysis** - Real-time SEO scoring with suggestions
- **Search Snippet Preview** - Live preview of how your page appears in search results

### **Advanced Features**
- **Redirect Manager** - 301/302 redirects with CSV import/export
- **Sitemap Generator** - Dynamic XML sitemap generation
- **Robots.txt Editor** - Backend robots.txt management
- **Universal Field Injection** - SEO fields automatically appear on any content type with SEO columns

### **Content Type Support**
- **CMS Pages** - SEO fields for static pages
- **Blog Posts** - SEO optimization for blog content
- **Static Pages** - SEO fields for RainLab.Pages
- **News Posts** - SEO optimization for news articles
- **Custom Content** - Works with any model that has SEO columns
- **Tailor Entries** - SEO fields for Tailor content

---

## Installation

**Install via OctoberCMS Marketplace** (Recommended)
- Go to Extensions > Install Plugins
- Search for "Best SEO Toolkit"
- Click Install

**Manual Installation**
- Download the plugin
- Extract to `plugins/metadesignsolutions/mdsoctoberseo/`
- Run `php artisan plugin:refresh metadesignsolutions.mdsoctoberseo`

**📖 Documentation**
- [User Manual PDF](docs/user-manual.pdf) - Complete feature guide and examples

---

## Configuration

### **Global SEO Settings**
1. Go to **Settings > Search Optimization > SEO Meta Manager**
2. Configure:
   - **Site Title & Description** - Default meta tags
   - **Open Graph Settings** - Social media defaults
   - **Twitter Card Settings** - X platform defaults
   - **Schema.org Settings** - Structured data defaults
   - **Content Type Visibility** - Choose which content types show SEO fields

### **Per-Page SEO**
1. Edit any CMS Page, Blog Post, or supported content type
2. You'll see a new **SEO** tab with:
   - **Search Result Preview** - Live preview of search appearance
   - **SEO Score** - Real-time scoring with suggestions
   - **Meta Tags** - Title, description, keywords
   - **Social Media** - Open Graph and Twitter cards
   - **Schema JSON-LD** - Custom structured data

---

## Usage Examples

### **SEO Fields on Content**
```php
// SEO fields automatically appear on any model with these columns:
// - seo_title
// - seo_description  
// - seo_keywords
// - og_title, og_description, og_image
// - twitter_title, twitter_description
// - schema_jsonld
```

### **Frontend Components**
```twig
{# Inject meta tags into your layout #}
[metaTags]

{# Generate sitemap #}
[sitemapGenerator]

{# Output schema JSON-LD #}
[schemaJsonLd]
```

### **Redirect Management**
1. Go to **SEO Toolkit > Redirect Manager**
2. Add individual redirects or import CSV files
3. Manage 301/302 redirects for SEO health

---

## Key Benefits

- **No Plugin Dependencies** - Works independently
- **Universal Compatibility** - Works with any content type
- **Automatic Field Injection** - SEO fields appear automatically
- **Professional UI** - Clean, intuitive backend interface
- **SEO Best Practices** - Built-in validation and suggestions
- **Marketplace Ready** - Follows OctoberCMS standards

---

## Updates & Maintenance

The plugin includes automatic updates through OctoberCMS:
- **Version Management** - Automatic version tracking
- **Database Migrations** - Seamless schema updates
- **Backward Compatibility** - Safe updates without data loss

---

## Support

- **Documentation**: Built-in help system in the backend
- **Support**: Contact MetaDesign Solutions (sales@metadesignsolutions.com)
- **Updates**: Automatic through OctoberCMS marketplace
- **License**: Managed by OctoberCMS marketplace

---

## Requirements

- **OctoberCMS** 3.x
- **PHP** 8.0+
- **Database** MySQL 5.7+ or PostgreSQL 10+

---

**© 2025 Meta Design Solutions. All rights reserved.**