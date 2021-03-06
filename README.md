# temporal-shelf

[![PHPUnit tests](https://github.com/cstuder/temporal-shelf/workflows/PHPUnit%20tests/badge.svg)](https://github.com/cstuder/temporal-shelf/actions?query=workflow%3A%22PHPUnit+tests%22)

PHP package to shelve files in directory structures according to years/months/days.

I.e. takes a file `data.txt` and copies it to `..../archive/2020/11/09/1604953664_data.txt`.

Created for usage on [api.existenz.ch](https://api.existenz.ch) and indirectly on [Aare.guru](https://aare.guru). As of 2020 in productive use.

## Installation

`composer require cstuder/temporal-shelf`

## Example usage

```php
<?php
require('vendor/autoload.php');

$shelf = new \cstuder\TemporalShelf\TemporalShelf($targetDirectory);

$shelvedFilename = $shelf->shelveFile($filename);
```

See `docs/example.php` for a full working example.

## Documentation

The `temporal-shelf` library copies files into a shelf (a file archive). A shelf is a directory structure with the default path containing current year, month and day. Additionally the file gets a prefix containing the current timestamp.

I.e.: The file `download.txt` is shelved to `/archive/2020/10/21/1603308783_download.txt`.

The directory structure is generated automatically, with permissions `0755` set.

Both path (`$directoryPattern`) and prefix (`$prefixPattern`) can be configured with a string which is then passed through the PHP [DateTime::format](https://www.php.net/manual/en/datetime.format.php) method.

Example: Setting `$directoryPattern = "Y/W"` and `$prefixPattern = "N_"` would shelve the file to `/archive/2020/43/3_download.txt` (Generating a weekly directory per year).

By default will throw an exceptionn when a file is already exists in the shelve with the same target name. This can be configured with the `OverwriteOptions` values.

Exceptions are thrown when the file is not readable, when the directory structure cannot be built or if the copying fails.

Files are copied. The original file is left untouched and has to be cleaned up by yourself.

The library is intentionally kept simple and doesn't handle non-alphabetical directory or file prefix patterns well. Also keep the shelf directory unpolluted by other files.

### \_\_construct(string $shelfDirectory, string $directoryPattern = 'Y/m/d', string \$filePrefixPattern = 'U\_', string \$timezone = 'UTC', int \$overwriteOption = Options\OverwriteOptions::EXCEPTION_ON_OVERWRITE)

Creates the TemporalShelf object and sets the configuration.

`$shelfDirectory` is the target archive directory root.

`$directoryPattern` is the pattern of the subdirectories where the files are to be sorted in.

`$filePrefixPattern` is the prefix added to the filename when shelving. Set it to `''` in order to disable the prefixing.

`$timezone` is a timezone identifier used when converting the timestamp of the file to the directory path.

`$overwriteOption` determines the behaviour when the target filename already exists in the shelf directory.

Does not validate the shelf directory.

### shelveFile($filename, int $timestamp = null): string

Shelves a file with the current configuration and an optional timestamp. If the timestamp is null, takes the current time.

Returns the full path to the shelved file.

### findAllShelvedFiles(int \$sortOrder = Options\SortOrderOptions::ASCENDING): array

Returns an array of paths to all files in the shelf directory. All files, not just shelved files.

`$sortOrder` determines the order of the array (`ASCENDING|DESCENDING`).

### findFreshestFile(): ?string

Returns the path to the freshest file on the shelf. Returns `null` if no file is found.

Shortcut for `findAllShelvedFiles(Options\SortOrderOptions::DESCENDING)[0]`.

## Testing

Run `composer test` to execute the PHPUnit test suite.

## Releasing

1. Add changes to the [changelog](CHANGELOG.md).
1. Create a new tag `vX.X.X`.
1. Push.

## License

MIT.

## Author

Christian Studer <cstuder@existenz.ch>, Bureau für digitale Existenz.
