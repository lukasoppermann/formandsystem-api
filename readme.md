# Form&System API Documentation

The Form&System API is used to retrieve items from the clients database (and return data when in a context of the cms).
Data should be cached to minimize render times and the number of request.

The api is divided into multiple parts.

[Pages Api](#pages-api)

[Stream Api](#stream-api)

## Pages Api

`http://api.formandsystem.com/v#.#/pages/#?parameter=value[&...]`

`http://api.formandsystem.com/v#.#/pages/$path.to.page?parameter=value[&...]`

##### Parameters for calls to the pages api

Parameter  | value | default |description
------------- | ------------- | ------------- | -------------
format  | json | json | format in which the resulting data will be returned
pathSeparator | . : :: + | . | character which will be used to separate path elements instead of /


## Stream Api

`http://api.formandsystem.com/v#.#/stream/$streamName?parameter=value[&...]`

##### Parameters for calls to the stream api
