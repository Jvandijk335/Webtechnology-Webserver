# WebTech Project 

This repository contains a Docker compose file that will set up 4 services. It sets up Caddy, PHP-fpm, Postgres, and adminer. 

## How to use

To start up the stack, run the following.

```
$ git clone git@github.com:bryanhonof/webtech-compose.git
$ cd webtech-compose
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

### Temperature API

### `GET /api/temperature`

Retrieve temperature records, optionally filtered by a datetime range. 

- Query parameters:
    - `begin` (optional): Start datetime (inclusive), e.g. `2025-05-22 15:36:00`
    - `end` (optional): End datetime (inclusive). Defaults to urrent server time if not provided.
- Limits results to 50 records. 

#### Example Requests:

Retrieve last 50 readings:

```bash
$ curl http://localhost:80/api/temperature
```

Retrieve readings between two timestamps:
```bash
$ curl 'http://localhost:80/api/temperature?begin=2025-05-22%2015:36&end=2025-05-22%2015:40'
```

#### Response:
```json
[
  {"id":2,"value":50,"datetime":"2025-05-22 15:36:15.104792+02"},
  {"id":3,"value":50,"datetime":"2025-05-22 15:36:15.909615+02"}
]
```

### `GET /api/temperature/<id>`

Get a single temperature record by its unique ID.

### Highscore API

### `GET /api/highscores`
Fetch the top 10 hgihscores, ordered by descending by score and ascending by creation time. 

#### Example request:

```bash
$ curl http://localhost:80/api/highscores
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