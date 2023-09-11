# KU Wongnai - Notification Service

Email notificaion API and In-App notification

## Setup

Run the following command

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

```
sail up -d
```

```
sail artisan migrate:fresh --seed
```

## API

can test at Postman


### example Email notification

can get email result at mailtrap.io

> GET -> /welcome/user/email/{toEmail}



### example In-App notification

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