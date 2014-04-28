<?php
namespace SimpleIT\ExerciseBundle\Feature\ExerciseResource;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test TestAttempt
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class ResourceTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const NOT_FOUND = "Not Found";

    const USER_TOKEN_1 = 'Bearer token-1';

    const ID_TEST_1 = 109;

    const ID_TEST_2 = 130;

    const ID_TEST_3 = 150;

    const ID_TEST_4 = 153;

    const ID_TEST_5 = 154;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '{"id":109,"type":"text","content":{"text":"Programme 1 en C qui a pour sortie ecran 12","object_type":"text"},"required_exercise_resources":[],"author":1000001}';

    const TEST_RESOURCE_2 = '{"id":130,"type":"picture","content":{"source":"img6.jpeg","object_type":"picture"},"required_exercise_resources":[],"author":1000001}';

    const TEST_RESOURCE_3 = '{"id":150,"type":"multiple-choice-question","content":{"question":"Un tendon relie","propositions":[{"text":"Un muscle a un os","right":true,"force_use":false},{"text":"Deux muscles","right":false},{"text":"Deux os","right":false},{"text":"Un ligament et un muscle","right":false},{"text":"Deux muscles","right":false},{"text":"Un ligament et un os","right":false}],"max_number_of_propositions":4,"max_number_of_right_propositions":0,"do_not_shuffle":false,"object_type":"multiple_choice_question"},"required_exercise_resources":[],"author":1000001}';

    const TEST_RESOURCE_4 = '{"id":153,"type":"sequence","content":{"sequence_type":"text","main_block":{"block_type":"di","elements":[{"text":"Partie une","object_type":"text"},{"block_type":"or","elements":[{"text":"Partie deux","object_type":"text"},{"text":"Partie trois","object_type":"text"}],"object_type":"block"},{"block_type":"di","elements":[{"text":"Partie quatre","object_type":"text"},{"text":"Partie cinq","object_type":"text"}],"object_type":"block"},{"block_type":"di","elements":[{"block_type":"or","elements":[{"text":"Partie six","object_type":"text"},{"text":"Partie sept","object_type":"text"},{"block_type":"or","elements":[{"text":"Partie huit","object_type":"text"},{"text":"Partie neuf","object_type":"text"}],"object_type":"block"}],"object_type":"block"},{"block_type":"or","elements":[{"text":"Partie dix","object_type":"text"},{"text":"Partie onze","object_type":"text"}],"object_type":"block"},{"text":"Partie douze","object_type":"text"},{"text":"Partie treize","object_type":"text"}],"object_type":"block"},{"text":"Partie quatorze,la derni\u00e8re","object_type":"text"}],"object_type":"block"},"object_type":"sequence"},"required_exercise_resources":[],"author":1000001}';

    const TEST_RESOURCE_5 = '{"id":154,"type":"sequence","content":{"sequence_type":"text","main_block":{"block_type":"or","elements":[{"resource_id":129,"object_type":"resource_id"},{"resource_id":125,"object_type":"resource_id"},{"resource_id":131,"object_type":"resource_id"},{"resource_id":132,"object_type":"resource_id"},{"resource_id":127,"object_type":"resource_id"},{"resource_id":128,"object_type":"resource_id"},{"resource_id":130,"object_type":"resource_id"},{"resource_id":126,"object_type":"resource_id"}],"object_type":"block"},"object_type":"sequence"},"required_exercise_resources":[125,126,127,128,129,130,131,132],"author":1000001}';

    const TEST_RESOURCE_LIST_1 = '[{"id":109,"type":"text","author":1000001},{"id":110,"type":"text","author":1000001},{"id":111,"type":"text","author":1000001},{"id":112,"type":"text","author":1000001},{"id":113,"type":"text","author":1000001},{"id":114,"type":"text","author":1000001},{"id":115,"type":"text","author":1000001},{"id":116,"type":"text","author":1000001},{"id":117,"type":"text","author":1000001},{"id":118,"type":"text","author":1000001},{"id":119,"type":"text","author":1000001},{"id":120,"type":"text","author":1000001},{"id":121,"type":"text","author":1000001},{"id":122,"type":"text","author":1000001},{"id":123,"type":"text","author":1000001},{"id":124,"type":"text","author":1000001},{"id":125,"type":"picture","author":1000001},{"id":126,"type":"picture","author":1000001},{"id":127,"type":"picture","author":1000001},{"id":128,"type":"picture","author":1000001},{"id":129,"type":"picture","author":1000001},{"id":130,"type":"picture","author":1000001},{"id":131,"type":"picture","author":1000001},{"id":132,"type":"picture","author":1000001},{"id":147,"type":"multiple-choice-question","author":1000001},{"id":148,"type":"multiple-choice-question","author":1000001},{"id":149,"type":"multiple-choice-question","author":1000001},{"id":150,"type":"multiple-choice-question","author":1000001},{"id":151,"type":"multiple-choice-question","author":1000001},{"id":152,"type":"multiple-choice-question","author":1000001},{"id":153,"type":"sequence","author":1000001},{"id":154,"type":"sequence","author":1000001},{"id":155,"type":"sequence","author":1000001},{"id":156,"type":"multiple-choice-question","author":1000001}]';

    /**
     * @var string
     */
    protected $path = '/resources/{resourceId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(self::ID_TEST_1, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_1),
            array(self::ID_TEST_2, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_2),
            array(self::ID_TEST_3, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_3),
            array(self::ID_TEST_4, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_4),
            array(self::ID_TEST_5, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_5),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            )
        );
    }

    /**
     * Test GET
     *
     * @param int | string $inputIdentifier    Exercise id
     * @param int          $expectedStatusCode Expected status code
     * @param string       $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView($inputIdentifier, $expectedStatusCode, $expectedContent)
    {
        $response = $this->get(array('resourceId' => $inputIdentifier));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Get list provider
     *
     * @return array
     */
    public static function getListProvider()
    {
        return array(
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1,
            ),
        );
    }

    /**
     * Test GET on collection
     *
     * @param integer $expectedStatusCode Expected status code
     * @param string  $expectedContent    Expected content
     * @param array   $parameters
     * @param array   $headers
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->getAll(
            array(),
            $parameters,
            $headers,
            array(),
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Post provider
     *
     * @return array
     */
    public static function postProvider()
    {
        return array(
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"},"required_exercise_resources":[120,148]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"text","content":\{"text":"Super test poutre","object_type":"text"\},"required_exercise_resources":\[(120|148),(120|148)\],"author":1000001\}$#',
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"},"required_exercise_resources":[120,148],"author":1000002}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"},"required_exercise_resources":[]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"text","content":\{"text":"Super test poutre","object_type":"text"\},"required_exercise_resources":\[\],"author":1000001\}$#',
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"},"required_exercise_resources":[1]}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                "#" . self::NOT_FOUND . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"},"required_exercise_resources":[120,148]}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array()
            ),
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"}equired_exercise_resources":[]}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"text","content":{"text":"Super test poutre","object_type":"text"}}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
        );
    }

    /**
     * Test POST
     *
     * @param string $inputContent       Answer content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     * @param array  $headers
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputContent,
        $expectedStatusCode,
        $expectedContent,
        array $headers
    )
    {
        $response = $this->post(
            array(),
            $inputContent,
            array(),
            $headers
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
    }

    /**
     * Put provider
     *
     * @return array
     */
    public static function putProvider()
    {
        return array(
            array(
                self::ID_TEST_1,
                '{"content":{"text":"Programme","object_type":"text"}}',
                ApiResponse::STATUS_CODE_OK,
                '{"id":109,"type":"text","content":{"text":"Programme","object_type":"text"},"required_exercise_resources":[],"author":1000001}',
            ),
            array(
                self::ID_TEST_2,
                '{"required_exercise_resources":[117]}',
                ApiResponse::STATUS_CODE_OK,
                '{"id":130,"type":"picture","content":{"source":"img6.jpeg","object_type":"picture"},"required_exercise_resources":[117],"author":1000001}',
            ),
            array(
                self::ID_TEST_1,
                '{"content":{"text":"Programme2,"object_type":"text"}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_1,
                '{"id":109,"content":{"text":"Programme2","object_type":"text"}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_1,
                '{"type":"text","content":{"text":"Programme2","object_type":"text"}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '{"content":{"text":"Programme","object_type":"text"}}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $resourceId
     * @param string $inputContent       Answer content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $resourceId,
        $inputContent,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array('resourceId' => $resourceId),
            $inputContent
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Delete provider
     *
     * @return array
     */
    public static function deleteProvider()
    {
        return array(
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
        );
    }

    /**
     * Test DELETE
     *
     * @param int $resourceId
     * @param int $expectedStatusCode Expected status code
     *
     * @dataProvider deleteProvider
     */
    public function testDelete(
        $resourceId,
        $expectedStatusCode
    )
    {
        $response = $this->delete(array('resourceId' => $resourceId));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    /**
     * @inheritdoc
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet|\PHPUnit_Extensions_Database_DataSet_XmlDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet(
            array(
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/authentification_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/resource_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/exercise_model_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/stored_exercise_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/test_model_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/test_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/attempt_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/item_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/answer_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/knowledge_dataset.xml'),
            ));
    }
}
