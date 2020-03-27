# Simple Site Parser

## Description
This is a simple site parser. It based on DOMDocument and extend it for requirements of my test job.

#### Known issues
* SSP doesn't support cyrillic symbols in urls
* SSP can't convert a few relative urls to absolute (such as `'../relative_path'`, `'./relative_path'` and `'relative_path'`). It convert `'/relative_path'` only. This bug can be fixed using PCEL_HTTP.

## Usage
Use `parse.php` for parse page and save the report to a file:

    --url - parse requested URL (required)  
    -r    - max HTTP redirects (0 - disable redirects); default: 10  
    -n    - nesting level for recursive parsing (0 - infinity); default: 0  

Use `report.php` for print report to console:

    --domain - print report for specified domain (required)  
    -p       - max records to print (0 - infinity); default: 0  

Use `help.php` for get help.
