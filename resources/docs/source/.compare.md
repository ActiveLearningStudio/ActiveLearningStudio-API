---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost:8000/docs/collection.json)

<!-- END_INFO -->

#H5P


<!-- START_63e3a887440ff6d148ca0a5d5957983a -->
## List of H5Ps

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Display a listing of the H5Ps

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p`


<!-- END_63e3a887440ff6d148ca0a5d5957983a -->

<!-- START_d968691305763106f6c10964bbe80ee1 -->
## H5P create settings

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/create`


<!-- END_d968691305763106f6c10964bbe80ee1 -->

<!-- START_be26525cce0877341cba62b279e3bcf6 -->
## Store H5P

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/h5p" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/h5p`


<!-- END_be26525cce0877341cba62b279e3bcf6 -->

<!-- START_8614b9901a28821625ac19188ad8ea69 -->
## Retrive H5P based on id to display

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/{}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/{}"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/{}`


<!-- END_8614b9901a28821625ac19188ad8ea69 -->

<!-- START_29ccc5ce276a1e03649154df4dfc99a9 -->
## Retrive H5P based on id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/{}/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/{}/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/{}/edit`


<!-- END_29ccc5ce276a1e03649154df4dfc99a9 -->

<!-- START_a1bff8f2dd47bd9fe0b7f2df3200d70a -->
## Update H5P based on id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/h5p/{}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/{}"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/h5p/{}`

`PATCH api/v1/h5p/{}`


<!-- END_a1bff8f2dd47bd9fe0b7f2df3200d70a -->

<!-- START_2841931e4c7de3832118059ead982a92 -->
## api/v1/h5p/{}
<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/h5p/{}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/{}"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/h5p/{}`


<!-- END_2841931e4c7de3832118059ead982a92 -->

<!-- START_369293816da1be491c68109d134f702b -->
## H5P create settings

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/settings" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/settings"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/settings`


<!-- END_369293816da1be491c68109d134f702b -->

<!-- START_525329a5d3ad637983f78cadac3a1960 -->
## Retrive H5P embed parameters

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/embed/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/embed/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/embed/{id}`


<!-- END_525329a5d3ad637983f78cadac3a1960 -->

#User management


APIs for managing users
<!-- START_ae759839bebb25703d47273f4486ce12 -->
## Subscribe.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/subscribe" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/subscribe"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/subscribe`


<!-- END_ae759839bebb25703d47273f4486ce12 -->

<!-- START_dfb8fbb6facf7c68af221b2363bceb9e -->
## Display the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/users/me" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/users/me"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/users/me`


<!-- END_dfb8fbb6facf7c68af221b2363bceb9e -->

<!-- START_1aff981da377ba9a1bbc56ff8efaec0d -->
## Display a listing of the user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/users`


<!-- END_1aff981da377ba9a1bbc56ff8efaec0d -->

<!-- START_4194ceb9a20b7f80b61d14d44df366b4 -->
## Store a newly created user in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/users`


<!-- END_4194ceb9a20b7f80b61d14d44df366b4 -->

<!-- START_cedc85e856362e0e3b46f5dcd9f8f5d0 -->
## Display the specified user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/users/{user}`


<!-- END_cedc85e856362e0e3b46f5dcd9f8f5d0 -->

<!-- START_296fac4bf818c99f6dd42a4a0eb56b58 -->
## Update the specified user in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/users/{user}`

`PATCH api/v1/users/{user}`


<!-- END_296fac4bf818c99f6dd42a4a0eb56b58 -->

<!-- START_22354fc95c42d81a744eece68f5b9b9a -->
## Remove the specified user from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/users/{user}`


<!-- END_22354fc95c42d81a744eece68f5b9b9a -->

#general


