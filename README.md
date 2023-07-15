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

5. Seed database

   ```bash
   $ php artisan db:seed
   ```

## Development Tools
 - Intall [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode) VSCode Extension
 - Install the dependencies

   ```bash
   $ yarn install
   ```
-

## Test API

### Test Auth API

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
            fullName:example
            username:example
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

### Test Forget and Reset Password API

   - Setup steps

     1. `docker-compose up -d`
     2. `php artisan migrate`

   - Forget Password API
     1. Receive reset code from email
        - [POST] http:localhost:8080/api/password/email
          ```bash
          # post data
          email:example@gmail.com
          ```
     2. Reset password with reset code and **new password**
        - reset code expired in one hour
        - [POST] http:localhost:8080/api/password/reset
          ```bash
          # post data
          code:567894
          password:qwer1234
          password_confirmation:qwer1234
          ```

### Test Change Password API

   - Setup steps

     1. `docker-compose up -d`

   - Change Password API

     - [POST] http://localhost:8080/api/password/change

   - Change Password Steps
     1. Get bearer token with Login API
     2. Paste **Change Password API**
     3. Setup bearer token
        1. Add **Authorization** to post header
        2. Add **bearer-token** to **Authorization Value**
           ```bash
           # format
           bearer bearer-token
           ```
     4. Post API with **current password** and **new password**
        - post example
          ```bash
          # post data
          current_password:567894
          new_password:qwer1234
          new_password_confirmation:qwer1234
          ```
        - return status
          - bearer token error: 'Unauthorized user', http-401
          - current_password error: 'trans('auth.failed')', http-401
          - password successfully changed: 'password successfully changed', http-200

### Test Get User List and User Info API
    - Setup steps
        1. `docker-compose up -d`
        2. setup Authorization with bear token ( use login api to get )

    - API
        - Get User List: [GET] http://localhost:8080/api/get_user_list
        - Get User Info: [GET] http://localhost:8080/api/get_user/{user_id}

    - return status
        - bearer token error: 'Unauthorized user', http-401
        - permission error: role is not admin, http-403
        - successfully: get result

### Test Update User Profile API
    - Setup steps
        1. `docker-compose up -d`
        2. `php artisan migrate`
        3. setup Authorization with bear token ( use login api to get )

    - API
        - Update User Profile: [PATCH] http://localhost:8080/api/update_user/{user_id}
            - check user profile table if exists user_id
        - Update User Role: [POST] http://localhost:8080/api/update_user_role/{user_id}

    - return status
        - bearer token error: 'Unauthorized user', http-401
        - permission error: role is not admin, http-403
            - only for Update User Role API
        - successfully: get updated result


