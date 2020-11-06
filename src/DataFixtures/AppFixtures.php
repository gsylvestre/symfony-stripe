<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $allProducts = [];
        for($i=0; $i<30; $i++) {
            $product = new Product();
            $product->setName($faker->words(2, true));
            $product->setPrice($faker->numberBetween(1000, 50000));
            $manager->persist($product);
            $allProducts[] = $product;
        }

        $manager->flush();

        $user = new User();
        $user->setEmail('yo@yo.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->encoder->encodePassword($user, 'yoyoyo'));

        $manager->persist($user);
        $manager->flush();

        $cart = new Cart();
        $cart->setUser($user);
        $cart->setStatus('active');
        $cart->addProduct($faker->randomElement($allProducts));
        $cart->addProduct($faker->randomElement($allProducts));
        $cart->addProduct($faker->randomElement($allProducts));

        $manager->persist($cart);
        $manager->flush();
    }
}
