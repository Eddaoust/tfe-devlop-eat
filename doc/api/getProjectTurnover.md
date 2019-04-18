# Get project turnover
Get the turnover for recent projects

**Url:** ```/admin/api/project/turnover```

**Method:** GET

**Authentification** True

**Permission** ROLE_USER

## Response

```json
[
    {
        "name": "Devaux",
        "turnover": "875234",
        "created": "2018-09-13 10:21:28"
    }, {
        "name": "Millesimo",
        "turnover": "12890",
        "created": "2017-05-10 19:09:04"
    }, {
        ...
    }
]
```