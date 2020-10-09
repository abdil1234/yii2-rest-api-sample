# Yii2 example crud api with redis (Service - repository pattern)
# Installation
* git clone repo-url
* composer update
* php init (Dev)
* adjust db at common/main-local.php 
* adjust component redis at backend/main.php 
* php yii migrate
* php yii migrate --migrationPath=@yii/rbac/migrations
* php yii rbac/init
 
# Configuration
* document roots of your web server:
 For Apache it could be the following:

   ```apache
       <VirtualHost *:80>
           ServerName rest-rbac.test
           DocumentRoot "/path/to/yii-application/backend/web/"
           
           <Directory "/path/to/yii-application/backend/web/">
               # use mod_rewrite for pretty URL support
               RewriteEngine on
               # If a directory or a file exists, use the request directly
               RewriteCond %{REQUEST_FILENAME} !-f
               RewriteCond %{REQUEST_FILENAME} !-d
               # Otherwise forward the request to index.php
               RewriteRule . index.php

               # use index.php as index file
               DirectoryIndex index.php

               # ...other settings...
               # Apache 2.4
               Require all granted
               
               ## Apache 2.2
               # Order allow,deny
               # Allow from all
           </Directory>
       </VirtualHost>
       
   ```

   For nginx:

   ```nginx
       server {
           charset utf-8;
           client_max_body_size 128M;

           listen 80; ## listen for ipv4
           #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

           server_name rest-rbac.test;
           root        /path/to/yii-application/backend/web/;
           index       index.php;

           access_log  /path/to/yii-application/log/backend-access.log;
           error_log   /path/to/yii-application/log/backend-error.log;

           location / {
               # Redirect everything that isn't a real file to index.php
               try_files $uri $uri/ /index.php$is_args$args;
           }

           # uncomment to avoid processing of calls to non-existing static files by Yii
           #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
           #    try_files $uri =404;
           #}
           #error_page 404 /404.html;

           # deny accessing php files for the /assets directory
           location ~ ^/assets/.*\.php$ {
               deny all;
           }

           location ~ \.php$ {
               include fastcgi_params;
               fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
               fastcgi_pass 127.0.0.1:9000;
               #fastcgi_pass unix:/var/run/php5-fpm.sock;
               try_files $uri =404;
           }
       
           location ~* /\. {
               deny all;
           }
       }
        
   ```
   
 * Change the hosts file to point the domain to your server.

   - Windows: `c:\Windows\System32\Drivers\etc\hosts`
   - Linux: `/etc/hosts`

   Add the following lines:

   ```
   127.0.0.1   rest-rbac.test
   ```
# Register
    POST http://rest-rbac.test/v1/user/register
    
Register new user

#### Request Body
```json
{
    "username": "user",
    "email": "user1@gmail.com",
    "password": "1234567890"
}
```

### Response (201)
``` json
{
    "username": "user",
    "email": "user1@gmail.com",
    "access_token": "1234567890"
}
```

# Login
    POST http://rest-rbac.test/v1/user/login
    

Login user

#### Request Body
```json
{
    "username": "user",
    "password": "1234567890"
}
```

### Response (200)
``` json
{
    "id": 10,
    "email": "user1@gmail.com",
    "username": "user",
    "status": 10
}
```
# Authentication
Make sure every request to book endpoint set header Authorization: Bearer <token>

# Authorization
* admin -> create, update,index, view,delete
* user -> index, view

# Redis Scenario
* save data with tags `cache_table_book` to redis db when user hit view and index endpoint with 10 second expired
* reset cache with tags `cache_table_book` after user hit create, update, delete endpoint


# Book List

    GET http://rest-rbac.test/v1/book/index
    
Returns a list of [Book][] the current authorized user has access to
### Parameters

#### Pagination a book based on a request: `http://rest-rbac.test/v1/book/book/index?page=:offset&per-page=:limit`
#### Filter a book based on a request: `http://rest-rbac.test/v1/book/book/index?filter[:column][like]=book1`
#### Selecting a book based on a request: `http://rest-rbac.test/v1/book/book/index?fields=:column1, :column2`
#### Sorting a book based on a request: `http://rest-rbac.test/v1/book/book/index?sort= -:column1`

