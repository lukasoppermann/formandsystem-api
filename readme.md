# Form&System API Documentation

The Form&System API is used to retrieve items from the clients database (and return data when in a context of the cms).
Data should be cached to minimize render times and the number of request.

The api is divided into multiple parts.

API  |  Description
-------------  |  -------------
[Pages Api](#pages-api) | to retrieve single pages by `id` or `path`
[Streams Api](#stream-api) | to retrieve a stream by `stream`-name like `news` or `navigation` which is a collection of pages

## Pages Api

A page will be returned as an array.

`http://api.formandsystem.com/v#.#/pages/#?parameter=value[&...]`

`http://api.formandsystem.com/v#.#/pages/$path.to.page?parameter=value[&...]`

##### Parameters for calls to the pages api

Parameter  | value | default |description
------------- | ------------- | ------------- | -------------
format  | json | json | format in which the resulting data will be returned
language | [en,de,...] | en | language for which the results will be returned
fields | SQL-compatible fields listing | * | the fields that will be returned from the database
pathSeparator | . : :: + | . | character which will be used to separate path elements instead of /
until* | YYYY-MM-DD or false | false | returns results that are older or equal to the given date
since* | YYYY-MM-DD or false | false | returns results that are newer or equal to the given date
limit* | INT | 20 | max amount of results returned
offset* | INT | 0 | defines the starting offset of the returned results

* currently not in use

## Stream Api

A stream will be returned as an array with every page in it being returned as an array as well.

`http://api.formandsystem.com/v#.#/streams/$streamName?parameter=value[&...]`

##### Parameters for calls to the streams api

Parameter  | value | default | description
------------- | ------------- | ------------- | -------------
format  | json | json | format in which the resulting data will be returned
language | [en,de,...] | en | language for which the results will be returned
limit | INT | 20 | max amount of results returned
offset | INT | 0 | defines the starting offset of the returned results
fields | SQL-compatible fields listing | * | the fields that will be returned from the database
until | YYYY-MM-DD or false | false | returns results that are older or equal to the given date
since | YYYY-MM-DD or false | false | returns results that are newer or equal to the given date
first | boolean | false | returns only the first entry

##

curl -i -H "Accept: application/json" -X POST -d "stream=news&position=1" http://api.formandsystem.local/v1/streams

curl -i -H "Accept: application/json" -X POST -d "stream=news" http://api.formandsystem.local/v1/streams