<!-- START_cd4a874127cd23508641c63b640ee838 -->
## doc.json
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/doc.json" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/doc.json"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "variables": [],
    "info": {
        "name": "Curriki API",
        "_postman_id": "0d6c827a-9780-4b72-8872-d42ad2c48aef",
        "description": "",
        "schema": "https:\/\/schema.getpostman.com\/json\/collection\/v2.0.0\/collection.json"
    },
    "item": [
        {
            "name": "H5P",
            "description": "",
            "item": [
                {
                    "name": "List of H5Ps",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "Display a listing of the H5Ps",
                        "response": []
                    }
                },
                {
                    "name": "H5P create settings",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/create",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store H5P",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Retrive H5P based on id to display",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/{}",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Retrive H5P based on id",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/{}\/edit",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update H5P based on id",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/{}",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/{}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/{}",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "H5P create settings",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/settings",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Retrive H5P embed parameters",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/embed\/:id",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                }
            ]
        },
        {
            "name": "User management",
            "description": "\nAPIs for managing users",
            "item": [
                {
                    "name": "Subscribe.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/subscribe",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the authenticated user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/users\/me",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display a listing of the user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/users",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a newly created user in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/users",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/users\/:user",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the specified user in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/users\/:user",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove the specified user from storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/users\/:user",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                }
            ]
        },
        {
            "name": "general",
            "description": "",
            "item": [
                {
                    "name": "doc.json",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "doc.json",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/create",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/create",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/{h5p}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/:h5p",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/{h5p}\/edit",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/:h5p\/edit",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/{h5p}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/:h5p",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/{h5p}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/:h5p",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "library",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "library",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "library\/show\/{id}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "library\/show\/:id",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "library\/store",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "library\/store",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "library\/destroy",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "library\/destroy",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "library\/restrict",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "library\/restrict",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "library\/clear",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "library\/clear",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/embed\/{id}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/embed\/:id",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/export\/{id}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/export\/:id",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "h5p\/content-params\/{id}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "h5p\/content-params\/:id",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Authorize a client to access the user's account.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/authorize",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Approve the authorization request.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/authorize",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Deny the authorization request.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/authorize",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Authorize a client to access the user's account.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/token",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Get all of the authorized tokens for the authenticated user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/tokens",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Delete the given token.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/tokens\/:token_id",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Get a fresh transient token cookie for the authenticated user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/token\/refresh",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Get all of the clients for the authenticated user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/clients",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a new client.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/clients",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the given client.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/clients\/:client_id",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Delete the given client.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/clients\/:client_id",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Get all of the available scopes for the application.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/scopes",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Get all of the personal access tokens for the authenticated user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/personal-access-tokens",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Create a new personal access token for the user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/personal-access-tokens",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Delete the given token.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "oauth\/personal-access-tokens\/:token_id",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Register",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/register",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Login",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/login",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Send a reset link to the given user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/forgot-password",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Reset the given user's password.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/reset-password",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Mark the authenticated user's email address as verified.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/verify-email",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Resend the email verification notification.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/verify-email\/resend",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Logout",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/logout",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Upload thumb image for project",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/upload-thumb",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Share the specified project.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/share-project",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/projects\/{project}\/clone-project",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/clone-project",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove share specified project.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/remove-share-project",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display a listing of the project.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a newly created project in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified project.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the specified project in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove the specified project from storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Reorder playlists in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists\/reorder",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/projects\/{project}\/playlists\/{playlist}\/clone-playlist",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists\/:playlist\/clone-playlist",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display a listing of the playlist.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a newly created playlist in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified playlist.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists\/:playlist",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the specified playlist in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists\/:playlist",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove the specified playlist from storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/projects\/:project\/playlists\/:playlist",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified activity in detail.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activities\/:activity\/detail",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/playlists\/{playlist}\/activities\/{activity}\/clone-activity",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/playlists\/:playlist\/activities\/:activity\/clone-activity",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display a listing of the activity.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activities",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a newly created activity in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activities",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified activity.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activities\/:activity",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the specified activity in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activities\/:activity",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove the specified activity from storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activities\/:activity",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified resource.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-types\/:activityType\/items",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display a listing of the resource.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-types",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a newly created resource in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-types",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified resource.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-types\/:activity_type",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the specified resource in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-types\/:activity_type",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove the specified resource from storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-types\/:activity_type",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display a listing of the resource.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-items",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Store a newly created resource in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-items",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the specified resource.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-items\/:activity_item",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update the specified resource in storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-items\/:activity_item",
                            "query": []
                        },
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Remove the specified resource from storage.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/activity-items\/:activity_item",
                            "query": []
                        },
                        "method": "DELETE",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/libraries",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/libraries",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/single-libraries",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/single-libraries",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/content-type-cache",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/content-type-cache",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/library-install",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/library-install",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/library-upload",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/library-upload",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/files",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/files",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/filter",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/filter",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/finish",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/finish",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/h5p\/ajax\/content-user-data",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/h5p\/ajax\/content-user-data",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display error",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "localhost:8000",
                            "path": "api\/v1\/error",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                }
            ]
        }
    ]
}
```

### HTTP Request
`GET doc.json`


<!-- END_cd4a874127cd23508641c63b640ee838 -->

<!-- START_f510e1eb805892f65cfdde1bdd2570d0 -->
## h5p
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET h5p`


<!-- END_f510e1eb805892f65cfdde1bdd2570d0 -->

