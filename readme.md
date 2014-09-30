# Form&System API Documentation

# Laravel 5.0 branch

**Info:**
http://www.slideshare.net/landlessness/teach-a-dog-to-rest  

http://code.tutsplus.com/tutorials/laravel-4-a-start-at-a-restful-api-updated--net-29785  

http://maxoffsky.com/code-blog/building-restful-api-in-laravel-start-here/

https://github.com/dingo/api/wiki/Basic-Tutorial

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

Returns success object `{success: "true", content: {records as json}}`

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

curl -i -H "Accept: application/json" -X POST -d "stream=news" http://api.formandsystem.local/v1/pages

curl -i -H "Accept: application/json" -X PUT -d "status=2&access_token=dpk5yvtSTnfj2pTGsCbBHVdQa2rVnH2Kn7eUbePV" http://newapi.formandsystem.local/v1/pages/1

curl -i -X POST -d "grant_type=client_credentials&client_id=2imkqYRPPBOxbbnK&client_secret=qA2Wgg9bwPWXwlTnvyWKcnmOcS6KXwGQ&scope=pages.read,pages.write,pages.delete" http://newapi.formandsystem.local/oauth/access_token

curl -i -X GET http://newapi.formandsystem.local/v1/pages/1?access_token=zBRks3eNtwDGYheW7pGTD1LCeERlDSNr58LTiBpI
curl -i -H "Accept: application/json" -X POST -d "status=1&language=en&stream=news&position=1&parent_id=0&access_token=0OX5FQwiFd24OS9fgY1EKHbXe9b76xXiYNML5Nii" http://newapi.formandsystem.local/v1/pages

curl -i -H "Accept: application/json" -X POST -d "status=2&access_token=e91bVjeP5uVjEfGH7xbF8iwBtD8gzXMwXegAmFOO" http://newapi.formandsystem.local/v1/pages/1
