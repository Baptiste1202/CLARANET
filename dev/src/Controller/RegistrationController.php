<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\CustomAuthenticator;
use App\Security\UserAuthenticator;
use App\Service\JWTService as ServiceJWTService;
use App\Service\SendEmailService;
use ContainerF9mBeh1\getJWTServiceService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        Security $security, 
        EntityManagerInterface $entityManager, 
        SendEmailService $mail,
        ServiceJWTService $jwt
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate the token
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256',
            ]; 

            $payload = [
                'user_id' => $user->getId(),
                'iat' => '',
                'exp' => ''
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // send the email
                $mail->send(
                    'noreply@canard.coincoin',
                    $user->getEmail(),
                    'Activation du compte',
                    'register',
                    compact('user', 'token')
                );

            if ($user->isVerified()){
                return $security->login($user, CustomAuthenticator::class, 'main');
                return $this->redirectToRoute('list_vehicules');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'registration/register.html.twig', 
            ['registrationForm' => $form->createView()]
        );
    }

    #[Route('verif/{token}', name: 'verify_user')]
    public function verifUser($token, ServiceJWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em) :Response
    {
        // check if token is valid
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, '0hLa83ll3Broue11e')){
            // token is valid
            // get data (payload)
            $payload = $jwt->getPayload($token);
            
            // get the user
            $user = $userRepository->find($payload['user_id']);

            // check if we have the user and he's not already verify
            if ($user && !$user->isVerified()){
                $user->setVerified(true);
                $em->flush();

                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_login'); 
            }
        }

        $this->addFlash('danger', 'Token invalide ou a expiré');
        return $this->redirectToRoute('app_login'); 
    }


    

}