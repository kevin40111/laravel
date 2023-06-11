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
    $ # TODO:
    ```
