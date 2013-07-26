<?php


namespace SimpleIT\ClaireAppBundle\Twig;

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
            'firstForList' => new \Twig_Filter_Method($this, 'firstLetterFilter')
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
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'claire_app_extension';
    }
}