<!-- START_7d22820c0fe2b0b9fa52ed10738f004d -->
## h5p/create
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET h5p/create`


<!-- END_7d22820c0fe2b0b9fa52ed10738f004d -->

<!-- START_639198a1902fe338510b7132e5cc8883 -->
## h5p
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/h5p" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST h5p`


<!-- END_639198a1902fe338510b7132e5cc8883 -->

<!-- START_6b43c6f62b1184827c48e3b0cb649fde -->
## h5p/{h5p}
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET h5p/{h5p}`


<!-- END_6b43c6f62b1184827c48e3b0cb649fde -->

<!-- START_ce90dcdac1a20635648c0539951b20cb -->
## h5p/{h5p}/edit
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET h5p/{h5p}/edit`


<!-- END_ce90dcdac1a20635648c0539951b20cb -->

<!-- START_a936c27d351f8b5ef5c21552f4d9e626 -->
## h5p/{h5p}
> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/h5p/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT h5p/{h5p}`

`PATCH h5p/{h5p}`


<!-- END_a936c27d351f8b5ef5c21552f4d9e626 -->

<!-- START_0da4104a8b7a2dc1b2b9746c016df781 -->
## h5p/{h5p}
> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/h5p/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE h5p/{h5p}`


<!-- END_0da4104a8b7a2dc1b2b9746c016df781 -->

<!-- START_cf03da7d624d59031bb2af50a35e24dc -->
## library
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/library" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/library"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET library`


<!-- END_cf03da7d624d59031bb2af50a35e24dc -->

<!-- START_be7f9dc51c79739a0f4b09db197046e6 -->
## library/show/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/library/show/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/library/show/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET library/show/{id}`


<!-- END_be7f9dc51c79739a0f4b09db197046e6 -->

<!-- START_aad132870660623943d02190219b50e7 -->
## library/store
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/library/store" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/library/store"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST library/store`


<!-- END_aad132870660623943d02190219b50e7 -->

<!-- START_d78868c2dbeb02a0842a5b08df469f4a -->
## library/destroy
> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/library/destroy" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/library/destroy"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE library/destroy`


<!-- END_d78868c2dbeb02a0842a5b08df469f4a -->

<!-- START_57169872d02ad4eff559bad219bf8f70 -->
## library/restrict
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/library/restrict" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/library/restrict"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET library/restrict`


<!-- END_57169872d02ad4eff559bad219bf8f70 -->

<!-- START_81059c63b26ab95479204413dc81cd7e -->
## library/clear
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/library/clear" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/library/clear"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST library/clear`


<!-- END_81059c63b26ab95479204413dc81cd7e -->

<!-- START_51730eb6febe54c094149d7d45ec3d67 -->
## h5p/embed/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p/embed/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/embed/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET h5p/embed/{id}`


<!-- END_51730eb6febe54c094149d7d45ec3d67 -->

<!-- START_e67b10624855a0bc4e299df9ff525f93 -->
## h5p/export/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p/export/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/export/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET h5p/export/{id}`


<!-- END_e67b10624855a0bc4e299df9ff525f93 -->

<!-- START_ad74731e05037df4c9d4638abd4eebc5 -->
## h5p/content-params/{id}
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/h5p/content-params/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/h5p/content-params/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET h5p/content-params/{id}`


<!-- END_ad74731e05037df4c9d4638abd4eebc5 -->

<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/authorize`


<!-- END_0c068b4037fb2e47e71bd44bd36e3e2a -->

<!-- START_e48cc6a0b45dd21b7076ab2c03908687 -->
## Approve the authorization request.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/authorize`


<!-- END_e48cc6a0b45dd21b7076ab2c03908687 -->

<!-- START_de5d7581ef1275fce2a229b6b6eaad9c -->
## Deny the authorization request.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/authorize`


<!-- END_de5d7581ef1275fce2a229b6b6eaad9c -->

<!-- START_a09d20357336aa979ecd8e3972ac9168 -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/oauth/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token`


<!-- END_a09d20357336aa979ecd8e3972ac9168 -->

<!-- START_d6a56149547e03307199e39e03e12d1c -->
## Get all of the authorized tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/oauth/tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/tokens`


<!-- END_d6a56149547e03307199e39e03e12d1c -->

<!-- START_a9a802c25737cca5324125e5f60b72a5 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/oauth/tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/tokens/{token_id}`


<!-- END_a9a802c25737cca5324125e5f60b72a5 -->

<!-- START_abe905e69f5d002aa7d26f433676d623 -->
## Get a fresh transient token cookie for the authenticated user.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/oauth/token/refresh" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/token/refresh"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token/refresh`


<!-- END_abe905e69f5d002aa7d26f433676d623 -->

