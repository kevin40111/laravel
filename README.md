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
    - Setup steps ( install jwt package )
        1. `composer install`
        2. `cp .env src/.env` ( 使用 gdrive 設定權限分享 )
            - Solve Permission Problem: `sudo chmod 775 src/.env` ( default permisson: 600 )
            - [.env download link](https://drive.google.com/file/d/1D1E0TWPbuEctc_zivG6cam1S9DL5792O/view?usp=sharing)
        3. Generate a jwt token, will get saved in .env ( 執行指令後，會自動生成在 .env 檔案 )
            - `php artisan jwt:secret`

    - Register API ( 註冊後，記得信箱收驗證信 )
        - [POST] http:localhost:8080/api/auth/register
            ```bash
            # post data
            name:example
            email:example@gmail.com
            password:qwer1234
            password_confirmation:qwer1234
            ```


    - Login API ( Email 驗證完才可以登入 )
        - 未驗證的結果: {"error":"Email not verified"}, 403
        - [POST] http:localhost:8080/api/auth/login
            ```bash
            # post data
            email:example@gmail.com
            password:qwer1234
            ```