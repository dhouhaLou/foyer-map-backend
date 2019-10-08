<?php

namespace AppBundle\Controller;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;


class UserController extends FOSRestController
{

    /**
     * @Rest\Get("/users")
     */
    public function getAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized",Response::HTTP_UNAUTHORIZED);
        }

        $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/users/{id}")
     */
    public function idAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === FALSE) {
            return new View("Unauthorized",Response::HTTP_UNAUTHORIZED);
        }
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/users")
     */
    public function postAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized",Response::HTTP_UNAUTHORIZED);
        }
        $data = new User;
        $username = $request->get('username');
        $email = $request->get('email');
        $role = $request->get('role');
        $password = $request->get('password');
        if(empty($username) || empty($password) || empty($role)|| empty($email))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setUsername($username);
        $data->setPlainPassword($password);
        $data->setEmail($email);
        $data->setRoles([$role]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        try {
            $em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            return new View("Duplicate entry", Response::HTTP_NOT_ACCEPTABLE);
        }
        return new View("User Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/users/{id}")
     */
    public function updateAction($id,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === FALSE) {
            return new View("Unauthorized",Response::HTTP_UNAUTHORIZED);
        }
        $username = $request->get('username');
        $email = $request->get('email');
        $role = $request->get('role');
        $password = $request->get('password');
        if(empty($username) || empty($password) || empty($role)|| empty($email))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        $data->setUsername($username);
        $data->setPlainPassword($password);
        $data->setEmail($email);
        $data->setRoles([$role]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        try {
            $em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            return new View("Duplicate entry", Response::HTTP_NOT_ACCEPTABLE);
        }
        return new View("User Updated Successfully", Response::HTTP_OK);


    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized",Response::HTTP_UNAUTHORIZED);
        }
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
