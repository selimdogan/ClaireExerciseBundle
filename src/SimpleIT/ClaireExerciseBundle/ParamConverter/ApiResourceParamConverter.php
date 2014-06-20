<?php

namespace SimpleIT\ClaireExerciseBundle\ParamConverter;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use SimpleIT\ClaireExerciseBundle\Exception\Api\ApiException;
use SimpleIT\ClaireExerciseBundle\Service\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiResourceParamConverter
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ApiResourceParamConverter implements ParamConverterInterface
{

    /**
     * @var  SerializerInterface
     */
    private $serializer;

    /**
     * Apply
     *
     * @param Request                $request       Request
     * @param ConfigurationInterface $configuration Configuration
     *
     * @throws ApiException
     * @return bool
     */
    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        try {
            // Array deserialization
            if ($configuration->getClass() == 'Doctrine\Common\Collections\ArrayCollection'
                || $configuration->getClass() == 'Doctrine\Common\Collections\Collection'
            ) {
                $options = $configuration->getOptions();

                if (array_key_exists('type', $options)) {
                    $type = 'ArrayCollection<' . $options['type'] . '>';
                    $resource = $this->serializer->deserialize(
                        $request->getContent(),
                        $type,
                        'json'
                    );
                } else {
                    $resource = $this->serializer->deserialize(
                        $request->getContent(),
                        'array',
                        'json'
                    );
                    $resource = new ArrayCollection($resource);
                }
            } else {
                $resource = $this->serializer->deserialize(
                    $request->getContent(),
                    $configuration->getClass(),
                    'json'
                );
            }
            $request->attributes->set($configuration->getName(), $resource);
            $apply = true;

        } catch (\Exception $e) {
            throw new ApiException(ApiException::STATUS_CODE_BAD_REQUEST, $e->getMessage());
        }

        return $apply;
    }

    /**
     * Supports
     *
     * @param ConfigurationInterface $configuration Configuration
     *
     * @return bool
     */
    public function supports(ConfigurationInterface $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return true;
    }

    /**
     * Set serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
