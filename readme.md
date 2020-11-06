## Mini démo de site ecommerce avec Stripe et Symfony

#### Installation
```
git clone https://github.com/gsylvestre/symfony-stripe.git 
cd symfony-stripe 
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

Puis se connecter avec yo@yo.com (mdp: yoyoyo)

#### Pour Stripe... 
J'ai laissé mes api keys, faites en bon usage.  
Si vous voulez faire fonctionner les webhooks, vous devez télécharger le cli stripe et lancer :   
```
stripe listen --forward-to https://127.0.0.1:8000/stripe/hook
```
en remplacement par votre url
