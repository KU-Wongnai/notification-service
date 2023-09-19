# KU Wongnai - Notification Service

Email notificaion API and In-App notification

## Setup

Run the following command to install the dependencies

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

Copy `.env.example` to `.env`

```sh
cp .env.example .env
```

For local development, you can use Mailpit to test sending emails. You can go to http://localhost:8025 to see the sent emails.

```
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Make sure you have the network called `ku-wongnai_ku-wongnai` this use for connect to RabbitMQ

Run the following command to start the service

```sh
sail up -d
```

Run the following command to run migration and seed data. Which will create sample users with email to test sending email.

```sh
sail artisan migrate:fresh --seed
```

Run the following command to start the consume message from RabbitMQ

```sh
sail artisan rabbitmq:consume
```

> If some of service can't be run make sure that the port you are using is not used by other service.

## API

You can used Postman to test the API.

### Example Email Notification

See the result at mailtrap.io or in local development you can go to http://localhost:8025

> GET -> /welcome/user/email/{toEmail}

### Example In-App Notification

> GET -> localhost/api/user/noti/welcome/{id}

```json
{
    "id": "cde53269-46ff-4154-b279-c34d7fb3f84b",
    "type": "App\\Notifications\\WelcomeNewUser",
    "notifiable_type": "App\\Models\\User",
    "notifiable_id": 1,
    "data": "Welcome to KU Wongnai. Share your favorite food with our community and inspire others.",
    "read_at": null,
    "created_at": "2023-09-11T12:43:39.000000Z",
    "updated_at": "2023-09-11T12:43:39.000000Z"
}
```