<!-- START_babcfe12d87b8708f5985e9d39ba8f2c -->
## Get all of the clients for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/clients`


<!-- END_babcfe12d87b8708f5985e9d39ba8f2c -->

<!-- START_9eabf8d6e4ab449c24c503fcb42fba82 -->
## Store a new client.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/clients`


<!-- END_9eabf8d6e4ab449c24c503fcb42fba82 -->

<!-- START_784aec390a455073fc7464335c1defa1 -->
## Update the given client.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT oauth/clients/{client_id}`


<!-- END_784aec390a455073fc7464335c1defa1 -->

<!-- START_1f65a511dd86ba0541d7ba13ca57e364 -->
## Delete the given client.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/clients/{client_id}`


<!-- END_1f65a511dd86ba0541d7ba13ca57e364 -->

<!-- START_9e281bd3a1eb1d9eb63190c8effb607c -->
## Get all of the available scopes for the application.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/oauth/scopes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/scopes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/scopes`


<!-- END_9e281bd3a1eb1d9eb63190c8effb607c -->

<!-- START_9b2a7699ce6214a79e0fd8107f8b1c9e -->
## Get all of the personal access tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/personal-access-tokens`


<!-- END_9b2a7699ce6214a79e0fd8107f8b1c9e -->

<!-- START_a8dd9c0a5583742e671711f9bb3ee406 -->
## Create a new personal access token for the user.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/personal-access-tokens`


<!-- END_a8dd9c0a5583742e671711f9bb3ee406 -->

<!-- START_bae65df80fd9d72a01439241a9ea20d0 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/oauth/personal-access-tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/oauth/personal-access-tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/personal-access-tokens/{token_id}`


<!-- END_bae65df80fd9d72a01439241a9ea20d0 -->

<!-- START_d7b7952e7fdddc07c978c9bdaf757acf -->
## Register

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/register`


<!-- END_d7b7952e7fdddc07c978c9bdaf757acf -->

<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Login

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/login`


<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_78c4b7d6388c81c68bc37ec872d44f65 -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/forgot-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/forgot-password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/forgot-password`


<!-- END_78c4b7d6388c81c68bc37ec872d44f65 -->

<!-- START_6d3061d375666b8cf6babe163b36f250 -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/reset-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/reset-password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/reset-password`


<!-- END_6d3061d375666b8cf6babe163b36f250 -->

<!-- START_c0e8219f309b296fd587bc241557abce -->
## Mark the authenticated user&#039;s email address as verified.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/verify-email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/verify-email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/verify-email`


<!-- END_c0e8219f309b296fd587bc241557abce -->

<!-- START_7de63f50d103dc0194c570c267800180 -->
## Resend the email verification notification.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/verify-email/resend" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/verify-email/resend"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/verify-email/resend`


<!-- END_7de63f50d103dc0194c570c267800180 -->

<!-- START_61739f3220a224b34228600649230ad1 -->
## Logout

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/logout`


<!-- END_61739f3220a224b34228600649230ad1 -->

<!-- START_57b4a66cc415c7809e03a83e2dfa7039 -->
## Upload thumb image for project

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/upload-thumb" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/upload-thumb"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/projects/upload-thumb`


<!-- END_57b4a66cc415c7809e03a83e2dfa7039 -->

<!-- START_b5eb04f0d6542f95b6dacffc1fe170d9 -->
## Share the specified project.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/1/share-project" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/share-project"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/projects/{project}/share-project`


<!-- END_b5eb04f0d6542f95b6dacffc1fe170d9 -->

<!-- START_8116176406f690f5afb6e1f462788a6b -->
## api/v1/projects/{project}/clone-project
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/1/clone-project" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/clone-project"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```
> Example response (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "The Science of Golf",
            "description": "Uncover the science, technology, engineering, and mathematics behind the game of golf.",
            "thumb_url": "\/storage\/uploads\/nN5y8v8zh2ghxrKuHCv5wvJOREFw0Nr27s2DPxWq.png",
            "shared": null,
            "starter_project": false,
            "users": [
                {
                    "id": 1,
                    "first_name": "test",
                    "last_name": "test",
                    "email": "localuser@local.com",
                    "role": "teacher"
                }
            ],
            "playlists": [
                {
                    "id": 1,
                    "title": "The Engineering & Design Behind Golf Balls",
                    "order": 0
                },
                {
                    "id": 2,
                    "title": "Topic 2",
                    "order": 1
                }
            ],
            "is_public": false,
            "created_at": "2020-04-30T20:03:12.000000Z",
            "updated_at": "2020-07-11T12:51:07.000000Z"
        },
        {
            "id": 1,
            "name": "The Science of Golf",
            "description": "Uncover the science, technology, engineering, and mathematics behind the game of golf.",
            "thumb_url": "\/storage\/uploads\/nN5y8v8zh2ghxrKuHCv5wvJOREFw0Nr27s2DPxWq.png",
            "shared": null,
            "starter_project": false,
            "users": [
                {
                    "id": 1,
                    "first_name": "test",
                    "last_name": "test",
                    "email": "localuser@local.com",
                    "role": "teacher"
                }
            ],
            "playlists": [
                {
                    "id": 1,
                    "title": "The Engineering & Design Behind Golf Balls",
                    "order": 0
                },
                {
                    "id": 2,
                    "title": "Topic 2",
                    "order": 1
                }
            ],
            "is_public": false,
            "created_at": "2020-04-30T20:03:12.000000Z",
            "updated_at": "2020-07-11T12:51:07.000000Z"
        }
    ]
}
```

