# Get company category
Get company category for a given country

**Url:** ```/admin/api/company/category/{countryId}```

**Method:** GET

**Authentification** True

**Permission** ROLE_USER

## Request Data

* countryId : int

## Response

```json
[
    {
        "id": 123,
        "abbreviation": "SARL",
        "name": "Société à Responsabilité Limitée au Luxembourg"
    }, {
        "id": 145,
        "abbreviation": "SA",
        "name": "Société anonyme"
    }
]
```