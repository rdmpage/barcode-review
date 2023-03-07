# Barcode Review

Data and code for a review of DNA barcodes.

## BOLD data

BOLD now provides [“frictionless data” data packages](https://www.boldsystems.org/index.php/datapackages). These include specimen data and sequences, but not unfortunately not links to media.

### 28-Sep-2022
Data used here is [BOLD DNA Barcode Reference Library 28-SEP-2022](https://www.boldsystems.org/index.php/datapackage?id=BOLD_Public.28-Sep-2022) [doi:10.5883/DP-BOLD_Public.28-Sep-2022](https://doi.org/10.5883/DP-BOLD_Public.28-Sep-2022) :x: DOI is not resolving.

9,135,778 sequences.

### BARCODE 500K

[International Barcode of Life data](http://www.boldsystems.org/index.php/datapackage?id=iBOLD.31-Dec-2016) [doi:10.5883/DP-iBOLD.31-Dec-2016](https://doi.org/10.5883/DP-iBOLD.31-Dec-2016)

## Select random subset of data

See https://stackoverflow.com/a/24591688

To get random set of barcodes:

```
SELECT * FROM barcode WHERE processid IN (SELECT processid FROM barcode WHERE marker_code="COI-5P" ORDER BY RANDOM() LIMIT 1000);
```

To get random set of barcodes with GenBank accession numbers:

```
SELECT * FROM barcode WHERE processid IN (SELECT processid FROM barcode WHERE marker_code="COI-5P" ORDER BY RANDOM() LIMIT 1000);
```



