<?php

namespace App\Controller;

use App\Data\DataMesa;
use App\Entity\Mesa;
use App\Form\MesaType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/mesa")
 * @IsGranted("ROLE_CAJERO")
 */
class MesaController extends AbstractController
{
    /**
     * @Route("/", name="mesa_index", methods={"GET"})
     */
    public function index(): Response
    {
        $dm = new DataMesa();
        $mesas =  $dm->obtenerMesasPedidosPendientes( $this->getDoctrine()->getManager());
        return $this->render('mesa/index.html.twig', [
            'mesas' => $mesas,
        ]);
    }

    /**
     * @Route("/new", name="mesa_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mesa = new Mesa();
        $form = $this->createForm(MesaType::class, $mesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mesa);
            $entityManager->flush();

            return $this->redirectToRoute('mesa_index');
        }

        return $this->render('mesa/new.html.twig', [
            'mesa' => $mesa,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{idMesa}", name="mesa_show", methods={"GET"})
     */
    public function show(Mesa $mesa): Response
    {
        return $this->render('mesa/show.html.twig', [
            'mesa' => $mesa,
        ]);
    }

    /**
     * @Route("/{idMesa}/edit", name="mesa_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mesa $mesa): Response
    {
        $form = $this->createForm(MesaType::class, $mesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mesa_index', [
                'idMesa' => $mesa->getIdMesa(),
            ]);
        }

        return $this->render('mesa/edit.html.twig', [
            'mesa' => $mesa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMesa}", name="mesa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mesa $mesa): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mesa->getIdMesa(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mesa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mesa_index');
    }
}
