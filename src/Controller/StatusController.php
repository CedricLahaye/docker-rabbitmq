<?php

namespace App\Controller;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
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
    public function createStatus(Request $request, MessageBusInterface $bus): Response
    {
        $status = new Status();

        $status->setStatus($request->get('status'));
        if(!$status->getStatus()){
            return $this->json('Please enter a status', Response::HTTP_NOT_FOUND);
        }
        $bus->dispatch($status);

        $this->em->persist($status);
        $this->em->flush();

        return $this->json('Created new status successfully', Response::HTTP_CREATED );
    }

    #[Route('/{id}', name: 'app_status_show', methods: ['GET'])]
    public function getSelectedStatus(int $id, StatusRepository $statusRepository): Response
    {
        $res = $statusRepository->find($id);
        if($res != null){
            return $this->json($res, Response::HTTP_OK);
        }
        return $this->json("This status does not exists", Response::HTTP_NOT_FOUND);
    }

    #[Route('/update/{id}', name: 'app_status_edit', methods: ['PUT'])]
    public function modifyStatus(Request $request, int $id): Response
    {
        $stat = $this->em->getRepository(Status::class)->find($id);

        if(!$stat){
            return $this->json('No status found for id '. $id, 404);
        }
        if(!$stat instanceof Status){
            throw new \LogicException('Old status not found');
        }
        $status = $request->get('status');
        if(!empty($status)) $stat->setStatus($request->get('status'));

        $this->em->flush();

        $newData = [
            'id' => $stat->getId(),
            'status' => $stat->getStatus(),
        ];
        return $this->json($newData, Response::HTTP_OK);
    }
    #[Route('/update/amqp/{id}', name: 'app_status_edit_amqp', methods: ['PUT'])]
    public function modifyStatusAMQP(int $id, string $statusModified): Response
    {
        $stat = $this->em->getRepository(Status::class)->find($id);

        if(!$stat){
            return $this->json('No status found for id '. $id, 404);
        }
        if(!$stat instanceof Status){
            throw new \LogicException('Old status not found');
        }
        $stat->setStatus($statusModified);

        $this->em->flush();

        $newData = [
            'id' => $stat->getId(),
            'status' => $stat->getStatus(),
        ];
        return $this->json($newData, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_status_delete', methods: ['POST'])]
    public function deleteProduct(int $id): Response
    {
        $product = $this->em->getRepository(Status::class)->find($id);

        if(!$product){
            return $this->json('No status found for id '. $id, Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($product);
        $this->em->flush();

        return $this->json('Deleted status '. $id . ' successfully', Response::HTTP_OK);
    }
}
