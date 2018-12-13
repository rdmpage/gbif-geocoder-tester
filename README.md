# GBIF Geocode Tester


## Motivation

See

## Idea

Test whether GBIF Geocoding works (https://lyrical-money.glitch.me)

Test using sequences because (a) we want to geocode as many sequences as possible, and (b) if we use recent sequences the specimens are unlikely to be in GBIF already and so make a stronger test.

Data can come from:
- papers that list localities and lat,lon pairs
- sequences that have localities and lat,lon pairs

Create test datasets, have script that runs test, have a way to visualise results.

### Test 1

Given records from GBIF can method recover those records?

### Test 2

Given localities with known coordinates, can geocoder match those with reasonable accuracy?

### Applications

Take example data, e.g. sequences (maybe DNA barcodes), geocode, then add to GBIF.



## Need to be able to measure performance

## Need better way to measure string matching

Cf Elasticsearch

## Need geocoding literature


## Datasets

### Brown frogs

Zhou, Y., Wang, S., Zhu, H., Li, P., Yang, B., & Ma, J. (2017). Phylogeny and biogeography of South Chinese brown frogs (Ranidae, Anura). PLOS ONE, 12(4), e0175113. doi:10.1371/journal.pone.0175113

Data from table 1, localities, latitude and longitude, and accessions. Manually converted to Excel and CSV.


## References

Cardoso, S. D., Serique, K. J., Amanqui, F. K., Santos, J. L. C. D., & Moreira, D. A. (2014). A Gazetteer for Biodiversity Data as a Linked Open Data Solution. 2014 IEEE 23rd International WETICE Conference. doi:10.1109/wetice.2014.19

Gritta, M., Pilehvar, M. T., Limsopatham, N., & Collier, N. (2017). What’s missing in geographical parsing? Language Resources and Evaluation, 52(2), 603–623. doi:10.1007/s10579-017-9385-8

Jurgens, D., Finethy, T., McCorriston, J., Xu, Y.T., & Ruths, D. (2015). Geolocation Prediction in Twitter Using Social Networks: A Critical Analysis and Review of Current Practice. ICWSM. https://www.semanticscholar.org/paper/Geolocation-Prediction-in-Twitter-Using-Social-A-of-Jurgens-Finethy/36aa70f51eb36b7b9dea27c3c84b96c85471ab22