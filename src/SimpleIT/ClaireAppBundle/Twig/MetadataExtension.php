<?php
/**
 * Author: Sylvain Mauduit <sylvain.mauduit@simple-it.fr>
 * Date: 23/05/13
 * Time: 21:18
 */

namespace SimpleIT\ClaireAppBundle\Twig;

/**
 * Metadata Twig Extension
 *
 * @package SimpleIT\ClaireAppBundle\Twig
 */
class MetadataExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'metadata_value' => new \Twig_Filter_Method($this, 'metadataValueFilter')
        );
    }

    /**
     * Get metadata value based on its key from an array of metadatas
     *
     * @param  array  $metadatas
     * @param  string $key
     *
     * @return mixed
     */
    public function metadataValueFilter($metadatas, $key)
    {
        foreach ($metadatas as $metadata) {
            if (is_array($metadata)) {
                if ($key === $metadata['key']) {
                    return $metadata['value'];
                }
            } else {
                if ($key === $metadata->getKey()) {
                    return $metadata->getValue();
                }
            }
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'claire_metadata';
    }
}

