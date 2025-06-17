# WebTech Project 

This repository contains a Docker compose file that will set up 4 services. It sets up Caddy, PHP-fpm, Postgres, and adminer. 

## How to use

Download and run Docker

To start up the stack, run the following.

```
$ git clone git@github.com:Jvandijk335/Webtechnology-Webserver.git
$ docker compose up
```

Once the stack is up and running, we can see if a connection to the
database happened successfully by trying the following. This command
should return the version of PostgresQL.

```console
$ curl http://localhost:80/api/db-status
"Connection OK; waiting to send."%
```

You can also go to http://localhost:8080 yourself, and see the webapplication (game) appear. 

## Webpages

| Webpage                  |  Directory                      | Description                                          |
| ---------                | -------------------------       | ---------------------------------------------------- |
| localhost                | src/index.html                  | This opens the webgame                               |
| localhost/leaderboard    | src/lib/views/leaderboard.php   | Shows the current leaderboard                        |
| localhost/about          | src/lib/views/project.php       | Shows the website that gives our project description |
| localhost/index          | src/lib/views/index.php         | Returns "Hello World!"                               |
| localhost/<any>          | src/lib/views/404.php           | Any other url return "404"                           |

## RESTful API

his API provides endpoints for managing temperature readings and highscores, backed by a PostgreSQL database. The API supports CRUD operations with JSON data interchange, using a custom PHP router. [src/routes.php](src/routes.php).

## Base URL

All API endpoints are relative to the server root, e.g., `http://localhost:80`.

---

## Available Endpoints

### Database Status

### `GET /api/db-status`

Returns the connection status of the PostgreSQL database.

**Example request:**
```bash
curl http://localhost:80/api/db-status
```

**Response:**
```bash
"Connection OK; waiting to send"
```

---

### Highscore API

### `GET /api/highscores`
Fetches the top 50 highscores, sorted by score (descending) by default
Supports optional query parameters to customize sorting.

#### Optional Query Parameters:

| Parameter  |  Value                                  | Description                                             |
| ---------  | --------------------------------------- | ------------------------------------------------------- |
| `$sort_by` | `id`, `username`, `score`, `created_at` | Choose the column to sort by.                           |
| `$order`   | `asc`, `desc`                           | Specify ascending (`asc`) or descending (`desc`) order. |


#### Example request:

##### Default top 50:

```bash
$ curl http://localhost:80/api/highscores
```

##### Sorted by date (Newest first):

```bash
curl "http://localhost:80/api/highscores?sort_by=created_at&order=desc"
```

##### Sorted by name (A-Z):

```bash
curl "http://localhost:80/api/highscores?sort_by=username&order=asc"
```

#### Response:
```json
[
  {"id":1,"username":"player1","score":1000,"created_at":"2025-05-22 12:00:00"},
  {"id":2,"username":"player2","score":950,"created_at":"2025-05-22 12:05:00"},
  ...
]
```

### `GET /api/highscores/<id>`a
Fetch a single highscore entry by its ID.

#### Example request:
```bash
$ curl http://localhost:80/api/highscores/3
```

#### Response:
```json
[
  {"id":3,"username":"player3","score":900,"created_at":"2025-05-22 13:00:00"}
]
```

### `POST /api/highscores`
Create a new highscore entry

- Request body (JSON):
```json
{
  "username": "playerX",
  "score": 1234
}
```
- Validation:
    - `username` must be provided.
    - `score` must be numeric.
    - If invalid, returns 400 error with JSON error message. 

#### Example request:
```bash
$ curl -H "Content-Type: application/json" -X POST http://localhost:80/api/highscores -d '{"username":"playerX","score":1234}' 
```

#### Response:
```json
[
  {"id":15,"username":"playerX","score":1234,"created_at":"2025-06-16 10:00:00"}
]
```

### `PUT /api/highscores/<id>`

Update the username and score of an existing highscore record (full update).
- Request body same as POST.

#### Example request:
```bash
$ curl -X PUT http://localhost:80/api/highscores 15 -d '{"username":"playerX","score":1500}'
```

#### Response:
```json
[
  {"id":15,"username":"playerY","score":1500,"created_at":"2025-06-16 10:00:00"}
]
```

### `PATCH /api/highscores/<id>`

Partial update of highscore entry (same payload and response as PUT).

### DELETE /api/highscore/<id>
Delete a highscore entry by its ID.

#### Example request:
```bash
$ curl -X DELETE http://localhost:80/api/highscores/15
```

#### Response:
```json
[
  {"id":15,"username":"playerY","score":1500,"created_at":"2025-06-16 10:00:00"}
]
```

### Additional Routes

- `GET /index` — serves [src/lib/views/index.php](src/lib/views/index.php)
- `GET /leaderboard` — serves a leaderboard page. 
- `ANY /404` — serves a 404 error page.

## Tree overview
```bash
.
├── compose.yaml
├── docker
│   ├── Caddyfile
│   ├── docker-entrypoint-initdb.d
│   │   ├── 01-init-webtech-db.sql
│   │   └── 02-init-webtech-table.sql
│   └── php-with-pgsql.Dockerfile
├── LICENSE
├── README.md
└── src
    ├── Build
    │   ├── WebTech_Build.data
    │   ├── WebTech_Build.framework.js
    │   ├── WebTech_Build.loader.js
    │   └── WebTech_Build.wasm
    ├── dependencies.txt
    ├── GUID.txt
    ├── index.html
    ├── lib
    │   ├── lib.php
    │   ├── router.php
    │   └── views
    │       ├── 404.php
    │       ├── index.php
    │       ├── leaderboard.php
    │       └── project.php
    ├── ProjectVersion.txt
    ├── routes.php
    ├── StreamingAssets
    │   ├── Ambient.bank
    │   ├── Master.bank
    │   ├── Master.strings.bank
    │   ├── Music.bank
    │   └── SFX.bank
    └── TemplateData
        ├── favicon.ico
        ├── fullscreen-button.png
        ├── progress-bar-empty-dark.png
        ├── progress-bar-empty-light.png
        ├── progress-bar-full-dark.png
        ├── progress-bar-full-light.png
        ├── style.css
        ├── unity-logo-dark.png
        ├── unity-logo-light.png
        └── webgl-logo.png

9 directories, 37 files
```