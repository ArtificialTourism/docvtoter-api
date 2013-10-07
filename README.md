Drivers of Change API
=====================

This is the Application Programming Interface (API) for Drivers of Change (DoC)

Making Requests
---------------

All api requests should go through `/api`. API requests are mapped in the following format:

```
/api/resource/[method]
```
HTTP verbs (GET, POST, PUT, DELETE), will be detected and used if given. The optional url argument `[method]` can be provided instead, where HTTP verbs are unable to be used for whatever reason. Replace `[method]` with `get`, `post`, `put` or `delete`.


Data Formats
------------

The default data type returned is `json`, you can specify the following data types to be returned using the optional `format` query parameter:

 * json (default)
 * xml
 * xmlrpc
 * php (returns native php objects, only useful for internal REST calls)
 
`jsonp` calls are possible with format set to `json` and the additional parameter `jsonp_callback` so responses are wrapped in a jsonp compatible response.

API Docs
--------

The REST API can itself provide basic documentation based on the php doc comments for each controller action. This allows for some basic API discoverability. For example, `/api/usage` profides basic information on available endpoints. `/api/card/usage` will provide information on the `card` endpoint.