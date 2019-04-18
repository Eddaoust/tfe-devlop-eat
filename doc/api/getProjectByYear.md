# Get project by year
Get the count of projects by year

**Url:** ```/admin/api/project/year```

**Method:** GET

**Authentification** True

**Permission** ROLE_ADMIN

## Response

```json
[
    {
        "projectCount": 6,
        "year": "2010"
    }, {
        "projectCount": 18,
        "year": "2011"
    }, {
        ...
    }
]
```