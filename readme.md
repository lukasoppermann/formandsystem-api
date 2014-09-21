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

A stream is a collection of pages like a news stream or a navigation.

### Create record

**URL Scheme used for creating records**
`POST http://api.formandsystem.com/v#.#/streams`

**Possible Arguments**

Argument  | value | required | description
------------- | ------------- | ------------- | -------------
stream  | alphanum and dash | yes | the name of the stream of the new record
parent_id | INT | no | id of the parent record, defaults to 0 if omitted
position | INT | no | position of the record, defaults to last position if omitted

**Return value on Success**
Returns success object with article_id `{success: "true", article_id: INT}`

### Read record

**URL Scheme used for retrieving records**
`GET http://api.formandsystem.com/v#.#/streams/$streamName?parameter=$value[&...]`

While all parameters are optional, the `$stremName` must be given.

**Parameters for calls to the streams api**

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

**Return value on Success**
Returns full record as json.

### Update record

**URL Scheme used for updating records**
`PUT http://api.formandsystem.com/v#.#/streams/$recordId`

The `$recordId` has to be given. All other parameters are optional and only updated if present.

**Possible Arguments**

Argument  | value | required | description
------------- | ------------- | ------------- | -------------
stream  | alphanum and dash | no | the name of the stream of the new record
parent_id | INT | no | id of the parent record, defaults to 0 if omitted
position | INT | no | position of the record, defaults to last position if omitted

**Return value on Success**
Returns success object `{success: "true"}`

### Delete record

Stream records can not be deleted directly.

## Errors & debugging

Whenever possible, the api returns a message indicating success or failure of an action along with an array of error messages.

```javascript
// result for successful action
{
  success: "true",
  ...
}

// result for failed action
{
  success: "false",
  errors: {
    error_name: "error description",
    ...
  }
  ...
}
```

## call api via cli for testing

curl -i -H "Accept: application/json" -X POST -d "stream=news&position=1" http://api.formandsystem.local/v1/streams

curl -i -H "Accept: application/json" -X POST -d "stream=news" http://api.formandsystem.local/v1/streams

curl -i -H "Accept: application/json" -X POST -d "stream=news&parent_id=2&position=0" http://api.formandsystem.local/v1/streams
