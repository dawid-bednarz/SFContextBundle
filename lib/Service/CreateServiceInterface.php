<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Service;

use DawBed\ContextBundle\Model\CreateModel;
use Doctrine\ORM\EntityManagerInterface;

interface CreateServiceInterface
{
    public function make(CreateModel $context): EntityManagerInterface;
}