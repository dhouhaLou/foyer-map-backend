<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Annonce;

class AnnonceController extends FOSRestController
{
    /**
     * @Rest\Get("/annonces")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Annonce')->findAll();
        if ($restresult === null) {
            return new View("there are no annonce exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/annonces/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);
        if ($singleresult === null) {
            return new View("annonce not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/annonces")
     */
    public function postAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $data = new Annonce();
        $titre = $request->get('titre');
        $ville = $request->get('ville');
        $tel = $request->get('tel');
        $description = $request->get('description');
        $typeAnnonce = $request->get('type_annonce');
        $image = $request->get('image');
        $membreID = $request->get('membre_id');
        if (empty($titre) || empty($ville) || empty($tel) || empty($description) || empty($typeAnnonce) || empty($image) || empty($membreID)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $membre = $this->getDoctrine()->getRepository('AppBundle:User')->find($membreID);
        if (!$membre) {
            return new View("Membre not found", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setTitre($titre);
        $data->setVille($ville);
        $data->setTel($tel);
        $data->setDescription($description);
        $data->setTypeAnnonce($typeAnnonce);
        $data->setImage($image);
        $data->setMembre($membre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("annonce Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/annonces/{id}")
     */
    public function updateAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $titre = $request->get('titre');
        $ville = $request->get('ville');
        $tel = $request->get('tel');
        $description = $request->get('description');
        $typeAnnonce = $request->get('type_annonce');
        $image = $request->get('image');
        $membreID = $request->get('membre_id');

        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);

        if ($data->getMembre()->getId() != $this->getUser()->getId()) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        if (empty($titre) || empty($ville) || empty($tel) || empty($description) || empty($typeAnnonce) || empty($image) || empty($membreID)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $membre = $this->getDoctrine()->getRepository('AppBundle:User')->find($membreID);
        if (!$membre) {
            return new View("Membre not found", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setTitre($titre);
        $data->setVille($ville);
        $data->setTel($tel);
        $data->setDescription($description);
        $data->setTypeAnnonce($typeAnnonce);
        $data->setImage($image);
        $data->setMembre($membre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("annonce Updated Successfully", Response::HTTP_OK);

    }


    /**
     * @Rest\Delete("/annonces/{id}")
     */
    public function deleteAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $sn = $this->getDoctrine()->getManager();
        $annonce = $this->getDoctrine()->getRepository('AppBundle:Annonce')->find($id);
        if (empty($annonce)) {
            return new View("annonce not found", Response::HTTP_NOT_FOUND);
        } else {
            if ($annonce->getMembre()->getId() != $this->getUser()->getId()) {
                return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
            }
            $sn->remove($annonce);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }

}
