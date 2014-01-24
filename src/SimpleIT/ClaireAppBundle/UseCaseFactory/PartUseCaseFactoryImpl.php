<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

use OC\CLAIRE\BusinessRules\Requestors\InvalidUseCaseException;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartUseCaseFactoryImpl implements UseCaseFactory
{
    /**
     * @var ContainerInterface
     */
    private $injector;

    /**
     * @return UseCase
     */
    public function make($useCaseName)
    {
        switch ($useCaseName) {
            // DESCRIPTION
            case 'GetDraftPartDescription':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.part_description.get_draft_part_description'
                );
                break;
            // DIFFICULTY
            case 'GetDraftPartDifficulty':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.part_difficulty.get_draft_part_difficulty'
                );
                break;
            case 'SavePartDifficulty':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.course.part_difficulty.save_part_difficulty'
                );
                break;
            default:
                throw new InvalidUseCaseException();
        }

        return $useCase;
    }

    public function setInjector(ContainerInterface $injector)
    {
        $this->injector = $injector;
    }

}
