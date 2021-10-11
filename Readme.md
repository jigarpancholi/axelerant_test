Steps to setup project:

- clone repository
- Create `.env.local` file from `.env` and set `DATABASE_URL`.
- Create `.env.test.local` file from `.env.test` and set test `DATABASE_URL`.
- Run `composer install`
- Run `yarn install`
- Run `yarn encore dev`
- Run `php bin/console doctrine:migration:migrate`
- Run `php bin/console doctrine:fixtures:load`
- Run `symfony server:start`
- Run url in browser http://127.0.0.1:8000
- Login Credential:

  Role user: user@example.com / admin@123
  
  Role admin: admin@example.com / admin@123

- Run php unit test cases using this command `composer test-cases`. This command loads fixtures data and run unit test cases.
