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
[Get Postman Collection](http://currikiapi.localhost/docs/collection.json)

<!-- END_INFO -->

#Google Classroom


<!-- START_e1403a4791b2f9419c8bb0bea0c91ceb -->
## Save Access Token

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Save GAPI access token in the database.

> Example request:

```bash
curl -X POST \
    "http://currikiapi.localhost/api/v1/google-classroom/access-token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"access_token":"est"}'

```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/google-classroom/access-token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "access_token": "est"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": "Access Token Saved successfully"
}
```
> Example response (500):

```json
{
    "errors": "Validation error: Access token is required"
}
```
> Example response (500):

```json
{
    "errors": "Failed to save the token."
}
```

### HTTP Request
`POST api/v1/google-classroom/access-token`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `access_token` | string |  required  | The stringified JSON of the GAPI access token object
    
<!-- END_e1403a4791b2f9419c8bb0bea0c91ceb -->

<!-- START_679513c6d9a3e7633325d5e35620d09d -->
## Get Courses

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Get all existing Google Classroom Courses

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/api/v1/google-classroom/courses" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/google-classroom/courses"
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
    "errors": "Service exception error"
}
```
> Example response (200):

```json
{
    "status": "success",
    "data": [
        {
            "id": "1",
            "name": "Course 1"
        },
        {
            "id": "2",
            "name": "Course 2"
        },
        {
            "id": "3",
            "name": "Course 3"
        }
    ]
}
```

### HTTP Request
`GET api/v1/google-classroom/courses`


<!-- END_679513c6d9a3e7633325d5e35620d09d -->

<!-- START_3f6177f199294bd05304ded78263e7fc -->
## Copy project to Google Classroom

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Copy whole project to google classroom either as a new course
or into an existing course.

> Example request:

```bash
curl -X POST \
    "http://currikiapi.localhost/api/v1/google-classroom/projects/9/copy" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"course_id":"123"}'

```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/google-classroom/projects/9/copy"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "course_id": "123"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (403):

```json
{
    "errors": "Forbidden. You are trying to share other user's project."
}
```
> Example response (500):

```json
{
    "errors": "Failed to save the token."
}
```
> Example response (200):

```json
{
    "data": {
        "course": {
            "id": "151999547900",
            "name": "How to build a playlist in CurrikiStudio",
            "topics": [
                {
                    "course_id": "151999547900",
                    "topic_id": "152025014649",
                    "name": "Development Setup",
                    "course_work": [
                        {
                            "id": "152023207873",
                            "course_id": "151999547900",
                            "description": null,
                            "topic_id": "152025014649",
                            "title": "Activity B - 0",
                            "state": "PUBLISHED",
                            "work_type": "ASSIGNMENT",
                            "materials": [
                                {
                                    "link": {
                                        "thumbnailUrl": null,
                                        "title": null,
                                        "url": "http:\/\/localhost:8082\/shared\/activity\/12"
                                    }
                                }
                            ],
                            "max_points": null
                        }
                    ]
                },
                {
                    "course_id": "151999547900",
                    "topic_id": "152020569111",
                    "name": "Building Conent Type",
                    "course_work": [
                        {
                            "id": "152022313831",
                            "course_id": "151999547900",
                            "description": null,
                            "topic_id": "152020569111",
                            "title": "Activity B - 0",
                            "state": "PUBLISHED",
                            "work_type": "ASSIGNMENT",
                            "materials": [
                                {
                                    "link": {
                                        "thumbnailUrl": null,
                                        "title": null,
                                        "url": "http:\/\/localhost:8082\/shared\/activity\/17"
                                    }
                                }
                            ],
                            "max_points": null
                        },
                        {
                            "id": "152004039772",
                            "course_id": "151999547900",
                            "description": null,
                            "topic_id": "152020569111",
                            "title": "Activity B - 2",
                            "state": "PUBLISHED",
                            "work_type": "ASSIGNMENT",
                            "materials": [
                                {
                                    "link": {
                                        "thumbnailUrl": null,
                                        "title": null,
                                        "url": "http:\/\/localhost:8082\/shared\/activity\/19"
                                    }
                                }
                            ],
                            "max_points": null
                        }
                    ]
                },
                {
                    "course_id": "151999547900",
                    "topic_id": "152002276754",
                    "name": "This is Week 3",
                    "course_work": []
                }
            ]
        }
    },
    "status": "success"
}
```

### HTTP Request
`POST api/v1/google-classroom/projects/{project}/copy`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `project` |  required  | The ID of the project.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `course_id` | string |  optional  | ID of an existing Google Classroom course.
    
<!-- END_3f6177f199294bd05304ded78263e7fc -->

#general


