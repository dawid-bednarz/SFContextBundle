<?php

namespace DawBed\ContextBundle\Command;

use DawBed\ContextBundle\Entity\AbstractGroup;
use DawBed\ContextBundle\Entity\Context;
use DawBed\ContextBundle\Provider;
use DawBed\PHPClassProvider\ClassProvider;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
class LoadContextCommand extends Command
{
    const NAME = 'dawbed:context:update';

    private $provider;
    private $entityManager;

    public function __construct(?string $name = null, Provider $provider, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->provider = $provider;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Update all contexts');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $respository = $this->entityManager->getRepository($this->provider->getDiscriminatorName());

        foreach ($this->provider->getData() as $type => $data) {
            $entity = $respository->findOneBy(['type' => $type]);
            if (is_null($entity)) {
                $output->writeln(sprintf('Add "%s"', $type));
                $entity = $this->getEntity();
            } else {
                $output->writeln(sprintf('Update "%s"', $type));
            }
            $entity->setName($data['name']);
            $entity->setType($type);
            $this->addGroups($entity, $data['groups']);
            $this->clearUnusedGroups($entity, $data['groups']);
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();

        return 0;
    }

    private function getEntity(): Context
    {
        $entityClass = $this->provider->getDiscriminatorName();
        return new $entityClass;
    }

    private function addGroups(Context $entity, array $groups) : void
    {
        foreach ($groups as $name) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('name', $name));
            $group = $entity->getGroups()->matching($criteria);
            if ($group->count()) {
                $group = $group->first();
            } else {
                $group = ClassProvider::new(AbstractGroup::class);
                $group->setContext($entity);
                $group->setName($name);
            }
            $this->entityManager->persist($group);
        }
    }

    private function clearUnusedGroups(Context $entity, array $groups): void
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->notIn('name', $groups));
        foreach ($entity->getGroups()->matching($criteria) as $group) {
            $this->entityManager->remove($group);
        }
    }
}