### HTTP Request
`POST api/v1/projects/{project}/clone-project`


<!-- END_8116176406f690f5afb6e1f462788a6b -->

<!-- START_4824704072d555009a2b09cfc7ed57a9 -->
## Remove share specified project.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/1/remove-share-project" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/remove-share-project"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/projects/{project}/remove-share-project`


<!-- END_4824704072d555009a2b09cfc7ed57a9 -->

<!-- START_d4bb0000cd4525b356d3f4e604741ee1 -->
## Display a listing of the project.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/projects" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/projects`


<!-- END_d4bb0000cd4525b356d3f4e604741ee1 -->

<!-- START_e832cdeb3d8617c57febfca7405a7263 -->
## Store a newly created project in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/projects`


<!-- END_e832cdeb3d8617c57febfca7405a7263 -->

<!-- START_75829dedef8e9515c7f6ed1540f9b2db -->
## Display the specified project.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/projects/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/projects/{project}`


<!-- END_75829dedef8e9515c7f6ed1540f9b2db -->

<!-- START_de3e82447d2000a0d706c9d01fad13cb -->
## Update the specified project in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/projects/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/projects/{project}`

`PATCH api/v1/projects/{project}`


<!-- END_de3e82447d2000a0d706c9d01fad13cb -->

<!-- START_e4b54f3a75c552f90d5d695795bc8e9f -->
## Remove the specified project from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/projects/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/projects/{project}`


<!-- END_e4b54f3a75c552f90d5d695795bc8e9f -->

<!-- START_5738f6cd77e8d8f2311a0e0faf8e5b5a -->
## Reorder playlists in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/1/playlists/reorder" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists/reorder"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/projects/{project}/playlists/reorder`


<!-- END_5738f6cd77e8d8f2311a0e0faf8e5b5a -->

<!-- START_ed16c8ad3ce246e689c746c06b9a93a3 -->
## api/v1/projects/{project}/playlists/{playlist}/clone-playlist
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/1/playlists/1/clone-playlist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists/1/clone-playlist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```
> Example response (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "The Science of Golf",
            "description": "Uncover the science, technology, engineering, and mathematics behind the game of golf.",
            "thumb_url": "\/storage\/uploads\/nN5y8v8zh2ghxrKuHCv5wvJOREFw0Nr27s2DPxWq.png",
            "shared": null,
            "starter_project": false,
            "users": [
                {
                    "id": 1,
                    "first_name": "test",
                    "last_name": "test",
                    "email": "localuser@local.com",
                    "role": "teacher"
                }
            ],
            "playlists": [
                {
                    "id": 1,
                    "title": "The Engineering & Design Behind Golf Balls",
                    "order": 0
                },
                {
                    "id": 2,
                    "title": "Topic 2",
                    "order": 1
                }
            ],
            "is_public": false,
            "created_at": "2020-04-30T20:03:12.000000Z",
            "updated_at": "2020-07-11T12:51:07.000000Z"
        },
        {
            "id": 1,
            "name": "The Science of Golf",
            "description": "Uncover the science, technology, engineering, and mathematics behind the game of golf.",
            "thumb_url": "\/storage\/uploads\/nN5y8v8zh2ghxrKuHCv5wvJOREFw0Nr27s2DPxWq.png",
            "shared": null,
            "starter_project": false,
            "users": [
                {
                    "id": 1,
                    "first_name": "test",
                    "last_name": "test",
                    "email": "localuser@local.com",
                    "role": "teacher"
                }
            ],
            "playlists": [
                {
                    "id": 1,
                    "title": "The Engineering & Design Behind Golf Balls",
                    "order": 0
                },
                {
                    "id": 2,
                    "title": "Topic 2",
                    "order": 1
                }
            ],
            "is_public": false,
            "created_at": "2020-04-30T20:03:12.000000Z",
            "updated_at": "2020-07-11T12:51:07.000000Z"
        }
    ]
}
```

