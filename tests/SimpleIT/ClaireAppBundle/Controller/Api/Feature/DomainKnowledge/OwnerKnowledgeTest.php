<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\DomainKnowledge;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerKnowledge Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const ID_TEST_1 = 101;

    const ID_TEST_2 = 102;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_KNOWLEDGE_1 = '#^\{"id":101,"knowledge":1,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"formula"\}$#';

    const TEST_KNOWLEDGE_2 = '#^\{"id":102,"knowledge":2,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"formula"\}$#';

    const TEST_KNOWLEDGE_LIST_1 = '#^\[\{"id":101,"knowledge":1,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"formula","content":\{"object_type":"formula"\}\},\{"id":102,"knowledge":2,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"formula","content":\{"object_type":"formula"\}\}\]$#';

    const TEST_KNOWLEDGE_LIST_2 = '#^\[\]$#';


    /**
     * @var string
     */
    protected $path = '/owner-knowledges/{ownerKnowledgesId}';

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
                '#Not Found#'
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
        $response = $this->get(array('ownerKnowledgesId' => $inputIdentifier));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_KNOWLEDGE_LIST_2,
                array("public-except-user" => 1000001)
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
            array(self::ID_TEST_1, ApiResponse::STATUS_CODE_NO_CONTENT),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            )
        );
    }

    /**
     * Test DELETE
     *
     * @param int $inputIdentifier    Exercise id
     * @param int $expectedStatusCode Expected status code
     *
     * @dataProvider deleteProvider
     */
    public function testDelete($inputIdentifier, $expectedStatusCode)
    {
        $response = $this->delete(array('ownerKnowledgesId' => $inputIdentifier));

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
