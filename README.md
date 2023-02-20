# Restaurant Ordering System
This is a basic implementation of a restaurant ordering system, built using Laravel 8. The system includes the ability to create and manage products, ingredients, and orders, and to check stock levels and fulfill orders. It also includes the ability to send emails to customers regarding order status.

## Setup on local

To run this application, you will need:

- Clone the repo
- Install docker and docker compose
- At root of project run `docker comopose build`
- Enter into docker using command `docker exec -it foodic-php bash`
- Run following commands
  -- `composer install`
- Sample `.env` file
```env
APP_NAME=foodic
APP_ENV=local
APP_KEY=base64:Qjx7veFu4p7CdhJyAxZCbBKSXh5C1YHSxz0Ai250qSk=
APP_DEBUG=true
APP_URL=http://localhost
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=foodic_db
DB_USERNAME=foodic_user
DB_PASSWORD=foodic_password
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
MEMCACHED_HOST=127.0.0.1
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@foodics.com"
MAIL_ADMIN_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```
## Further Commands
- Login to docker using the same command as above.
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan test --filter=OrderTest`

## End Point
- `http://localhost:8100/api/v1/order`
- Request body:
```json
{
    "user_id": 1,
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```
- Header
```json
"Content-Type": "application/json"
"Accept": "application/json"
```
- Success Response
```json
{
    "message":"Order created successfully",
    "order": {
    "id":1,
    "total_price":21.98,
    "user_id":1,
    "updated_at":"2023-02-20T20:39:15.000000Z",
    "created_at":"2023-02-20T20:39:15.000000Z",
    }
}
```
- Error Response
```json
{
    "message":"Error creating order",
    "error":"Not enough stock for ingredient Beef"
}
````
## License
This project is open-source software licensed under the MIT license.
