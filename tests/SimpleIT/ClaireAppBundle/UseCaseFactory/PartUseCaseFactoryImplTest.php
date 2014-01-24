<?php

namespace SimpleIT\ClaireAppBundle\UseCaseFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartUseCaseFactoryImplTest extends UseCaseFactoryTest
{
    protected $useCases = array(
        'GetDraftPartDescription' => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDescription\GetDraftPartDescription',
        'GetDraftPartDifficulty'  => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\GetDraftPartDifficulty',
        'SavePartDifficulty'  => 'OC\CLAIRE\BusinessRules\UseCases\Course\PartDifficulty\SavePartDifficulty',
    );

    protected function setup()
    {
        $this->useCaseFactory = new PartUseCaseFactoryImpl();
        parent::setup();
    }
}
