<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Service;

use DawBed\PHPContext\Model\CreateModel;
use Doctrine\ORM\EntityManagerInterface;

class CreateService implements CreateServiceInterface
{
    protected $supportService;
    protected $entityManager;

    function __construct(EntityManagerInterface $entityManager, SupportService $supportService)
    {
        $this->supportService = $supportService;
        $this->entityManager = $entityManager;
    }

    public function make(CreateModel $model): EntityManagerInterface
    {
        $entity = $model->getEntity();
        $repository = $this->entityManager->getRepository(get_class($entity));
        $this->supportService->isOrThrow($entity);

        $existsEntity = $repository->findOneByType($entity->getType());

        if (!is_null($existsEntity)) {
            $existsEntity->setName($entity->getName());
            $model->setEntity($existsEntity);
        }
        $this->entityManager->persist($model->getEntity());

        return $this->entityManager;
    }
}