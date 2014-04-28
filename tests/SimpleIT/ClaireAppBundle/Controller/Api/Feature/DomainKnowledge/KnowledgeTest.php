<?php
namespace SimpleIT\ExerciseBundle\Feature\DomainKnowledge;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test TestAttempt
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class KnowledgeTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const NOT_FOUND = "Not Found";

    const USER_TOKEN_1 = 'Bearer token-1';

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_KNOWLEDGE_1 = '{"id":1,"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"},"required_knowledges":[2],"author":1000001}';

    const TEST_KNOWLEDGE_2 = '{"id":2,"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":"formula"},"required_knowledges":[],"author":1000001}';

    const TEST_KNOWLEDGE_LIST_1 = '[{"id":1,"type":"formula","content":{"object_type":"formula"},"author":1000001},{"id":2,"type":"formula","content":{"object_type":"formula"},"author":1000001}]';

    /**
     * @var string
     */
    protected $path = '/knowledges/{knowledgeId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(self::ID_TEST_1, ApiResponse::STATUS_CODE_OK, self::TEST_KNOWLEDGE_1),
            array(self::ID_TEST_2, ApiResponse::STATUS_CODE_OK, self::TEST_KNOWLEDGE_2),
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
        $response = $this->get(array('knowledgeId' => $inputIdentifier));

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
                self::TEST_KNOWLEDGE_LIST_1,
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
                '{"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"}, "required_knowledges":[1]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"formula","content":\{"equation":"\$U = \$R \* \$I","variables":\[\{"name":"R","type":"float","digits_after_point":2,"interval":\{"min":"1","max":"2","step":"0.02"\}\},\{"name":"I","type":"float","digits_after_point":1,"interval":\{"min":"10","max":"20","step":"0.5"\}\}\],"unknown":\{"name":"U","type":"float","digits_after_point":2\},"object_type":"formula"\},"required_knowledges":\[1\],"author":1000001\}$#',
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"}}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"}, "required_knowledges":[20]}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                "#" . self::NOT_FOUND . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"type":"formula","content":{"equation":"$U $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"}, "required_knowledges":[1]}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array()
            ),
            array(
                '{"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"}, required_knowledges":[1]}',
                self::BAD_REQUEST_CODE,
                "#" . self::BAD_REQUEST . "#",
                array('Authorization' => self::USER_TOKEN_1)
            ),
            array(
                '{"id": 12,"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"float","digits_after_point":2,"interval":{"min":"1","max":"2","step":"0.02"}},{"name":"I","type":"float","digits_after_point":1,"interval":{"min":"10","max":"20","step":"0.5"}}],"unknown":{"name":"U","type":"float","digits_after_point":2},"object_type":"formula"}, "required_knowledges":[1]}',
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
                '{"content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":"formula"}}',
                ApiResponse::STATUS_CODE_OK,
                '{"id":1,"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":"formula"},"required_knowledges":[2],"author":1000001}',
            ),
            array(
                self::ID_TEST_2,
                '{"required_knowledges":[1]}',
                ApiResponse::STATUS_CODE_OK,
                '{"id":2,"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":"formula"},"required_knowledges":[1],"author":1000001}',
            ),
            array(
                self::ID_TEST_1,
                '{"content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":formula"}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_1,
                '{"id":109}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '{"content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":"formula"}}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $knowledgeId
     * @param string $inputContent       Answer content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $knowledgeId,
        $inputContent,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array('knowledgeId' => $knowledgeId),
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
     * @param int $knowledgeId
     * @param int $expectedStatusCode Expected status code
     *
     * @dataProvider deleteProvider
     */
    public function testDelete(
        $knowledgeId,
        $expectedStatusCode
    )
    {
        $response = $this->delete(array('knowledgeId' => $knowledgeId));

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
