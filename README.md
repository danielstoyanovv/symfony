#Setup Instructions

###System Requirements
- PHP 7.3
- Symfony 5.4.5
- MySQL 5.7
- Node.js
- NPM
- Composer
- Web server: Apache

###Installation Steps
- run php bin\console doctrine:database:drop --force
- run php bin\console doctrine:database:create
- run php bin\console doctrine:schema:update --force
- run php bin\console doctrine:fixtures:load

###Project's integrations
- RabbitMQ integration:
    config/packages/messenger.yaml

- Webpack integration:
  config/packages/webpack_encore.yaml

- Stimulus javascript controllers integration:
  assets/controllers/*

- Redis cache server integration:
    config/packages/cache.yaml

- Paypal payment integration:
    src/Service/Paypal.php
    src/Service/Payment/Paypal.php
    src/Controller/PaypalController.php

- Stripe payment integration:
    src/Service/Stripe.php
    src/Service/Payment/Stripe.php
    src/Controller/StripeController.php
 
- Checkout cart integration:
    src/Service/CartProvider.php
    src/Controller/CartController.php

- Checkout integration:
    src/Controller/CheckoutController.php

- Api log integration:
    src/Service/ApiLogProvider.php

- Send email integration:
    src/Service/SendWithTemplate.php

- Uploader integration:
    src/Service/Uploader.php

- Admin panel integration:
    src/Controller/Admin/*

- Profile integration:
    src/Controller/User/ProfileController.php

- Php Unit Tests integration:
  tests/*

- Token Generator for rest api calls
  src/Service/TokenGenerator.php

- Custom authenticator integration:
    src/Security/ApiKeyAuthenticator.php

- Rest api integration (based on ApiPlatform):
     
    curl -X 'POST' 
    'YOUR_SITE_URL/api/api_tokens' 
    -H 'accept: application/ld+json' 
    -H 'Content-Type: application/ld+json' 
    -d '{
    "email": "string",
    "password": "string"
    }'

    code implementation: src/DataPersister/ApiTokenDataPersister.php

    curl -X 'GET' 
    'YOUR_SITE_URL/api/articles' 
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN' 

    curl -X 'GET'
    'YOUR_SITE_URL/api/articles?isActive=true'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'

    curl -X 'GET'
    'YOUR_SITE_URL/api/articles?createdAt%5Bbefore%5D=2023-04-17%2011%3A00%3A00&createdAt%5Bafter%5D=2023-03-17%2011%3A00%3A00'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'

    curl -X 'POST' 
    'YOUR_SITE_URL/api/files'
    -H 'accept: application/ld+json' 
    -H 'Content-Type: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'
    -d '{
    "name": "newImage.jpg",
    "content": should be base64 encoded file content,
    "productName": "Existing Product Name"
    }'

    code implementation: src/DataPersister/FileDataPersister.php

    curl -X 'GET' 
    'YOUR_SITE_URL/api/products'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'

    curl -X 'POST'
    'YOUR_SITE_URL/api/products'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'
    -H 'Content-Type: application/ld+json'
    -d '{
    "name": "string",
    "description": "string",
    "price": 0,
    "position": 0,
    "status": true
    }'

    curl -X 'GET' 
    'YOUR_SITE_URL/api/products/YOUR_PRODUCT_ID'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'

    curl -X 'PUT'
    'YOUR_SITE_URL/api/products/YOUR_PRODUCT_ID'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'
    -H 'Content-Type: application/ld+json'
    -d '{
    "name": "string",
    "description": "string",
    "price": 0,
    "position": 0,
    "status": true
    }'

    curl -X 'DELETE'
    'YOUR_SITE_URL/api/products/YOUR_PRODUCT_ID'
    -H 'accept: */*'
    -H 'X-AUTH-TOKEN: MY_TOKEN'

    curl -X 'PATCH'
    'YOUR_SITE_URL/api/products/YOUR_PRODUCT_ID'
    -H 'accept: application/ld+json'
    -H 'X-AUTH-TOKEN: MY_TOKEN'
    -H 'Content-Type: application/merge-patch+json'
    -d '{
    "name": "string",
    "description": "string",
    "price": 0,
    "position": 0,
    "status": true
    }'