### HTTP Request
`POST api/v1/projects/{project}/playlists/{playlist}/clone-playlist`


<!-- END_ed16c8ad3ce246e689c746c06b9a93a3 -->

<!-- START_04d0433e8d1e16965dac2606ac067416 -->
## Display a listing of the playlist.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/projects/1/playlists" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/projects/{project}/playlists`


<!-- END_04d0433e8d1e16965dac2606ac067416 -->

<!-- START_a93f82d5d38155b5faa5021da933fa12 -->
## Store a newly created playlist in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/projects/1/playlists" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/projects/{project}/playlists`


<!-- END_a93f82d5d38155b5faa5021da933fa12 -->

<!-- START_39d40f33110103c75e8260da8c0e28c7 -->
## Display the specified playlist.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/projects/1/playlists/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/projects/{project}/playlists/{playlist}`


<!-- END_39d40f33110103c75e8260da8c0e28c7 -->

<!-- START_4bc7fcc89f8b0e68a8fb0628eaf1a323 -->
## Update the specified playlist in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/projects/1/playlists/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/projects/{project}/playlists/{playlist}`

`PATCH api/v1/projects/{project}/playlists/{playlist}`


<!-- END_4bc7fcc89f8b0e68a8fb0628eaf1a323 -->

<!-- START_feb1f1225dc03ec1052829032b6fc7b5 -->
## Remove the specified playlist from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/projects/1/playlists/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/projects/1/playlists/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/projects/{project}/playlists/{playlist}`


<!-- END_feb1f1225dc03ec1052829032b6fc7b5 -->

<!-- START_1dffbfda926912c6f46776975e41d907 -->
## Display the specified activity in detail.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activities/1/detail" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activities/1/detail"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activities/{activity}/detail`


<!-- END_1dffbfda926912c6f46776975e41d907 -->

<!-- START_622458bef71e12bdb63fdbd2b3bd8384 -->
## api/v1/playlists/{playlist}/activities/{activity}/clone-activity
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/playlists/1/activities/1/clone-activity" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/playlists/1/activities/1/clone-activity"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "id": 1,
            "title": "The Engineering & Design Behind Golf Balls",
            "order": 0,
            "is_public": false,
            "activities": [
                {
                    "id": 4,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 0,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 17774,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "abc",
                    "type": "h5p",
                    "content": "test",
                    "shared": false,
                    "order": null,
                    "thumb_url": "\/storage\/uploads_tmp\/DrV6rZ6ZDXFMT1k51gbOqw04rqguq6CMtiiD1nDH.png",
                    "subject_id": "Mathematics",
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": "2020-08-30T20:22:47.000000Z",
                    "updated_at": "2020-08-30T20:22:47.000000Z"
                },
                {
                    "id": 17776,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "abc",
                    "type": "h5p",
                    "content": "test",
                    "shared": false,
                    "order": null,
                    "thumb_url": "\/storage\/uploads_tmp\/DrV6rZ6ZDXFMT1k51gbOqw04rqguq6CMtiiD1nDH.png",
                    "subject_id": "Mathematics",
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": "2020-08-30T20:24:29.000000Z",
                    "updated_at": "2020-08-30T20:24:29.000000Z"
                },
                {
                    "id": 3,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 1,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 1,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 2,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 2,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 3,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 6,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 4,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 5,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 5,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 7,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 6,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                }
            ],
            "created_at": null,
            "updated_at": null
        },
        {
            "id": 1,
            "title": "The Engineering & Design Behind Golf Balls",
            "order": 0,
            "is_public": false,
            "activities": [
                {
                    "id": 4,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 0,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 17774,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "abc",
                    "type": "h5p",
                    "content": "test",
                    "shared": false,
                    "order": null,
                    "thumb_url": "\/storage\/uploads_tmp\/DrV6rZ6ZDXFMT1k51gbOqw04rqguq6CMtiiD1nDH.png",
                    "subject_id": "Mathematics",
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": "2020-08-30T20:22:47.000000Z",
                    "updated_at": "2020-08-30T20:22:47.000000Z"
                },
                {
                    "id": 17776,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "abc",
                    "type": "h5p",
                    "content": "test",
                    "shared": false,
                    "order": null,
                    "thumb_url": "\/storage\/uploads_tmp\/DrV6rZ6ZDXFMT1k51gbOqw04rqguq6CMtiiD1nDH.png",
                    "subject_id": "Mathematics",
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": "2020-08-30T20:24:29.000000Z",
                    "updated_at": "2020-08-30T20:24:29.000000Z"
                },
                {
                    "id": 3,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 1,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 1,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 2,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 2,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 3,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 6,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 4,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 5,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 5,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                },
                {
                    "id": 7,
                    "playlist": {
                        "id": 1,
                        "title": "The Engineering & Design Behind Golf Balls",
                        "project_id": 1,
                        "order": 0,
                        "mongo_projectid": "5eab2f005023ef5f784e5063",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null,
                        "is_public": false
                    },
                    "title": "",
                    "type": "h5p",
                    "content": "",
                    "shared": false,
                    "order": 6,
                    "thumb_url": null,
                    "subject_id": null,
                    "education_level_id": null,
                    "is_public": false,
                    "created_at": null,
                    "updated_at": null
                }
            ],
            "created_at": null,
            "updated_at": null
        }
    ]
}
```

### HTTP Request
`POST api/v1/playlists/{playlist}/activities/{activity}/clone-activity`


<!-- END_622458bef71e12bdb63fdbd2b3bd8384 -->

<!-- START_5f86527ae6872d2a9c3c73871e38d78a -->
## Display a listing of the activity.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activities"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activities`


