# GBIF Geocode Tester


## Motivation

See [Geocoding genomic databases using GBIF](https://www.biorxiv.org/content/early/2018/11/14/469650).

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

See https://www.elastic.co/guide/en/elasticsearch/guide/current/practical-scoring-function.html


### Query Coordination
The coordination factor (coord) is used to reward documents that contain a higher percentage of the query terms. The more query terms that appear in the document, the greater the chances that the document is a good match for the query.

Imagine that we have a query for quick brown fox, and that the weight for each term is 1.5. Without the coordination factor, the score would just be the sum of the weights of the terms in a document. For instance:

- Document with fox → score: 1.5
- Document with quick fox → score: 3.0
- Document with quick brown fox → score: 4.5

The coordination factor multiplies the score by the number of matching terms in the document, and divides it by the total number of terms in the query. With the coordination factor, the scores would be as follows:

- Document with fox → score: 1.5 * 1 / 3 = 0.5
- Document with quick fox → score: 3.0 * 2 / 3 = 2.0
- Document with quick brown fox → score: 4.5 * 3 / 3 = 4.5

The coordination factor results in the document that contains all three terms being much more relevant than the document that contains just two of them.

Remember that the query for quick brown fox is rewritten into a bool query like this:

```
GET /_search
{
  "query": {
    "bool": {
      "should": [
        { "term": { "text": "quick" }},
        { "term": { "text": "brown" }},
        { "term": { "text": "fox"   }}
      ]
    }
  }
}
```
The bool query uses query coordination by default for all should clauses, but it does allow you to disable coordination. Why might you want to do this? Well, usually the answer is, you don’t. Query coordination is usually a good thing. When you use a bool query to wrap several high-level queries like the match query, it also makes sense to leave coordination enabled. The more clauses that match, the higher the degree of overlap between your search request and the documents that are returned.

However, in some advanced use cases, it might make sense to disable coordination. Imagine that you are looking for the synonyms jump, leap, and hop. You don’t care how many of these synonyms are present, as they all represent the same concept. In fact, only one of the synonyms is likely to be present. This would be a good case for disabling the coordination factor:

```
GET /_search
{
  "query": {
    "bool": {
      "disable_coord": true,
      "should": [
        { "term": { "text": "jump" }},
        { "term": { "text": "hop"  }},
        { "term": { "text": "leap" }}
      ]
    }
  }
}
```
When you use synonyms (see Synonyms), this is exactly what happens internally: the rewritten query disables coordination for the synonyms. Most use cases for disabling coordination are handled automatically; you don’t need to worry about it.


## Need geocoding literature


## Datasets

### Brown frogs

Zhou, Y., Wang, S., Zhu, H., Li, P., Yang, B., & Ma, J. (2017). Phylogeny and biogeography of South Chinese brown frogs (Ranidae, Anura). PLOS ONE, 12(4), e0175113. doi:10.1371/journal.pone.0175113

Data from table 1, localities, latitude and longitude, and accessions. Manually converted to Excel and CSV.


## References

Cardoso, S. D., Serique, K. J., Amanqui, F. K., Santos, J. L. C. D., & Moreira, D. A. (2014). A Gazetteer for Biodiversity Data as a Linked Open Data Solution. 2014 IEEE 23rd International WETICE Conference. doi:10.1109/wetice.2014.19

Gritta, M., Pilehvar, M. T., Limsopatham, N., & Collier, N. (2017). What’s missing in geographical parsing? Language Resources and Evaluation, 52(2), 603–623. doi:10.1007/s10579-017-9385-8

Jurgens, D., Finethy, T., McCorriston, J., Xu, Y.T., & Ruths, D. (2015). Geolocation Prediction in Twitter Using Social Networks: A Critical Analysis and Review of Current Practice. ICWSM. https://www.semanticscholar.org/paper/Geolocation-Prediction-in-Twitter-Using-Social-A-of-Jurgens-Finethy/36aa70f51eb36b7b9dea27c3c84b96c85471ab22