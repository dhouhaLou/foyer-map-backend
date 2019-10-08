<?php

namespace AppBundle\Controller;

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Annuaire;

/**
 * Annuaire controller.
 *
 */
class AnnuaireController extends FOSRestController
{
    /**
     * @Rest\Get("/annuaires")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Annuaire')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/annuaires/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Annuaire')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/annuaires")
     */
    public function postAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $data = new Annuaire();
        $titre = $request->get('titre');
        $bailleur = $request->get('bailleur');
        $tel = $request->get('tel');
        $ville = $request->get('ville');
        $description = $request->get('description');
        $adminId = $request->get('admin_id');
        if (empty($titre) || empty($bailleur) || empty($tel) || empty($ville) || empty($adminId) || empty($description)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $admin = $this->getDoctrine()->getRepository('AppBundle:User')->find($adminId);

        $data->setTitre($titre);
        $data->setBailleur($bailleur);
        $data->setDescription($description);
        $data->setTel($tel);
        $data->setVille($ville);
        $data->setAdmin($admin);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("Annuaire Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/annuaires/{id}")
     */
    public function updateAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $titre = $request->get('titre');
        $bailleur = $request->get('bailleur');
        $tel = $request->get('tel');
        $ville = $request->get('ville');
        $adminID = $request->get('admin_id');
        $description = $request->get('description');

        $em = $this->getDoctrine()->getManager();
        $data = $this->getDoctrine()->getRepository('AppBundle:Annuaire')->find($id);

        if (empty($titre) || empty($bailleur) || empty($tel) || empty($ville) || empty($adminID) || empty($description)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $admin = $this->getDoctrine()->getRepository('AppBundle:User')->find($adminID);
        if (!$admin) {
            return new View("Membre not found", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setTitre($titre);
        $data->setDescription($description);
        $data->setBailleur($bailleur);
        $data->setTel($tel);
        $data->setVille($ville);
        $data->setAdmin($admin);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("Annuaire Updated Successfully", Response::HTTP_OK);


    }

    /**
     * @Rest\Delete("/annuaires/{id}")
     */
    public function deleteAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') === FALSE) {
            return new View("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $sn = $this->getDoctrine()->getManager();
        $annuaire = $this->getDoctrine()->getRepository('AppBundle:Annuaire')->find($id);
        if (empty($annuaire)) {
            return new View("annuaire not found", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($annuaire);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }

}
