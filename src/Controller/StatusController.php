<?php

namespace App\Controller;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/status')]
class StatusController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @param StatusRepository $statusRepository
     * @return Response
     * @Route("/", name="app_status", methods={"GET"})
     */
    public function getAllStatus(StatusRepository  $statusRepository): Response
    {
        return $this->json($statusRepository->findAll(), Response::HTTP_OK);
    }

    #[Route('/create', name: 'app_status_new', methods: ['GET', 'POST'])]
    public function createProduct(Request $request): Response
    {
        $status = new Status();
        $name = $request->get('name');
        $stock = $request->get('stocks');
        $barcode = $request->get('barcode');
        if(!empty($name)) $product->setName($name);
        if(empty($stock)) return $this->json("Enter amount of stocks");

        $product->setStocks($stock);
        $product->setBarcode($barcode);

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return $this->json($errors[0], Response::HTTP_BAD_REQUEST);
        }

        $this->em->persist($product);
        $this->em->flush();

        return $this->json('Created new product successfully', Response::HTTP_CREATED );
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function getSelectedProduct(int $id, ProductsRepository $productsRepository): Response
    {
        $res = $productsRepository->find($id);
        if($res != null){
            return $this->json($res, Response::HTTP_OK);
        }
        return $this->json("This product does not exists", Response::HTTP_NOT_FOUND);
    }

}
