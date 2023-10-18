## Requirements

-   [Docker](https://docs.docker.com/install)
-   [Docker Compose](https://docs.docker.com/compose/install)

## Setup

1. Clone the repository. `git clone git@github.com:bug-king-solver/virta-laravel-test.git`.
2. Start the containers by running `docker compose up -d` in the project root.
3. Install the composer packages by running `docker exec laravel_php composer install`.
4. Create database schemas `docker exec laravel_php php artisan migrate:refresh`.
5. Create faker data `docker exec laravel_php php artisan db:seed`.
6. Access the Laravel instance on `http://localhost:5173` (If there is a "Permission denied" error, run `docker-compose exec laravel chown -R www-data storage`).

## Tasks

1. CRUD for both stations and companies.
2. Within the radius of n kilometers from a point (latitude, longitude), your station list is ordered by increasing distance, and stations in the same location are grouped.
3. Your list includes all the children stations in the tree, for the given company_id.
4. Test coverage (PHPUnit)
5. API documentation (Swagger)
6. Dockerize the project
