<?php

namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * @Route(name="app_security_")
 */
class PaymentController extends AbstractController
{


    /**
     * @Route("/payment", name="payment")
     */
    public function payment() : Response
    {
        return $this->render('Front/payment/payment.html.twig');
    }

    /**
     * @Route("/payment/done", name="done")
     */
    public function paymentDone(Request $request) : Response
    {
        \Stripe\Stripe::setApiKey("sk_test_PpluQ2FQOMErLMBeKIc1e6Xd00KLAvkbIj");

        $token = $_POST['stripeToken'];

        try {
            $charge = \Stripe\Charge::create([
                'amount' => 2000,
                'currency' => 'eur',
                'source' => $request->request->get('stripeToken'),
                'description' => "Achat de Playmore Coins",
            ]);
            return $this->render('Front/payment/success.html.twig');
        } catch(\Stripe\Error\Card $e) {

            return $this->render('Front/payment/failed.html.twig');
        }



    }

}