## Example
### Request

    http://rest-rbac.test/v1/book/index
    http://rest-rbac.test/v1/book/index?page=1&per-page=10
    http://rest-rbac.test/v1/book/index?filter[title][like]=book 1&filter[author]=naruto
    http://rest-rbac.test/v1/book/index?fields=id,author
    http://rest-rbac.test/v1/book/index?fields=id,author
    http://rest-rbac.test/v1/book/index?sort=-id

### Response(200)
``` json
{
    "data": [
        {
            "id": 12,
            "title": "book 1",
            "description": "lorem ipsum donor",
            "author": "naruto",
            "_links": {
                "self": {
                    "href": "http://rest-rbac.test/v1/book/view?id=12"
                },
                "update": {
                    "href": "http://rest-rbac.test/v1/book/update?id=12"
                },
                "delete": {
                    "href": "http://rest-rbac.test/v1/book/delete?id=12"
                },
                "index": {
                    "href": "http://rest-rbac.test/v1/book/index"
                }
            }
        },
        {
            "id": 13,
            "title": "book 2",
            "description": "lorem ipsum donor",
            "author": "sasuke",
            "_links": {
                "self": {
                    "href": "http://rest-rbac.test/v1/book/view?id=13"
                },
                "update": {
                    "href": "http://rest-rbac.test/v1/book/update?id=13"
                },
                "delete": {
                    "href": "http://rest-rbac.test/v1/book/delete?id=13"
                },
                "index": {
                    "href": "http://rest-rbac.test/v1/book/index"
                }
            }
        }
    ],
    "_links": {
        "self": {
            "href": "http://rest-rbac.test/v1/book/index?page=1"
        }
    },
    "_meta": {
        "totalCount": 2,
        "pageCount": 1,
        "currentPage": 1,
        "perPage": 20
    }
}
```

# Book view

    GET http://rest-rbac.test/v1/book/view?id=:id
    
Get book detail authorized user has access to
## Example
### Request

    GET http://rest-rbac.test/v1/book/view?id=16
```

### Response (201)
``` json
{
    "id": 16,
    "title": "book 3",
    "description": "lorem ipsum amec donor",
    "author": "sarada",
    "_links": {
        "self": {
            "href": "http://rest-rbac.test/v1/book/view?id=16"
        },
        "update": {
            "href": "http://rest-rbac.test/v1/book/update?id=16"
        },
        "delete": {
            "href": "http://rest-rbac.test/v1/book/delete?id=16"
        },
        "index": {
            "href": "http://rest-rbac.test/v1/book/index"
        }
    }
}
```
# Book create

    POST http://rest-rbac.test/v1/book/create
    
Create new Book base on the current authorized user has access to
## Example
### Request

    POST http://rest-rbac.test/v1/book/create
#### Request Body
```json
{
    "title": "book 3",
    "author": "sarada",
    "description": "lorem ipsum amec donor"
}
```

### Response (201)
``` json
{
    "id": 16,
    "title": "book 3",
    "description": "lorem ipsum amec donor",
    "author": "sarada",
    "_links": {
        "self": {
            "href": "http://rest-rbac.test/v1/book/view?id=16"
        },
        "update": {
            "href": "http://rest-rbac.test/v1/book/update?id=16"
        },
        "delete": {
            "href": "http://rest-rbac.test/v1/book/delete?id=16"
        },
        "index": {
            "href": "http://rest-rbac.test/v1/book/index"
        }
    }
}
```

# Book update
    PUT, PATCH http://rest-rbac.test/v1/book/update?id=:id
Update Book base on book id the current authorized user has access to
## Example
### Request

    PUT, PATCH http://rest-rbac.test/v1/book/update?id=13
#### Request Body
```json
{
    "title": "book 3 update",
    "author": "lorem ipsum amec donor update",
    "description": "sarada"
}
```

### Response(200)
``` json
{
    "id": 16,
    "title": "book 3 update",
    "description": "sarada",
    "author": "lorem ipsum amec donor update",
    "_links": {
        "self": {
            "href": "http://rest-rbac.test/v1/book/view?id=16"
        },
        "update": {
            "href": "http://rest-rbac.test/v1/book/update?id=16"
        },
        "delete": {
            "href": "http://rest-rbac.test/v1/book/delete?id=16"
        },
        "index": {
            "href": "http://rest-rbac.test/v1/book/index"
        }
    }
}
```

# Book delete
    DELETE http://rest-rbac.test/v1/book/delete?id=:id
Update Book base on book id the current authorized user has access to
## Example
### Request

    DELETE http://rest-rbac.test/v1/book/delete?id=13

### Response (204)
No content