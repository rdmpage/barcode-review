# GenBank edit history

GenBank stores the edit history for a sequences, including changes to metadata about a sequence. There is no API for this, but we can fetch the webpage and extract the data.

The history for a sequence is given by the URL `https://www.ncbi.nlm.nih.gov/nucleotide/<id>?report=girevhist` where `<id>` is the accession number or GI number. For example, this link gets the history for [DQ275993](https://www.ncbi.nlm.nih.gov/nucleotide/DQ275993?report=girevhist).
