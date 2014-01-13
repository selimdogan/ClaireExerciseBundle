<?php

namespace OC\CLAIRE\BusinessRules\UseCases\AssociatedContent;

use OC\CLAIRE\BusinessRules\Requestors\InvalidUseCaseException;
use OC\CLAIRE\BusinessRules\Requestors\UseCase;
use OC\CLAIRE\BusinessRules\Requestors\UseCaseFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class AssociatedContentUseCaseFactoryImpl implements UseCaseFactory
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
            case 'GetDraftCourseCategory':
                $useCase = $this->injector->get(
                    'oc.claire.use_cases.associated_content.category_by_course.get_draft_course_category'
                );
                break;
            case 'SaveCourseCategory':
                $useCase = $this->injector->get('oc.claire.use_cases.associated_content.category_by_course.save_course_category');
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
