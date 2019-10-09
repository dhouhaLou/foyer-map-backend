#Deploy
1) Setup database in config_prod.yml
2) Run php bin/console doctrine:schema:update --force
3) Run php bin/console fos:oauth-server:create-client (for oauth)
4) Run php bin/console fos:user:create (to create admin user)
5) Run php bin/console fos:user:promote (add ROLE_ADMIN)

To deploy on heroku:
https://symfony.com/doc/3.4/deployment/heroku.html
https://devcenter.heroku.com/articles/deploying-symfony3
