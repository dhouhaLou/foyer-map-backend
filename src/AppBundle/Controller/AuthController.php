<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AuthController extends FOSRestController
{

    /**
     * @Rest\Post("/auth/login")
     */
    public function loginAction(Request $request)
    {
        $client = $this->getDoctrine()->getRepository('AppBundle:Client')->find(1);

        $grantRequest = new Request(array(
            'client_id' => $client->getPublicId(),
            'client_secret' => $client->getSecret(),
            'grant_type' => 'password',
            'username' => $request->get('username'),
            'password' => $request->get('password')
        ));

        $tokenResponse = $this->get('fos_oauth_server.server')->grantAccessToken($grantRequest);

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($request->get('username'));

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($user, 'json');

        return new JsonResponse([
            'token' => json_decode($tokenResponse->getContent()),
            'user' => json_decode($jsonContent)
        ]);

    }

    /**
     * @Rest\Post("/auth/register")
     */
    public function registerAction(Request $request)
    {
        $data = new User;
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        if (empty($username) || empty($password) || empty($email)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setUsername($username);
        $data->setEmail($email);
        $data->setPlainPassword($password);
        $data->setRoles(['ROLE_CLIENT']);
        $data->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {
            return new View("Duplicate entry", Response::HTTP_NOT_ACCEPTABLE);
        }
        return new View("Registered Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/auth/reset-password/{userEmail}")
     */
    public function resetPasswordRequestAction(Request $request)
    {
        $email = $request->query->get('userEmail');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);
        if (null === $user) {
            throw $this->createNotFoundException();
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            throw new BadRequestHttpException('Password request alerady requested');
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        return new Response(Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/auth/reset-password")
     */
    public function resetPasswordAction(Request $request)
    {
        $token = $request->request->get('token');
        $email = $request->request->get('password');

        return new Response(Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/auth/user")
     */
    public function userAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === FALSE) {
            return new View("Unauthorized",Response::HTTP_UNAUTHORIZED);
        }
        return $this->getUser();
    }

}
