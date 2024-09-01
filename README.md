# bothub_money

## Последовательность выполнения команд для успешного запуска

Заполните файл `.env`

```shell
cp .example.env .env
```

Заполните файл `config.ini`

```shell
cp example.config.ini config.ini
```

Для локальной разработки запустите:

```shell
docker-compose --env-file .env -f infra/docker-compose.local.yaml up --build
```
