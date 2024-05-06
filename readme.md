# Flexhub [![App build](https://github.com/sonnymilton/flexhub/actions/workflows/app_build.yml/badge.svg)](https://github.com/sonnymilton/flexhub/actions/workflows/app_build.yml)

Flexhub is a private repository of [Symfony Flex recipes](https://symfony.com/doc/current/setup/flex_private_recipes.html)
available for deployment on your infrastructure. Compatible
with [Flex serverless](https://symfony.com/blog/symfony-flex-is-going-serverless).

## Deployment

Flexhub is distributed as a [Docker image](https://hub.docker.com/r/sonnymilton/flexhub), which makes it easy to deploy.

`docker pull sonnymilton/flexhub:latest`

### Important details:

* The application requires PostgreSQL and Redis to run.
* You must set environment variables:
    * **DATABASE_URL** - PostgreSQL database DSN.  
      _Example_: `postgresql://user:password@localhost:5432/flex_server?serverVersion=16&charset=utf8`
    * **REDIS_URL** - Redis server DSN.  
      _Example_:  `redis://localhost:6379`
* Inside the container, the application runs on port 80

<details>
<summary>docker-compose.yml example</summary>

```yaml
services:
    flexhub:
        image: sonnymilton/flexhub:0.1.0
        tty: true
        environment:
            REDIS_URL: redis://redis:6379
            DATABASE_URL: postgresql://postgres:postgres@postgres:5432/flex_server?serverVersion=16&charset=utf8
        ports:
            - "8080:80"
        depends_on:
            - postgres
            - redis

    redis:
        image: eqalpha/keydb:alpine_x86_64_v6.3.4

    postgres:
        image: postgres:16.2-alpine
        environment:
            POSTGRES_DB: flex_server
            POSTGRES_USER: postgres
            POSTGRES_PASSWORD: postgres
```

</details>

<details>
<summary>Kubernetes manifest example</summary>

```yaml
apiVersion: v1
kind: Pod
metadata:
    name: flexhub-demo
spec:
    containers:
        - name: flexhub
          image: sonnymilton/flexhub:latest
          ports:
              - containerPort: 80
          env:
              - name: REDIS_URL
                value: "redis://localhost:6379"
              - name: DATABASE_URL
                value: "postgresql://user:password@localhost:5432/flex_server?serverVersion=16&charset=utf8"

        - name: redis
          image: redis:latest
          ports:
              - containerPort: 6379

        - name: postgres
          image: postgres:16.2
          ports:
              - containerPort: 5432
          env:
              - name: POSTGRES_USER
                value: "user"
              - name: POSTGRES_PASSWORD
                value: "password"
              - name: POSTGRES_DB
                value: "flex_server"

```

</details>

### Configure your composer.json to use your private flex server
```yaml
"extra": {
    "symfony": {
        "endpoint": [
            "https://flexhub.yourhost.lan/api/flex/index.json",
            "flex://defaults"
        ]
    }
}
```
* Replace `https://flexhub.yourhost.lan` with the host on which your flex recipes server is deployed.
* The `extra.symfony` key will most probably already exist in `your composer.json`. In that case, add the `"endpoint"` key to the existing `extra.symfony` entry.



## Development
### Local deployment
`docker-compose up -d`.  
The application runs on port 8000.

### Running code quality tools locally
Use `composer cq` to run PHPstan + php-cs-fixer + phpunit
