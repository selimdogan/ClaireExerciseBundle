<?php
namespace SimpleIT\ExerciseBundle\Feature\DomainKnowledge;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerKnowledgeByKnowledge Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerKnowledgeByKnowledgeTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const USER_TOKEN_1 = 'Bearer token-1';

    const USER_WRONG_TOKEN = 'Bearer toto';

    const ID_KNOWLEDGE_1 = 1;

    const ID_KNOWLEDGE_2 = 2;

    const ID_OWNER_KNOWLEDGE_1 = 101;

    const ID_OWNER_KNOWLEDGE_2 = 102;

    const ID_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/knowledges/{knowledgeId}/owner-knowledges/{ownerKnowledgeId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_KNOWLEDGE_1,
                self::ID_OWNER_KNOWLEDGE_1,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":101,"knowledge":1,"owner":1000001,"public":true,"metadata":\{.*\},"type":"formula"\}'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                self::ID_OWNER_KNOWLEDGE_2,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":102,"knowledge":2,"owner":1000001,"public":true,"metadata":\{.*\},"type":"formula"\}'
            ),
            array(
                self::ID_KNOWLEDGE_1,
                self::ID_OWNER_KNOWLEDGE_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_KNOWLEDGE_1,
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
                'knowledgeId'      => $knowledgeId,
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
                self::ID_KNOWLEDGE_1,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":101,"knowledge":1,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"formula","content":\{"object_type":"formula"\}\}\]'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":102,"knowledge":2,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"formula","content":\{"object_type":"formula"\}\}\]'
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
            array('knowledgeId' => $inputIdentifier),
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . '$#', $response->getContent());
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
                self::ID_KNOWLEDGE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"knowledge":2,"owner":1000001,"public":true,"metadata":\{.*\},"type":"formula","content":\{"equation":"\$U = \$R \* \$I","variables":\[\{"name":"R","type":"integer","interval":\{"min":"1","max":"20","step":"1"\}\},\{"name":"I","type":"integer","interval":\{"min":"10","max":"20","step":"1"\}\}\],"unknown":\{"name":"U","type":"int"\},"object_type":"formula"\}\}$#'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                array(),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                array('Authorization' => self::USER_WRONG_TOKEN),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                401,
                '#^Unauthorized$#'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"id":30,"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_KNOWLEDGE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_NOT_EXISTING,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            )
        );
    }

    /**
     * Test POST
     *
     * @param int    $inputIdentifier    Knowledge id
     * @param array  $headers
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputIdentifier,
        array $headers,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->post(
            array('knowledgeId' => $inputIdentifier),
            $content,
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
                self::ID_KNOWLEDGE_1,
                self::ID_OWNER_KNOWLEDGE_1,
                '{"public":false}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":101,"knowledge":1,"owner":1000001,"public":false,"metadata":\{.*\},"type":"formula","content":\{"equation":"\$U = \$R \* \$I","variables":\[\{"name":"R","type":"float","digits_after_point":2,"interval":\{"min":"1","max":"2","step":"0.02"\}\},\{"name":"I","type":"float","digits_after_point":1,"interval":\{"min":"10","max":"20","step":"0.5"\}\}\],"unknown":\{"name":"U","type":"float","digits_after_point":2\},"object_type":"formula"\}\}$#'
            ),
            array(
                self::ID_KNOWLEDGE_1,
                self::ID_OWNER_KNOWLEDGE_1,
                '{"id":5,"public":false}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_KNOWLEDGE_1,
                self::ID_OWNER_KNOWLEDGE_1,
                '{"metadata":{"doo":"mee"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_NOT_EXISTING,
                self::ID_OWNER_KNOWLEDGE_1,
                '{"public":false}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
            array(
                self::ID_KNOWLEDGE_1,
                self::ID_NOT_EXISTING,
                '{"public":false}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $oemId              Knowledge id
     * @param string $ownerKnowledgeId
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $oemId,
        $ownerKnowledgeId,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array('knowledgeId' => $oemId, 'ownerKnowledgeId' => $ownerKnowledgeId),
            $content
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
