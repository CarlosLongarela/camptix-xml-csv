# WordCamp plugin to convert exported XML to CSV and again to XML

**Based on idea and script from Pascal Casier** (https://github.com/ePascalC)

You can export Speakers, Sessions, Sponsors, Organizers and Volunteers, from `WordPress admin, Tools -> Export` to obtain the WordPress eXtended RSS (WXR file) to convert to CSV.

With this CSV file, you can open it with [LibreOffice Calc](https://www.libreoffice.org/download/download-libreoffice/), [Microsoft Excel](https://www.microsoft.com/en-gb/microsoft-365/excel) (create new sheet, data, and import CSV) or [Google Sheets](https://docs.google.com/spreadsheets/) and provide access to the team to add new ones, modify or delete data.

Thereafter, you can convert again this CSV to XML (WXR format) to import it to the WordCamp website.

Use the shortcode `[camptix_xml_csv]` in the page or post where you'll like to show the converter. That's all.

If you prefer to maintain it private, protect the post with a password or make it available only to admins (by example, with a page in draft mode).

**NOTE:**

* Do not change the CSV exported filename because the system will detect the type of CPT (`wcb_organizer`, `wcb_speaker`, `wcb_session`, `wcb_volunteer`, `wcb_sponsor`) based on the file name portion. For example: camptix-**wcb_organizer**-2023-11-01.csv

* If you have a sponsor CSV file like `camptix-wcb_sponsor-2023-11-01.csv` and you rename it to something like `camptix-wcb_volunteer-2023-11-01.csv`, converting to WXR will be erroneous because field data do not correspond with that CPT.

* Do not change CSV fields order or re-convert to XML will produce wrong data.

* Test in a local environment before making in live site.

* Make a backup or a full WXR export before any change.

**CSV Data:**

CSV headers are defined in `trait-camptix-common.php` file and are:

* CPT wcb_organizer: `'Title', 'Content', 'Excerpt', 'Post Name', 'WP User Name', 'Is First Time'`

* CPT wcb_speaker: `'Speaker ID', 'Title', 'Content', 'Excerpt', 'Post Name', 'User Email', 'WP User Name', 'Is First Time'`

* CPT wcb_session: `'Title', 'Content', 'Excerpt', 'Post Name', 'Session Time', 'Session Duration in seconds', 'Session Type', 'Session Slides', 'Session Video', 'Session Speaker ID', 'Track', 'Track Nicename'`

* CPT wcb_volunteer: `'Title', 'Content', 'Excerpt', 'Post Name', 'WP User Name', 'Volunteer Email', 'Is First Time'`

* CPT wcb_sponsor: `'Title', 'Content', 'Excerpt', 'Post Name', 'Company Name', 'Website', 'First Name', 'Last Name', 'Email Address', 'Phone Number', 'Street Address', 'City', 'State', 'Zip Code', 'Country'`

Any change in headers will also be made on corresponding methods on both classes (class to convert *XML -> CSV* and class to convert *CSV -> XML*)
