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
            'courseImage'  => new \Twig_Filter_Method($this, 'courseImageFilter'),
            'license'      => new \Twig_Filter_Method($this, 'licenseFilter'),
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
     * Filter for license
     *
     * @param string $license License string representation
     *
     * @return null|string
     */
    public function licenseFilter($license = null)
    {
        $content = null;
        if (!is_null($license)) {
            $content = '<span class="license-cc" title="CC"></span>';
            $licenseArray = explode('-', $license);
            foreach ($licenseArray as $partLicense) {
                switch ($partLicense) {
                    case MetadataResource::LICENSE_CC_BY:
                        $content .= '<span class="license-cc-by" title="BY"></span>';
                        break;
                    case MetadataResource::LICENSE_CC_NC:
                        $content .= '<span class="license-cc-nc" title="NC"></span>';
                        break;
                    case MetadataResource::LICENSE_CC_SA:
                        $content .= '<span class="license-cc-sa" title="SA"></span>';
                        break;
                    default:
                        break;

                }

            }
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'claire_app_extension';
    }
}
