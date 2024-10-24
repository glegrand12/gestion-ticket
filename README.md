# Start the application

To start the application, run the following command:

if it's the first time you are running the application, you need to install the dependencies first:


# Don't forget to create a *BRANCHE* with the name of your feature

**example:** `git checkout -b feat/your-feature-name`

```bash
php bin/console doctrine:database:create

php bin/console doctrine:schema:create

php bin/console doctrine:schema:update --force

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load -n
```

In theory, the application should be up and running now. You can access it by going to `http://localhost:8000` in your browser.

# GOOD LUCK! 🚀😁🥳

