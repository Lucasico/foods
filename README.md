<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Clone o projeto
git clone git@github.com:seuprojeto

## Acesse o projeto
cd seuprojeto

## Instale as dependências e o framework
composer install --no-scripts

## Copie o arquivo .env.example
cp .env.example .env

## Crie uma nova chave para a aplicação
php artisan key:generate

Em seguida você deve configurar o arquivo .env e rodar as migrations com:

php artisan migrate --seed

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
