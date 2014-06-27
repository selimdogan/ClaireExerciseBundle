<?php

namespace SimpleIT\ClaireExerciseBundle\Listener;

use Claroline\CoreBundle\Event\CustomActionResourceEvent;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseModel\ExerciseModel;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ExerciseModelListener
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelListener implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Set the container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * On try, open claire to answer the exercise.
     *
     * @param CustomActionResourceEvent $event
     */
    public function onTry(CustomActionResourceEvent $event)
    {
        /** @var ExerciseModel $model */
        $model = $event->getResource();

        //Redirection to the controller
        $route = $this->container->get('router')->generate('frontend_index');
        $route .= '#/learner/model/' . $model->getId() . '/try';
        $event->setResponse(new RedirectResponse($route));
        $event->stopPropagation();
    }
}
