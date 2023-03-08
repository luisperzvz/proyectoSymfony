<?php

namespace App\Controller;

use App\Entity\Articulo;
use App\form\ArticuloType;
use App\Repository\ArticuloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

#[Route('/articulos')]
class ArticulosController extends AbstractController
{
    
    #[Route('/', name: 'app_articulos_index', methods: ['GET'])]
    public function index(ArticuloRepository $articuloRepository): Response
    {
        return $this->render('articulos/index.html.twig', [
            'articulos' => $articuloRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_articulos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticuloRepository $articuloRepository): Response
    {
        $articulo = new Articulo();
        $form = $this->createForm(ArticuloType::class, $articulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articuloRepository->add($articulo, true);

            return $this->redirectToRoute('app_articulos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articulos/new.html.twig', [
            'articulo' => $articulo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articulos_show', methods: ['GET'])]
    public function show(Articulo $articulo): Response
    {
        return $this->render('articulos/show.html.twig', [
            'articulo' => $articulo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articulos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articulo $articulo, ArticuloRepository $articuloRepository): Response
    {
        $form = $this->createForm(ArticuloType::class, $articulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articuloRepository->add($articulo, true);

            return $this->redirectToRoute('app_articulos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articulos/edit.html.twig', [
            'articulo' => $articulo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articulos_delete', methods: ['POST'])]
    public function delete(Request $request, Articulo $articulo, ArticuloRepository $articuloRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articulo->getId(), $request->request->get('_token'))) {
            $articuloRepository->remove($articulo, true);
        }

        return $this->redirectToRoute('app_articulos_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/articulos/{categoria}', name:'filtrar',)]
    public function filtrar(ManagerRegistry $doctrine, String $categoria, Request $request): Response { 
        $entityManager = $doctrine->getManager();
        $articulo = $entityManager->getRepository(Articulo::class)->findByCategoria($categoria); 
        return $this-> renderForm('index.html.twig', ['articulos'=> $articulo]); 
        
    }
    
    }

