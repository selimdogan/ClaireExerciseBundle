<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Api;

/**
 * Class ApiHeader
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ApiHeader
{
    /**
     * @const CONTENT_RANGE = 'Content-Range'
     */
    const CONTENT_RANGE = 'Content-Range';

    /**
     * @const CONTENT_LENGTH = 'Content-Length'
     */
    const CONTENT_LENGTH = 'Content-Length';

    /**
     * @const CONTENT_TYPE = 'Content-Type';
     */
    const CONTENT_TYPE = 'Content-Type';

    /**
     * @const ALLOW = 'Allow'
     */
    const ALLOW = 'Allow';

    /**
     * @const FORMAT_DEFAULT = 'json'
     */
    const FORMAT_DEFAULT = self::FORMAT_JSON;

    /**
     * @const FORMAT_HTML = 'html'
     */
    const FORMAT_HTML = 'html';

    /**
     * @const FORMAT_JSON = 'json'
     */
    const FORMAT_JSON = 'json';

    /**
     * @const FORMAT_XML = 'xml'
     */
    const FORMAT_XML = 'xml';

}
