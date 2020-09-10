## Простой чат на Vue.js и PHP

Имя пользователя сохраняется в `localStorage`.

Отправка сообщений происходит через `send.php`.

Получение истории сообщений происходит через `history.php`.

Для обновления истории чата используется запрос по таймеру каждые 10 секунд.

### Docker

```sh
cp .env.example .env
docker-compose up -d --build
```

### База данных (MySQL)

По адресу [localhost:8080](http://localhost:8080) будет доступен **phpMyAdmin**.

```sql
CREATE TABLE IF NOT EXISTS message (
  id INTEGER AUTO_INCREMENT PRIMARY KEY,
  sender VARCHAR(80) NOT NULL,
  text VARCHAR(140) NOT NULL
) engine=InnoDB;
```

### TODO:

- Попробовать сделать сервер на WebSocket:
	- [Swoole](https://www.swoole.co.uk)
  - [Ratchet](http://socketo.me)
- Сделать нормальный UI
