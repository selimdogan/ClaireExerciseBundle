<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\DomainKnowledge;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerKnowledgeByUser Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeByUserTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const ID_USER_1 = 1000001;

    const ID_USER_2 = 1000002;

    const ID_OWNER_KNOWLEDGE_1 = 1;

    const ID_OWNER_KNOWLEDGE_2 = 2;

    const ID_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/users/{userId}/owner-knowledges/{ownerKnowledgeId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_USER_1,
                self::ID_OWNER_KNOWLEDGE_1,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":101,"knowledge":1,"owner":1000001,"public":true,"metadata":\{.*\},"type":"formula"\}'
            ),
            array(
                self::ID_USER_1,
                self::ID_OWNER_KNOWLEDGE_2,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":102,"knowledge":2,"owner":1000001,"public":true,"metadata":\{.*\},"type":"formula"\}'
            ),
            array(
                self::ID_USER_2,
                self::ID_OWNER_KNOWLEDGE_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_USER_1,
                self::ID_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_NOT_EXISTING,
                self::ID_OWNER_KNOWLEDGE_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test GET
     *
     * @param int    $knowledgeId
     * @param int    $ownerKnowledgeId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView(
        $knowledgeId,
        $ownerKnowledgeId,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->get(
            array(
                'userId'          => $knowledgeId,
                'ownerKnowledgeId' => $ownerKnowledgeId
            )
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . '$#', $response->getContent());
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
                self::ID_USER_1,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":101,"knowledge":1,"owner":1000001,"public":true,"metadata":\{".*\},"type":"formula","content":\{"object_type":"formula"\}\},\{"id":102,"knowledge":2,"owner":1000001,"public":true,"metadata":\{".*\},"type":"formula","content":\{"object_type":"formula"\}\}\]'
            ),
            array(
                self::ID_USER_2,
                ApiResponse::STATUS_CODE_OK,
                '\[\]'
            ),
            array(
                self::ID_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int | string $inputIdentifier    Knowledge id
     * @param integer      $expectedStatusCode Expected status code
     * @param string       $expectedContent    Expected content
     * @param array        $inputParameters    Parameters
     * @param array        $inputRange         Range (min, max)
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $inputIdentifier,
        $expectedStatusCode,
        $expectedContent,
        array $inputParameters = array(),
        array $inputRange = array()
    )
    {
        $response = $this->getAll(
            array('userId' => $inputIdentifier),
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . '$#', $response->getContent());
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
