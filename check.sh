phpcs --ignore=index.php || exit 1

./vendor/bin/phpunit || exit 1