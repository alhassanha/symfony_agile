
обратите внимание, что следует запустить эти две команды после запуска docker-compose:
      docker-compose run --rm php74-service php bin/console doctrine:database:create
      docker-compose run --rm php74-service php bin/console  make:migration
      docker-compose run --rm php74-service php bin/console  doctrine:migrations:migrate
