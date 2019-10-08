<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Foyer;


class FoyerController extends FOSRestController
{
    /**
     * @Rest\Get("/foyers")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Foyer')->findAll();
        if ($restresult === null) {
            return new View("there are no foyer exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/foyers/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Foyer')->find($id);
        if ($singleresult === null) {
            return new View("foyer not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/foyers")
     */
    public function postAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $data = new Foyer();
        $nbChmbr = $request->get('nb_chmbr');
        $prixIndiv = $request->get('prix_indiv');
        $prix2 = $request->get('prix2');
        $prix3 = $request->get('prix3');
        $membreID = $request->get('membre_id');
        $titre = $request->get('titre');
        $adresse = $request->get('adresse');
        $ville = $request->get('ville');


        if (empty($nbChmbr) || empty($prixIndiv) || empty($prix2) || empty($prix3) || empty($membreID) || empty($titre) || empty($adresse) || empty($ville)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $membre = $this->getDoctrine()->getRepository('AppBundle:User')->find($membreID);

        $data->setTitre($titre);
        $data->setVille($ville);
        $data->setAdresse($adresse);
        $data->setNbChmbr($nbChmbr);
        $data->setPrixIndiv($prixIndiv);
        $data->setPrix2($prix2);
        $data->setPrix3($prix3);
        $data->setMembre($membre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("foyer Added Successfully", Response::HTTP_OK);
    }


    /**
     * @Rest\Put("/foyers/{id}")
     */
    public function updateAction($id, Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $nbChmbr = $request->get('nb_chmbr');
        $prixIndiv = $request->get('prix_indiv');
        $prix2 = $request->get('prix2');
        $prix3 = $request->get('prix3');
        $membreID = $request->get('membre_id');
        $titre = $request->get('titre');
        $adresse = $request->get('adresse');
        $ville = $request->get('ville');

        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('AppBundle:Foyer')->find($id);


        if (empty($nbChmbr) || empty($prixIndiv) || empty($prix2) || empty($prix3) || empty($membreID) || empty($titre) || empty($adresse) || empty($ville)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $membre = $this->getDoctrine()->getRepository('AppBundle:User')->find($membreID);
        if (!$membre) {
            return new View("Membre not found", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setTitre($titre);
        $data->setVille($ville);
        $data->setAdresse($adresse);
        $data->setNbChmbr($nbChmbr);
        $data->setPrixIndiv($prixIndiv);
        $data->setPrix2($prix2);
        $data->setPrix3($prix3);
        $data->setMembre($membre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("foyer Updated Successfully", Response::HTTP_OK);

    }

    /**
     * @Rest\Delete("/foyers/{id}")
     */
    public function deleteAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $data = new Foyer();
        $sn = $this->getDoctrine()->getManager();
        $foyer = $this->getDoctrine()->getRepository('AppBundle:Foyer')->find($id);
        if (empty($foyer)) {
            return new View("foyer not found", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($foyer);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