<!-- END_5f86527ae6872d2a9c3c73871e38d78a -->

<!-- START_33aef576e6d971e2ba91dd3d8403d390 -->
## Store a newly created activity in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/activities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activities"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/activities`


<!-- END_33aef576e6d971e2ba91dd3d8403d390 -->

<!-- START_0c0cabe6076f4280d16c2869fadd9387 -->
## Display the specified activity.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activities/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activities/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activities/{activity}`


<!-- END_0c0cabe6076f4280d16c2869fadd9387 -->

<!-- START_66e6c51c201a5a8ca254ce9e8e8a63a9 -->
## Update the specified activity in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/activities/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activities/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/activities/{activity}`

`PATCH api/v1/activities/{activity}`


<!-- END_66e6c51c201a5a8ca254ce9e8e8a63a9 -->

<!-- START_a428814a3c351226a73456c3b41d2a39 -->
## Remove the specified activity from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/activities/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activities/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/activities/{activity}`


<!-- END_a428814a3c351226a73456c3b41d2a39 -->

<!-- START_2c9d295c1b6e5666c66efa3ed320a8f8 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activity-types/1/items" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-types/1/items"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activity-types/{activityType}/items`


<!-- END_2c9d295c1b6e5666c66efa3ed320a8f8 -->

<!-- START_55b9d702946a5d4a899524a1e0fdb620 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activity-types" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-types"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activity-types`


<!-- END_55b9d702946a5d4a899524a1e0fdb620 -->

<!-- START_85ab13e9eb950cc1a9a115c97a9754df -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/activity-types" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-types"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/activity-types`


<!-- END_85ab13e9eb950cc1a9a115c97a9754df -->

<!-- START_bdb1b63e1ce9c0a14580db6d33352940 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activity-types/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-types/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activity-types/{activity_type}`


<!-- END_bdb1b63e1ce9c0a14580db6d33352940 -->

<!-- START_82481680410b6fd26e4ca153a77ab8b6 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/activity-types/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-types/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/activity-types/{activity_type}`

`PATCH api/v1/activity-types/{activity_type}`


<!-- END_82481680410b6fd26e4ca153a77ab8b6 -->

<!-- START_a41e82c2c835644769b62a94fe943bb2 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/activity-types/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-types/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/activity-types/{activity_type}`


<!-- END_a41e82c2c835644769b62a94fe943bb2 -->

<!-- START_25035b0caacdd5b7032fb2b7c52260ca -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activity-items" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-items"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activity-items`


<!-- END_25035b0caacdd5b7032fb2b7c52260ca -->

<!-- START_1de79be21bc9ce181f82963d934b7602 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/activity-items" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-items"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/activity-items`


<!-- END_1de79be21bc9ce181f82963d934b7602 -->

<!-- START_d72216024b73d0dd45d171a00cd44ce9 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/activity-items/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-items/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/activity-items/{activity_item}`


<!-- END_d72216024b73d0dd45d171a00cd44ce9 -->

<!-- START_65149be1a44b052700677709a81a6c70 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost:8000/api/v1/activity-items/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-items/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/activity-items/{activity_item}`

`PATCH api/v1/activity-items/{activity_item}`


<!-- END_65149be1a44b052700677709a81a6c70 -->