<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/authorize"
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
    "http://currikiapi.localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/authorize"
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
    "http://currikiapi.localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/authorize"
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
    "http://currikiapi.localhost/oauth/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/token"
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
    -G "http://currikiapi.localhost/oauth/tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/tokens"
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
    "http://currikiapi.localhost/oauth/tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/tokens/1"
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
    "http://currikiapi.localhost/oauth/token/refresh" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/token/refresh"
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
    -G "http://currikiapi.localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/clients"
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
    "http://currikiapi.localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/clients"
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
    "http://currikiapi.localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/clients/1"
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
    "http://currikiapi.localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/clients/1"
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
    -G "http://currikiapi.localhost/oauth/scopes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/scopes"
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
    -G "http://currikiapi.localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/personal-access-tokens"
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
    "http://currikiapi.localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/personal-access-tokens"
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
    "http://currikiapi.localhost/oauth/personal-access-tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/oauth/personal-access-tokens/1"
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
    "http://currikiapi.localhost/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/register"
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
    "http://currikiapi.localhost/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/login"
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
    "http://currikiapi.localhost/api/forgot-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/forgot-password"
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
    "http://currikiapi.localhost/api/reset-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/reset-password"
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
    "http://currikiapi.localhost/api/verify-email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/verify-email"
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
    "http://currikiapi.localhost/api/verify-email/resend" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/verify-email/resend"
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
    "http://currikiapi.localhost/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/logout"
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

<!-- START_dfb8fbb6facf7c68af221b2363bceb9e -->
## Display the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/api/v1/users/me" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/users/me"
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
    -G "http://currikiapi.localhost/api/v1/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/users"
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
    "http://currikiapi.localhost/api/v1/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/users"
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
    -G "http://currikiapi.localhost/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/users/1"
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
    "http://currikiapi.localhost/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/users/1"
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
    "http://currikiapi.localhost/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/users/1"
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

<!-- START_57b4a66cc415c7809e03a83e2dfa7039 -->
## Upload thumb image for project

> Example request:

```bash
curl -X POST \
    "http://currikiapi.localhost/api/v1/projects/upload-thumb" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/upload-thumb"
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
    "http://currikiapi.localhost/api/v1/projects/1/share-project" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/share-project"
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

<!-- START_4824704072d555009a2b09cfc7ed57a9 -->
## Remove share specified project.

> Example request:

```bash
curl -X POST \
    "http://currikiapi.localhost/api/v1/projects/1/remove-share-project" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/remove-share-project"
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
    -G "http://currikiapi.localhost/api/v1/projects" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects"
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
    "http://currikiapi.localhost/api/v1/projects" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects"
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
    -G "http://currikiapi.localhost/api/v1/projects/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1"
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
    "http://currikiapi.localhost/api/v1/projects/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1"
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
    "http://currikiapi.localhost/api/v1/projects/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1"
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

<!-- START_04d0433e8d1e16965dac2606ac067416 -->
## Display a listing of the playlist.

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/api/v1/projects/1/playlists" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/playlists"
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
    "http://currikiapi.localhost/api/v1/projects/1/playlists" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/playlists"
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
    -G "http://currikiapi.localhost/api/v1/projects/1/playlists/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/playlists/1"
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
    "http://currikiapi.localhost/api/v1/projects/1/playlists/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/playlists/1"
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
    "http://currikiapi.localhost/api/v1/projects/1/playlists/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/projects/1/playlists/1"
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

<!-- START_c238d6cdfdd62f3aae5a627422fe3427 -->
## Display a listing of the activity.

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/api/v1/playlists/1/activities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/playlists/1/activities"
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
`GET api/v1/playlists/{playlist}/activities`


<!-- END_c238d6cdfdd62f3aae5a627422fe3427 -->

<!-- START_2a7a430e931750bac16a4f433def76c7 -->
## Store a newly created activity in storage.

> Example request:

```bash
curl -X POST \
    "http://currikiapi.localhost/api/v1/playlists/1/activities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/playlists/1/activities"
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
`POST api/v1/playlists/{playlist}/activities`


<!-- END_2a7a430e931750bac16a4f433def76c7 -->

<!-- START_33a2e7decc039b04e96d21e0fae0e6b1 -->
## Display the specified activity.

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/api/v1/playlists/1/activities/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/playlists/1/activities/1"
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
`GET api/v1/playlists/{playlist}/activities/{activity}`


<!-- END_33a2e7decc039b04e96d21e0fae0e6b1 -->

<!-- START_2c284aa499dc018e9876b34be26974fe -->
## Update the specified activity in storage.

> Example request:

```bash
curl -X PUT \
    "http://currikiapi.localhost/api/v1/playlists/1/activities/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/playlists/1/activities/1"
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
`PUT api/v1/playlists/{playlist}/activities/{activity}`

`PATCH api/v1/playlists/{playlist}/activities/{activity}`


<!-- END_2c284aa499dc018e9876b34be26974fe -->

<!-- START_4b91bf4495723f1f78bc403ac627bdaf -->
## Remove the specified activity from storage.

> Example request:

```bash
curl -X DELETE \
    "http://currikiapi.localhost/api/v1/playlists/1/activities/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/playlists/1/activities/1"
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
`DELETE api/v1/playlists/{playlist}/activities/{activity}`


<!-- END_4b91bf4495723f1f78bc403ac627bdaf -->

<!-- START_b455a0ec0acd6c04cdc310209814381d -->
## Display error

> Example request:

```bash
curl -X GET \
    -G "http://currikiapi.localhost/api/v1/error" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://currikiapi.localhost/api/v1/error"
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


