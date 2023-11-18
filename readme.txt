=== Camptix XML CSV ===
 * Plugin Name: Camptix XML CSV
 * Plugin URI: https://europe.wordcamp.org/
 * Description: Converts uploaded XML files to CSV format and back again from CSV to XML. Based on idea and script from Pascal Casier (https://github.com/ePascalC).
 * Version: 1.0.1
 * Author: Carlos Longarela <carlos@longarela.eu>, WordCamp Europe
 * Author URI: https://tabernawp.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: camptix-xml-csv
 * Domain Path: /languages
 * Requires at least: 5.2
 * Requires PHP: 7.4
 * Tested up to: 6.3.2

You can export Speakers, Sessions, Sponsors, Organizers and Volunteers, from `WordPress admin, Tools -> Export` to obtain the WordPress eXtended RSS (WXR file) to convert to CSV.

== Description ==
With this CSV file, you can open it with [LibreOffice Calc](https://www.libreoffice.org/download/download-libreoffice/), [Microsoft Excel](https://www.microsoft.com/en-gb/microsoft-365/excel) (create new sheet, data, and import CSV) or [Google Sheets](https://docs.google.com/spreadsheets/) and provide access to the team to add new ones, modify or delete data.

Thereafter, you can convert again this CSV to XML (WXR format) to import it to the WordCamp website.

Use the shortcode `[camptix_xml_csv]` in the page or post where you'll like to show the converter. That's all.

If you prefer to maintain it private, protect the post with a password or make it available only to admins (by example, with a page in draft mode).
