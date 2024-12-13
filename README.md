# OpenCart Image Gallery
[![License: GPLv3](https://img.shields.io/badge/license-GPL%20V3-green?style=plastic)](LICENSE)

The extension allows you to create unlimited galleries with their separate pages, as well as modules with images.

## Other Languages

* [Russian](README_RU.md)

## Change Log

* [CHANGELOG.md](docs/CHANGELOG.md)

## Screenshots

* [SCREENSHOTS.md](docs/SCREENSHOTS.md)

## Features

- Full support for multilingual stores
- Full multi-store support
- Three types of galleries available:
    - Product images from specific categories with/without additional images
    - Images from specific directories based on file masks
    - Manually selected images (can also be uploaded and named individually)
- Four image viewer libraries:
    - ColorBox
    - LightBox (responsive)
    - FancyBox (responsive)
    - Magnific PopUp (responsive, integrated into OpenCart 2)
- Advanced module and gallery caching system
- Two module types with extensive settings for displaying galleries:
    - Galleries (list of galleries with/without covers)
    - Photos (multiple albums are displayed as tabs)
- LazyLoad for optimizing large albums
- Last-Modified headers for each album
- Sitemap module for search engines (XML file)
- Individual settings for each gallery/module
- Independent SEO controller based on fast seo_pro
- Ability to display the module not only on specific layouts but also on specific category or product pages
- Configurable number of columns (for both Bootstrap and non-Bootstrap templates)
- Responsive template support

## Compatibility

* OpenCart 2.3, 3.x.

## Installation

- Copy the files from the archive (from the `upload` folder) to the root directory of your site (do not replace existing files).
- Go to "Extensions" > "Modules". Install the Image Gallery module.
- After installation, three buttons (Albums/Modules/Settings) will appear at the top-left corner of all module pages.
- In the module settings page, select layouts for product/category/error pages. If the error page layout is missing, add it in "System/Design/Layouts" by specifying the path `error/not_found` and then select it in the module settings. **Do not skip this step; it is essential for SEO and module functionality.**
- Create at least one album.
- Go to the Modules section, where an SEO hook module will be created to enable SEO-friendly URLs. Place this module on the site's error page layout and click "Save."
- That's it! You can now add the XML feed to Google, Yandex, or other search engines. The XML feed is located at: `http://your-site-address/index.php?route=feed/gallery`

## Management

The module is simple, no manual is required, everything is in the description and hints.

If you have any questions, write to the support thread or send a private message.

## License

* [GPL v3.0](LICENSE.MD)

## Thank You for Using My Extensions!

I have decided to make all my OpenCart extensions free and open-source to benefit the community. Developing, maintaining, and updating these extensions takes time and effort.

If my extensions have been helpful for your project and you’d like to support my work, any donation is greatly appreciated.

### 💙 You can support me via:

* [PayPal](https://paypal.me/TalgatShashakhmetov?country.x=US&locale.x=en_US)
* [CashApp](https://cash.app/$TalgatShashakhmetov)

Your support inspires me to keep improving and developing these tools. Thank you!