<!-- START_fc54754bce57b6e7aa5c36cf9350309a -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost:8000/api/v1/activity-items/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/activity-items/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/activity-items/{activity_item}`


<!-- END_fc54754bce57b6e7aa5c36cf9350309a -->

<!-- START_c711e668f99fc561f6cec66cc5be5b22 -->
## api/v1/h5p/ajax/libraries
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/libraries" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/libraries"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/libraries`

`POST api/v1/h5p/ajax/libraries`


<!-- END_c711e668f99fc561f6cec66cc5be5b22 -->

<!-- START_61242b587899ecf2e0299d7aac87c129 -->
## api/v1/h5p/ajax/single-libraries
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/single-libraries" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/single-libraries"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/single-libraries`


<!-- END_61242b587899ecf2e0299d7aac87c129 -->

<!-- START_5e3d83c2d6d3468f5a604273809e11b0 -->
## api/v1/h5p/ajax/content-type-cache
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/content-type-cache" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/content-type-cache"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/content-type-cache`

`POST api/v1/h5p/ajax/content-type-cache`

`PUT api/v1/h5p/ajax/content-type-cache`

`PATCH api/v1/h5p/ajax/content-type-cache`

`DELETE api/v1/h5p/ajax/content-type-cache`

`OPTIONS api/v1/h5p/ajax/content-type-cache`


<!-- END_5e3d83c2d6d3468f5a604273809e11b0 -->

<!-- START_3b386c29bc55af48f86706d6942be5a8 -->
## api/v1/h5p/ajax/library-install
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/library-install" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/library-install"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/library-install`

`POST api/v1/h5p/ajax/library-install`

`PUT api/v1/h5p/ajax/library-install`

`PATCH api/v1/h5p/ajax/library-install`

`DELETE api/v1/h5p/ajax/library-install`

`OPTIONS api/v1/h5p/ajax/library-install`


<!-- END_3b386c29bc55af48f86706d6942be5a8 -->

<!-- START_acd3b9d39a95869aec083f2110c00a05 -->
## api/v1/h5p/ajax/library-upload
> Example request:

```bash
curl -X POST \
    "http://localhost:8000/api/v1/h5p/ajax/library-upload" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/library-upload"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/h5p/ajax/library-upload`


<!-- END_acd3b9d39a95869aec083f2110c00a05 -->

<!-- START_cda7f0d7113622786e742c924e982fcb -->
## api/v1/h5p/ajax/files
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/files" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/files"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/files`

`POST api/v1/h5p/ajax/files`

`PUT api/v1/h5p/ajax/files`

`PATCH api/v1/h5p/ajax/files`

`DELETE api/v1/h5p/ajax/files`

`OPTIONS api/v1/h5p/ajax/files`


<!-- END_cda7f0d7113622786e742c924e982fcb -->

<!-- START_c16715cbda021553cbf0c71e70259255 -->
## api/v1/h5p/ajax/filter
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/filter" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/filter"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/filter`

`POST api/v1/h5p/ajax/filter`

`PUT api/v1/h5p/ajax/filter`

`PATCH api/v1/h5p/ajax/filter`

`DELETE api/v1/h5p/ajax/filter`

`OPTIONS api/v1/h5p/ajax/filter`


<!-- END_c16715cbda021553cbf0c71e70259255 -->

<!-- START_8d849db0464b929d8d0e644cfedb8ae1 -->
## api/v1/h5p/ajax/finish
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/finish" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/finish"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/finish`

`POST api/v1/h5p/ajax/finish`

`PUT api/v1/h5p/ajax/finish`

`PATCH api/v1/h5p/ajax/finish`

`DELETE api/v1/h5p/ajax/finish`

`OPTIONS api/v1/h5p/ajax/finish`


<!-- END_8d849db0464b929d8d0e644cfedb8ae1 -->

<!-- START_9c501aa8cbd92faa887f97859dab467f -->
## api/v1/h5p/ajax/content-user-data
> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/h5p/ajax/content-user-data" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/h5p/ajax/content-user-data"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/h5p/ajax/content-user-data`

`POST api/v1/h5p/ajax/content-user-data`

`PUT api/v1/h5p/ajax/content-user-data`

`PATCH api/v1/h5p/ajax/content-user-data`

`DELETE api/v1/h5p/ajax/content-user-data`

`OPTIONS api/v1/h5p/ajax/content-user-data`


<!-- END_9c501aa8cbd92faa887f97859dab467f -->

<!-- START_b455a0ec0acd6c04cdc310209814381d -->
## Display error

> Example request:

```bash
curl -X GET \
    -G "http://localhost:8000/api/v1/error" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost:8000/api/v1/error"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "errors": [
        "Unauthorized."
    ]
}
```

### HTTP Request
`GET api/v1/error`


<!-- END_b455a0ec0acd6c04cdc310209814381d -->


