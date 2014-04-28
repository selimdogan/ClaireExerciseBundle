<?php

namespace SimpleIT\ExerciseBundle\Model\ExerciseObject;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject\ExercisePictureObject;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\PictureResource;

/**
 * Factory to create ExercisePicture
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class ExercisePictureFactory
{
    /**
     * Create ExercisePicture from ExerciseResource
     *
     * @param PictureResource $res The input resource
     *
     * @return ExercisePictureObject
     */
    public static function createFromCommonResource(PictureResource $res)
    {
        $picture = new ExercisePictureObject();
        $picture->setSource($res->getSource());

        return $picture;
    }
}
