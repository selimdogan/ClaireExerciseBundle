<?php


namespace SimpleIT\ClaireAppBundle\Twig;

use SimpleIT\ApiResourcesBundle\Course\MetadataResource;
use SimpleIT\Utils\ArrayUtils;
use SimpleIT\Utils\StringUtils;

/**
 * Class extension
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class ClaireAppExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'firstForList' => new \Twig_Filter_Method($this, 'firstLetterFilter'),
            'courseImage'  => new \Twig_Filter_Method($this, 'courseImageFilter')
        );
    }

    /**
     * Return the first letter of a string, # if this char is not alpha
     *
     * @param string $string String
     *
     * @return string
     */
    public function firstLetterFilter($string)
    {
        $string = strtoupper($string);
        $char = substr($string, 0, 1);

        if (!ctype_alpha($char)) {
            $char = '#';
        }

        return $char;
    }

    /**
     * Handle course image
     *
     * @param array $metadatas Metadatas
     *
     * @return string
     */
    public function courseImageFilter($metadatas = array())
    {
        $url = null;
        if (!is_null($metadatas)) {
            $url = ArrayUtils::getValue($metadatas, MetadataResource::COURSE_METADATA_IMAGE);
        }
        if (is_null($url)) {
            // FIXME put a url
            $url = '';
        }

        return $url;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'claire_app_extension';
    }
}
