<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseModel;

use OC\Tester\ApiFeatureTest;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\Utils\FormatUtils;

/**
 * Test RequiredResourceByExerciseModel Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class RequiredResourceByExerciseModelTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const NOT_FOUND = "Not Found";

    const ID_TEST_NOT_EXISTING = 250;

    const ID_TEST_ADD_REQ_MOTHER_1 = 1;

    const ID_TEST_ADD_REQ_REQ_RES_1 = 122;

    const TEST_ADD_REQUIREMENT_1 = '{"id":1,"type":"multiple-choice","title":"Bases de l\'anatomie","content":{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes (une ou plusieurs).","documents":[],"question_blocks":[{"number_of_occurrences":3,"resources":[],"resource_constraint":{"metadata_constraints":[{"key":"category","values":["anatomie"],"comparator":"in"}],"excluded":[]},"max_number_of_propositions":0,"max_number_of_right_propositions":1}],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":[122]}';

    const ID_TEST_REMOVE_REQ_MOTHER_1 = 2;

    const ID_TEST_REMOVE_REQ_REQ_RES_1 = 109;

    const ID_TEST_REMOVE_REQ_REQ_RES_WRONG = 126;

    /**
     * @var string
     */
    protected $path = '/exercise-models/{exerciseModelId}/required-resources/{reqResId}';

    /**
     * Get addRequiredResource Provider
     *
     * @return array
     */
    public static function addRequiredResourceProvider()
    {
        return array(
            array(
                self::ID_TEST_ADD_REQ_MOTHER_1,
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
     * Test CREATE on required resource
     *
     * @param int    $exerciseModelId
     * @param int    $requiredResourceId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     * @param array  $parameters
     * @param array  $headers
     *
     * @dataProvider addRequiredResourceProvider
     */
    public function testAddRequiredResource(
        $exerciseModelId,
        $requiredResourceId,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->post(
            array('exerciseModelId' => $exerciseModelId, 'reqResId' => $requiredResourceId),
            null,
            $parameters,
            $headers,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Get deleteRequiredResource Provider
     *
     * @return array
     */
    public static function deleteRequiredResourceProvider()
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
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
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
     * Test DELETE on required resource
     *
     * @param int    $exerciseModelId
     * @param int    $requiredResourceId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     * @param array  $parameters
     * @param array  $headers
     *
     * @dataProvider deleteRequiredResourceProvider
     */
    public function testDeleteRequiredResource(
        $exerciseModelId,
        $requiredResourceId,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->delete(
            array('exerciseModelId' => $exerciseModelId, 'reqResId' => $requiredResourceId),
            $parameters,
            $headers
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * Get getAddRequiredResource Provider
     *
     * @return array
     */
    public static function editRequiredResourceProvider()
    {
        return array(
            array(
                self::ID_TEST_ADD_REQ_MOTHER_1,
                '[125,126,130,131]',
                ApiResponse::STATUS_CODE_OK,
                '[125,126,130,131]'
            ),
            array(
                self::ID_TEST_ADD_REQ_MOTHER_1,
                '[125,126,130,131',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ID_TEST_ADD_REQ_MOTHER_1,
                '[125,126,130,131,250]',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '[125,126,130,131]',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
        );
    }

    /**
     * Test PUT on required resource
     *
     * @param int    $exerciseModelId
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     * @param array  $parameters
     * @param array  $headers
     *
     * @dataProvider editRequiredResourceProvider
     */
    public function testEditRequiredResource(
        $exerciseModelId,
        $content,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->put(
            array('exerciseModelId' => $exerciseModelId),
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
