<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\DomainKnowledge;

use OC\Tester\ApiFeatureTest;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\Utils\FormatUtils;

/**
 * Test RequiredKnowledgeByKnowledge
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class RequiredKnowledgeByKnowledgeTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const NOT_FOUND = "Not Found";

    const ID_TEST_NOT_EXISTING = 250;

    const ID_TEST_ADD_REQ_MOTHER_1 = 1;

    const ID_TEST_ADD_REQ_MOTHER_2 = 2;

    const ID_TEST_ADD_REQ_REQ_RES_1 = 1;

    const TEST_ADD_REQUIREMENT_1 = '{"id":2,"type":"formula","content":{"equation":"$U = $R * $I","variables":[{"name":"R","type":"integer","interval":{"min":"1","max":"20","step":"1"}},{"name":"I","type":"integer","interval":{"min":"10","max":"20","step":"1"}}],"unknown":{"name":"U","type":"int"},"object_type":"formula"},"required_knowledges":[1],"author":1000001}';

    const ID_TEST_REMOVE_REQ_MOTHER_1 = 1;

    const ID_TEST_REMOVE_REQ_REQ_RES_1 = 2;

    const ID_TEST_REMOVE_REQ_REQ_RES_WRONG = 250;

    /**
     * @var string
     */
    protected $path = '/knowledges/{knowledgeId}/required-knowledges/{reqKnowId}';

    /**
     * Get addRequiredKnowledge Provider
     *
     * @return array
     */
    public static function addRequiredKnowledgeProvider()
    {
        return array(
            array(
                self::ID_TEST_ADD_REQ_MOTHER_2,
                self::ID_TEST_ADD_REQ_REQ_RES_1,
                ApiResponse::STATUS_CODE_CREATED,
                self::TEST_ADD_REQUIREMENT_1,
            ),
            array(
                self::ID_TEST_ADD_REQ_MOTHER_1,
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                self::ID_TEST_ADD_REQ_REQ_RES_1,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
        );
    }

    /**
     * Test CREATE on required knowledge
     *
     * @param int    $knowledgeId
     * @param int    $requiredKnowledgeId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     * @param array  $parameters
     * @param array  $headers
     *
     * @dataProvider addRequiredKnowledgeProvider
     */
    public function testAddRequiredKnowledge(
        $knowledgeId,
        $requiredKnowledgeId,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->post(
            array('knowledgeId' => $knowledgeId, 'reqKnowId' => $requiredKnowledgeId),
            null,
            $parameters,
            $headers,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Get deleteRequiredKnowledge Provider
     *
     * @return array
     */
    public static function deleteRequiredKnowledgeProvider()
    {
        return array(
            array(
                self::ID_TEST_REMOVE_REQ_MOTHER_1,
                self::ID_TEST_REMOVE_REQ_REQ_RES_1,
                ApiResponse::STATUS_CODE_NO_CONTENT,
                '',
            ),
            array(
                self::ID_TEST_REMOVE_REQ_MOTHER_1,
                self::ID_TEST_REMOVE_REQ_REQ_RES_WRONG,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
            array(
                self::ID_TEST_REMOVE_REQ_MOTHER_1,
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                self::ID_TEST_ADD_REQ_REQ_RES_1,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
        );
    }

    /**
     * Test DELETE on required knowledge
     *
     * @param int    $knowledgeId
     * @param int    $requiredKnowledgeId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     * @param array  $parameters
     * @param array  $headers
     *
     * @dataProvider deleteRequiredKnowledgeProvider
     */
    public function testDeleteRequiredKnowledge(
        $knowledgeId,
        $requiredKnowledgeId,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->delete(
            array('knowledgeId' => $knowledgeId, 'reqKnowId' => $requiredKnowledgeId),
            $parameters,
            $headers
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Get getAddRequiredKnowledge Provider
     *
     * @return array
     */
    public static function editRequiredKnowledgeProvider()
    {
        return array(
            array(
                self::ID_TEST_ADD_REQ_MOTHER_2,
                '[1]',
                ApiResponse::STATUS_CODE_OK,
                '[1]'
            ),
            array(
                self::ID_TEST_ADD_REQ_MOTHER_2,
                '[1',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_ADD_REQ_MOTHER_2,
                '[1,250]',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '[1]',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
        );
    }

    /**
     * Test PUT on required knowledge
     *
     * @param int    $knowledgeId
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     * @param array  $parameters
     * @param array  $headers
     *
     * @dataProvider editRequiredKnowledgeProvider
     */
    public function testEditRequiredKnowledge(
        $knowledgeId,
        $content,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->put(
            array('knowledgeId' => $knowledgeId),
            $content,
            $parameters,
            $headers,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
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
