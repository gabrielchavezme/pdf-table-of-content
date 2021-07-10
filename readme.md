<a href="https://gabrielchavez.me" target="_blank">
<img width="300" src="https://gabrielchavez.me/storage/2021/01/Logotipo-Gabriel-Cha%CC%81vez-10.png">
</a>
<span>&nbsp;&nbsp;&nbsp;</span>

# Pdf Merge & table of content Solution for PHP and Laravel

This package is a wrapper for the `TCPDF` class that provides an elegant API for merging PDF files and returns a table of contents with bookmarks.

This library is based on the source code of: <a href="https://github.com/karriereat/pdf-merge" target="_blank">karriereat/pdf-merge</a>

The same code structure was used and the functionality was added to make a bookmark in each added document, later it returns an array with the document's table of contents in order to render it in a JS engine on the frontend side.

## Installation

You can install the package via composer:

```bash
composer require gabrielchavezme/pdf-table-of-content
```

## Usage

```php
$pdfMerge = new PdfTableOfContent();

$pdfMerge->add([
    'file' => '/path/to/file1.pdf',
    'title' => 'File 1',
    'id' => 1
]);
$pdfMerge->add([
    'file' => '/path/to/file2.pdf',
    'title' => 'File 2',
    'id' => 2
]);

$pdfMerge->merge('/path/to/output.pdf');
```

Please note, that the `merge` method will throw an `NoFilesDefinedException` if no files where added.

### Check for file existence
You can check if a file was already added for merging by calling:

```php
$pdfMerge->contains('/path/to/file.pdf');
```

## License

Apache License 2.0 Please see [LICENSE](LICENSE) for more information.
