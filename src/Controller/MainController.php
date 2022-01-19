<?php

namespace App\Controller;

use App\Entity\CRUD;
use App\Form\CrudType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {   
        $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();
        return $this->render('main/index.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @Route("create", name="create")
     */
    public function create(Request $request){
        $crud = new CRUD();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully!');
            return $this->redirectToRoute('main');
        }
        return $this->render('main/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
