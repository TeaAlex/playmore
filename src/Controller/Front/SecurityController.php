<?php
namespace App\Controller\Front;

use App\Form\RegisterType;
use App\Form\ResetType;
use App\Form\ForgotType;
use App\Entity\User;
use App\Services\MailServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 * @package App\Controller
 * @Route(name="app_security_")
 */
class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper): Response
    {
        return $this->render('Front/Security/login.html.twig', [
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
    /**
     * @Route("/register", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailServices $mailservices)
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('app_default_home');
        }
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            if ($mailservices->notifyRegistration($user->getEmail())) {
                $this->addFlash('success', 'Notification mail was sent successfully.');
            }
            return $this->redirectToRoute('app_security_login');
        }
        return $this->render(
            'Front/Security/register.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/reset", name="reset")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailServices $mailservices): Response
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('app_default_home');
        }
        $form = $this->createForm(ResetType::class);
        $form->handleRequest($request);
        $token = $request->query->get('token');

        if($token === null){
            return $this->redirectToRoute('app_security_login');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneByResetToken($token);
            if ($user === null) {
                return $this->redirectToRoute('app_security_login');
            }
            $user->setResetToken(null);
            $pass = $form['password']->getData();
            $password = $passwordEncoder->encodePassword($user, $pass);
            $user->setPassword($password);
            $em->flush();
            if ($mailservices->notifyPassword($user->getEmail())) {
                $this->addFlash('success', 'Notification mail was sent successfully.');
            }
            return $this->redirectToRoute('app_security_login');
        }
        return $this->render('Front/Security/reset.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/forgot", name="forgot")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function forgot(Request $request, MailServices $mailservices, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('app_default_home');
        }
        $form = $this->createForm(ForgotType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $email]);
            $token = $tokenGenerator->generateToken();

            if ($user === null) {
                return $this->redirectToRoute('app_security_login');
            }

            $url = $this->generateUrl('app_security_reset', array('token'=>$token), UrlGeneratorInterface::ABSOLUTE_URL);
            $em = $this->getDoctrine()->getManager();
            $user->setResetToken($token);
            $em->flush();

            if ($mailservices->forgotPass($email, $url)) {
                $this->addFlash('notice', 'Notification mail was sent successfully.');
            }
        }
        return $this->render('Front/Security/forgot.html.twig', [
            'form' => $form->createView()
        ]);
    }




}