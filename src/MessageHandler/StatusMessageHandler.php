<?php

namespace App\MessageHandler;

use App\Controller\StatusController;
use App\Entity\Status;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class StatusMessageHandler extends AsMessageHandler
{
    public function __invoke(Status $status){
        $statusController = new StatusController();

        $statusController->modifyStatusAMQP($status->getId(), "Status mis à jour");
    }
}