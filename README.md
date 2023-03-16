# Barcode Review

Data and code for a review of DNA barcodes.

## BOLD data

BOLD now provides [“frictionless data” data packages](https://www.boldsystems.org/index.php/datapackages). These include specimen data and sequences, but not unfortunately not links to media.

### 28-Sep-2022
Data used here is [BOLD DNA Barcode Reference Library 28-SEP-2022](https://www.boldsystems.org/index.php/datapackage?id=BOLD_Public.28-Sep-2022) [doi:10.5883/DP-BOLD_Public.28-Sep-2022](https://doi.org/10.5883/DP-BOLD_Public.28-Sep-2022) :x: DOI is not resolving.

9,135,778 sequences.

### BARCODE 500K

[International Barcode of Life data](http://www.boldsystems.org/index.php/datapackage?id=iBOLD.31-Dec-2016) [doi:10.5883/DP-iBOLD.31-Dec-2016](https://doi.org/10.5883/DP-iBOLD.31-Dec-2016)

This data looks to be the iBOL data, but with taxonomic updates(?). Need to figure out is this a snap shot of the original data in 2016, or a snapshot of the barcodes from iBOL extracted from a more recent version of BOLD?

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

## Changes in taxonomic placement of sequences over time

Can compare taxonomic names in versions of BOLD, e.g.

```
SELECT ibol.processid, ibol.taxon AS old, barcode.taxon AS new, ibol.gb_acs, barcode.gb_acs, ibol.id_precision, barcode.id_precision FROM ibol INNER JOIN barcode USING(processid) WHERE old <> new LIMIT 1000;
```

Note that precision of identification can change in both directions, taxa in BOLD and GenBank may be different, and some name changes are due to synonyms. Bit of a mess, how do we visualise this? Is this because the iBOL subset is not an historical subset?


## Questions

### Taxonomic changes

How many barcode are identified to species level (proper name) in original iBOL and today?

```
SELECT COUNT(processid) FROM ibolog WHERE marker_code="COI-5P";
```

2,789,906 barcodes in original dataset, of which 415,751 were identified to species level: 

```
SELECT COUNT(processid) FROM ibolog WHERE marker_code="COI-5P" AND id_precision = "species"; 
```

A number of these did not have formal species names, that is, the species names contained qualifiers such as “cf.”, “sp.”, “aff.”, or contained numbers:

```
SELECT COUNT(processid) FROM ibolog WHERE marker_code="COI-5P" 
AND id_precision = "species"
AND (taxon LIKE "%sp.%" 
OR taxon LIKE "%cf.%" 
OR taxon LIKE "%aff.%"
OR taxon LIKE "%0%" 
OR taxon LIKE "%1%" 
OR taxon LIKE "%2%" 
OR taxon LIKE "%3%" 
OR taxon LIKE "%4%" 
OR taxon LIKE "%5%" 
OR taxon LIKE "%6%" 
OR taxon LIKE "%7%" 
OR taxon LIKE "%8%" 
OR taxon LIKE "%9%"
OR taxon LIKE "% group%"  
); 
```

= 40,163 barcodes. Hence 415,751 - 40,163 = 375,588 have what look like formal names. This is 375,588/2,789,906 of the original dataset (13%).

#### Updated data

```
SELECT COUNT(processid) FROM ibol WHERE marker_code="COI-5P";
```

2,784,901 barcodes (cf. 2,789,906 in original dump).

If we do the same query on the updated BARCODE 500K dataset:

```
SELECT COUNT(processid) FROM ibol WHERE marker_code="COI-5P" AND id_precision = "species"; 

```

1,197,370 barcodes are identified to species level (much improved).

```
SELECT COUNT(processid) FROM ibol WHERE marker_code="COI-5P" 
AND id_precision = "species"
AND (taxon LIKE "%sp.%" 
OR taxon LIKE "%cf.%" 
OR taxon LIKE "%aff.%"
OR taxon LIKE "%0%" 
OR taxon LIKE "%1%" 
OR taxon LIKE "%2%" 
OR taxon LIKE "%3%" 
OR taxon LIKE "%4%" 
OR taxon LIKE "%5%" 
OR taxon LIKE "%6%" 
OR taxon LIKE "%7%" 
OR taxon LIKE "%8%" 
OR taxon LIKE "%9%"
OR taxon LIKE "% group%"  
); 
```

178,805 of these are not formal names, so 1,197,370 - 178,805 = 1,018,565 have formal species names, = 1,018,565 / 2,784,901 = 36% of the barcodes.




### Connectivity of barcode records:
- formal taxonomic name
- lat/lon
- voucher specimen code
- publication

### GenBank

Of the original iBOL data, 1,340,895 have a GenBank accession code.

```
SELECT COUNT(processid) FROM ibolog WHERE marker_code="COI-5P" AND gb_acs IS NOT NULL; 
```

In the original dataset none of these are flagged “suppressed”, but in the BARCODE 500K there are 1,787,436 sequences of which 

```
SELECT COUNT(processid) FROM ibol WHERE marker_code="COI-5P" AND gb_acs LIKE "%-suppressed"; 
```

147,681 are flagged as suppressed. These are all identified to the level of order. 



### Data

Original iBOL records: http://bins.boldsystems.org/index.php/datarelease

More recent version of those records: [International Barcode of Life data](http://www.boldsystems.org/index.php/datapackage?id=iBOLD.31-Dec-2016) [doi:10.5883/DP-iBOLD.31-Dec-2016](https://doi.org/10.5883/DP-iBOLD.31-Dec-2016)

### Queries

#### GenBank

Random set of sequences:

```
SELECT gb_acs FROM ibolog WHERE gb_acs IS NOT NULL ORDER BY RANDOM() LIMIT 1000;
```

For these 1000 sequences we retrieved the corresponding EMBL entry and recorded whether there was a PMID or DOI for a work associated with the sequence. 

Of the 1000 sequences, 362 have a PubMed id and 14 have a DOI. Hence more than half of the sequences lack a link to the primary literature (mention Holly Miller et al.).

For each sequence we retrieved the edit history in GenBank. 

Of these 1000 sequences, 243 were at some time labelled “suppressed” due to non compliance with an early release agreement. Of these, 135 records were subsequently “unsuppressed” as they were deemed to comply with the agreement.  Hence there has been ongoing curation of the barcode data.

Edit frequency of sequences:

```
Array
(
    [1] => 35
    [2] => 293
    [3] => 353
    [4] => 15
    [5] => 1
    [6] => 38
    [7] => 56
    [8] => 53
    [9] => 70
    [10] => 45
    [11] => 23
    [12] => 11
    [13] => 5
    [14] => 1
    [15] => 1
)
```







