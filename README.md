# CheckInLite Backend

## Getting Started

1. Install package dependencies

    ```bash
    $ cd src && composer install
    # or
    $ composer install --working-dir src
    ```

2. Start up the docker compose

    ```bash
    $ docker-compose up -d
    ```

    - Execute this bash, if contains main-branch php image file
        ```bash
        # clean old php image file => old php image trigger connection refuse error
        $ docker-compose up --build
        ```

3. Modify `src/storage` permission to 777
    - docker container need to write log to volumn folder


4. Migrate database


    ```bash
    $ php artisan migrate
    ```

5. Test Auth API
    - Setup step ( install jwt package )
        - `composer install`
    - Register API
        - [POST] http:localhost:8080/api/auth/register
            ```bash
            # post data
            name:example
            email:example@gmail.com
            password:qwer1234
            password_confirmation:qwer1234
            ```


    - Login API ( 目前尚未加入 email 認證完才可以登入 )
        - [POST] http:localhost:8080/api/auth/login
            ```bash
            # post data
            email:example@gmail.com
            password:qwer1234
            ```