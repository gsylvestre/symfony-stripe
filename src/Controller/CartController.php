<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartRepository $cartRepository): Response
    {
        $cart = $cartRepository->findOneBy(['user' => $this->getUser(), 'status' => 'active']);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/stripe/hook", name="stripe_hook")
     */
    public function stripeHook(Request $request)
    {
        $payload = $request->getContent();
        file_put_contents(__DIR__ . '/hook.json', $payload);
        return new Response('hihi');
    }

    /**
     * @Route("/stripe/create/session", name="stripe_create_session", methods={"POST"})
     */
    public function stripeCreateSession(CartRepository $cartRepository)
    {
        $cart = $cartRepository->findOneBy(['user' => $this->getUser(), 'status' => 'active']);

        \Stripe\Stripe::setApiKey('sk_test_51HkPZdCc8KoQEaMIodCH66kPI9mFdk8OgmP8hzJBHjqKnKthr828wbmUCXWWzeSX4kH9JerKiMHlkPmAM84XEBTw00iPzzRsK7');

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $cart->getStripeLineItems(),
            'mode' => 'payment',
            'success_url' => $this->generateUrl('cart_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cart_canceled', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id' => $checkout_session->id]);
    }

    /**
     * @Route("/commande/validation", name="cart_success")
     */
    public function cartSuccess()
    {
        return new Response('yoouuuuuupppipiiiiii');
    }

    /**
     * @Route("/commande/annulation", name="cart_canceled")
     */
    public function cartCancel()
    {
        return new Response('oh noooooooo');
    }
}
