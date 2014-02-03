<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartUseCaseFactoryImplTest extends UseCaseFactoryTest
{
    protected $useCases = array(
        'GetDraftPart'                        => 'OC\CLAIRE\BusinessRules\UseCases\Course\Part\GetDraftPart',
        'GetWaitingForPublicationPart'        => 'OC\CLAIRE\BusinessRules\UseCases\Course\Part\GetWaitingForPublicationPart',
        'GetPublishedPart'                    => 'OC\CLAIRE\BusinessRules\UseCases\Course\Part\GetPublishedPart',
        'SavePart'                            => 'OC\CLAIRE\BusinessRules\UseCases\Course\Part\SavePart',
        'GetDraftPartContent'                 => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\GetDraftPartContent',
        'GetWaitingForPublicationPartContent' => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\GetWaitingForPublicationPartContent',
        'GetPublishedPartContent'             => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\GetPublishedPartContent',
        'SavePartContent'                     => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartContent\SavePartContent',
        'GetDraftPartDescription'             => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\GetDraftPartDescription',
        'GetDraftPartDifficulty'              => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\GetDraftPartDifficulty',
        'SavePartDifficulty'                  => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\SavePartDifficulty',
    );

    protected function setup()
    {
        $this->useCaseFactory = new PartUseCaseFactoryImpl();
        parent::setup();
    }
}
