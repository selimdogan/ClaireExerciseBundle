<?php
namespace SimpleIT\ClaireAppBundle\Model\Exercise\MultipleChoice;

use SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\MultipleChoice\LearnerAnswer;

/**
 * Class LearnerAnswerFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class LearnerAnswerFactory
{
    /**
     * Create an learner answer from array of answers and options
     *
     * @param array $la
     * @param array $options
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\ExerciseCreation\MultipleChoice\LearnerAnswer
     */
    public static function create(array $la, array $options)
    {
        $learnerAnswer = new LearnerAnswer();
        $ticks = array();

        for ($i = 0; $i < $options['nbpropositions']; $i++) {
            if (isset($la[$i]) && $la[$i] == "on") {
                $ticks[$i] = true;
            } else {
                $ticks[$i] = false;
            }
        }

        $learnerAnswer->setTicks($ticks);

        return $learnerAnswer;
    }
}
