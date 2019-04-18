# Get project stat
Get statistics for one project.

**Url:** ```/admin/api/project/stat/{id}```

**Method:** GET

**Authentification** True

**Permission** ROLE_USER

## Request Data

* id : int

## Response

```json
{
  "lots": 30,
  "state": [
      {
          "name": "Option",
          "date": "datetime object",
          "quantity": 30
      }, {
          "name": "Vente",
          "date": "datetime object",
          "quantity": 11
      }
  ],
  "step": {
      "study": "datetime object",
      "mastery": "datetime object",
      "permitStart": "datetime object",
      "permitEnd": "datetime object",
      "worksStart": "datetime object",
      "worksEnd": "datetime object",
      "delivery": "datetime object"
  }
}
```