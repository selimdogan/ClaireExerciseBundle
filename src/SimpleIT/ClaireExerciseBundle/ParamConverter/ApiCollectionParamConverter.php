<?php

namespace SimpleIT\ClaireExerciseBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiException;
use SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation;
use SimpleIT\ClaireExerciseBundle\Model\Collection\Sort;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiCollectionParamConverter
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiCollectionParamConverter implements ParamConverterInterface
{

    /**
     * Apply
     *
     * @param Request                $request       Request
     * @param ConfigurationInterface $configuration Configuration
     *
     * @throws \SimpleIT\ClaireExerciseBundle\Exception\Api\ApiException
     * @return bool
     */
    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $collectionInformation = new CollectionInformation();

        $collectionInformation = $this->handleParameters($request, $collectionInformation);

        $request->attributes->set($configuration->getName(), $collectionInformation);

        return true;
    }

    /**
     * Handle parameters
     *  - sorts
     *  - filters
     *
     * @param Request               $request               Request
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @throws ApiException
     * @return CollectionInformation
     */
    private function handleParameters(
        Request $request,
        CollectionInformation $collectionInformation
    )
    {
        try {
            $parameters = $request->query->all();

            // handle sort
            if (array_key_exists('sort', $parameters)) {

                /* Sort :
                 *      - sort=property order
                 *      - sort=property order,property order
                 * Order : asc | desc
                 */
                $sortParams = explode(',', $parameters['sort']);
                foreach ($sortParams as $sortParam) {
                    $sortItemConcats = explode(' ', $sortParam);
                    $order = 'asc';
                    if (isset($sortItemConcats[1])) {
                        $order = $sortItemConcats[1];
                    }
                    $sortItem = new Sort($sortItemConcats[0], $order);
                    $collectionInformation->addSort($sortItem);

                }
                unset($parameters['sort']);
            }

            if (!empty($parameters)) {
                $filterItems = array();
                foreach ($parameters as $property => $value) {
                    $value = explode(',', $value);
                    if (count($value) < 2) {
                        $value = $value[0];
                    }
                    if (!empty($value)) {
                        $filterItems[$property] = $value;
                    }
                }
                $collectionInformation->setFilters($filterItems);
            }

            return $collectionInformation;

        } catch (\InvalidArgumentException $iae) {
            throw new ApiException(ApiException::STATUS_CODE_BAD_REQUEST, $iae->getMessage());
        }
    }

    /**
     * Supports (only CollectionInformation)
     *
     * @param ConfigurationInterface $configuration
     *
     * @return bool
     */
    public function supports(
        ConfigurationInterface $configuration
    )
    {
        $isClassSupported = false;
        if ('SimpleIT\ClaireExerciseBundle\Model\Collection\CollectionInformation' ===
            $configuration->getClass()
        ) {
            $isClassSupported = true;
        }

        return $isClassSupported;
    }
}
