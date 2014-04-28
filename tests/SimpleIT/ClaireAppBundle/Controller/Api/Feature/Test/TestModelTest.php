<?php
namespace SimpleIT\ExerciseBundle\Feature\Test;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test Stored Exercises.
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class TestModelTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = '#Bad Request#';

    const USER_TOKEN_1 = 'Bearer token-1';

    const NOT_FOUND = '#Not Found#';

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '{"id":1,"title":"Anatomie et autre","owner_exercise_models":[1,2]}';

    const TEST_RESOURCE_2 = '{"id":2,"title":"Modele de test d\'essai","owner_exercise_models":[3,4,2]}';

    const TEST_RESOURCE_IN_LIST_1 = '{"id":1,"title":"Anatomie et autre"}';

    const TEST_RESOURCE_IN_LIST_2 = '{"id":2,"title":"Modele de test d\'essai"}';

    /**
     * @var string
     */
    protected $path = '/test-models/{testModelId}';

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
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
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
        $response = $this->get(array('testModelId' => $inputIdentifier));

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
        $expectedListNormal = '[' .
            self::TEST_RESOURCE_IN_LIST_1 . ',' .
            self::TEST_RESOURCE_IN_LIST_2 . ']';

        return array(
            array(ApiResponse::STATUS_CODE_OK, $expectedListNormal)
        );
    }

    /**
     * Test GET on collection
     *
     * @param integer $expectedStatusCode Expected status code
     * @param string  $expectedContent    Expected content
     * @param array   $inputParameters    Parameters
     * @param array   $inputRange         Range (min, max)
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $expectedStatusCode,
        $expectedContent,
        array $inputParameters = array(),
        array $inputRange = array()
    )
    {
        $response = $this->getAll(
            array(),
            $inputParameters,
            array(),
            $inputRange,
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
                '{"title":"Ma premiere feuille de test !!", "owner_exercise_models":[2,1,3]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"title":"Ma premiere feuille de test !!","owner_exercise_models":\[2,1,3\]\}$#',
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"title":"Ma premiere feuille de test !!"}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"owner_exercise_models":[2,1,3]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"title":"Ma premiere feuille de test !!", "owner_exercise_models":[2,1,3}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            )
        );
    }

    /**
     * Test POST
     *
     * @param string $inputContent       JSON Input
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     * @param array  $headers            Headers
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputContent,
        $expectedStatusCode,
        $expectedContent,
        array $headers = array()
    )
    {
        $response = $this->post(array(), $inputContent, array(), $headers);

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
                '{"title":"Nouveau titre"}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":1,"title":"Nouveau titre","owner_exercise_models":\[1,2\]\}$#',
            ),
            array(
                self::ID_TEST_1,
                '{"owner_exercise_models":[2,1,3]}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":1,"title":"Anatomie et autre","owner_exercise_models":\[2,1,3\]\}$#',
            ),
            array(
                self::ID_TEST_1,
                '{"id":"8"}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
            ),
            array(
                self::ID_TEST_1,
                '{"title":"Nouveau titre}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '{"title":"Nouveau titre"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND,
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $exerciseModelId
     * @param string $inputContent          Answer content
     * @param int    $expectedStatusCode    Expected status code
     * @param string $expectedContent       Expected response content
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $exerciseModelId,
        $inputContent,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array("testModelId" => $exerciseModelId),
            $inputContent,
            array()
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
        );
    }

    /**
     * Test DELETE
     *
     * @param int $exerciseModelId
     * @param int $expectedStatusCode
     *
     * @dataProvider deleteProvider
     */
    public function testDelete(
        $exerciseModelId,
        $expectedStatusCode
    )
    {
        $response = $this->delete(array("testModelId" => $exerciseModelId));

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
