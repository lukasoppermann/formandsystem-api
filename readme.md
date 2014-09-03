# Form&System API Documentation

The Form&System API is used to retrieve items from the clients database (and return data when in a context of the cms).
Data should be cached to minimize render times and the number of request.

The api is divided into multiple parts.

API  |  Description
-------------  |  -------------
[Pages Api](#pages-api) | to retrieve single pages by `id` or `path`
[Stream Api](#stream-api) | to retrieve a stream by `stream`-name like `news` or `navigation` which is a collection of pages

## Pages Api

`http://api.formandsystem.com/v#.#/pages/#?parameter=value[&...]`

`http://api.formandsystem.com/v#.#/pages/$path.to.page?parameter=value[&...]`

##### Parameters for calls to the pages api

Parameter  | value | default |description
------------- | ------------- | ------------- | -------------
format  | json | json | format in which the resulting data will be returned
language | [en,de,...] | en | language for which the results will be returned
limit | INT | 20 | max amount of results returned
offset | INT | 0 | defines the starting offset of the returned results
fields | ... | * | the fields that will be returned from the database
until | YYYY-MM-DD or false | false | returns results that are older or equal to the given date
since | YYYY-MM-DD or false | false | returns results that are newer or equal to the given date
pathSeparator | . : :: + | . | character which will be used to separate path elements instead of /


## Stream Api

`http://api.formandsystem.com/v#.#/stream/$streamName?parameter=value[&...]`

##### Parameters for calls to the stream api

Parameter  | value | default |description
------------- | ------------- | ------------- | -------------
format  | json | json | format in which the resulting data will be returned
language | [en,de,...] | en | language for which the results will be returned
limit | INT | 20 | max amount of results returned
offset | INT | 0 | defines the starting offset of the returned results
fields | ... | * | the fields that will be returned from the database
until | YYYY-MM-DD or false | false | returns results that are older or equal to the given date
since | YYYY-MM-DD or false | false | returns results that are newer or equal to the given date
