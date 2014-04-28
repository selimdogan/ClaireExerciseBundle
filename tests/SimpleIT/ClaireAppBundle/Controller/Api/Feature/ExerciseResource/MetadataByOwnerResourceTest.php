<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseResource;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test MetadataByResource Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerResourceTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const ID_TEST_1 = 9;

    const ID_TEST_2 = 10;

    const ID_TEST_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/owner-resources/{ownerResourceId}/metadatas/{metaKey}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_TEST_1,
                'screen-output',
                ApiResponse::STATUS_CODE_OK,
                '{"screen-output":"12"}'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                'screen-output',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_TEST_1,
                'scroutput',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            )
        );
    }

    /**
     * Test GET
     *
     * @param int    $ownerModelId       Exercise id
     * @param string $metaKey
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView($ownerModelId, $metaKey, $expectedStatusCode, $expectedContent)
    {
        $response = $this->get(
            array('ownerResourceId' => $ownerModelId, 'metaKey' => $metaKey)
        );

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
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                '#\{(,|("author":"Toto")|("language":"en")|("screen-output":"12")|("size":"43")|("prog-language":"C")|("_misc":"programme;algo;factorielle")){11}\}#'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#Not Found#'
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int | string $inputIdentifier    Resource id
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
            array('ownerResourceId' => $inputIdentifier),
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
                self::ID_TEST_1,
                '{"pos":"tat"}',
                ApiResponse::STATUS_CODE_CREATED,
                '#\{"pos":"tat"\}#'
            ),
            array(
                self::ID_TEST_1,
                '{"poes":"tat","fic","vu"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_1,
                '{"poeic,"vu"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '{"pos":"tat"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            )
        );
    }

    /**
     * Test POST
     *
     * @param int    $inputIdentifier    Resource id
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider postProvider
     */
    public function testCreate($inputIdentifier, $content, $expectedStatusCode, $expectedContent)
    {
        $response = $this->post(array('ownerResourceId' => $inputIdentifier), $content);

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
                self::ID_TEST_2,
                'screen-output',
                '{"value":"222"}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"screen-output":"222"\}$#'
            ),
            array(
                self::ID_TEST_2,
                'screen-output',
                '{"val":"222"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_2,
                'screen-output',
                '{"key":"matiere","value":"222"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_2,
                'screen-output',
                '{"value:"222"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_2,
                'doo',
                '{"value":"me"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                'screen-output',
                '{"value":"222"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $oemId              Resource id
     * @param string $metaKey
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putProvider
     */
    public function testEdit($oemId, $metaKey, $content, $expectedStatusCode, $expectedContent)
    {
        $response = $this->put(
            array('ownerResourceId' => $oemId, 'metaKey' => $metaKey),
            $content
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
    }

    /**
     * PutAll provider
     *
     * @return array
     */
    public static function putAllProvider()
    {
        return array(
            array(
                self::ID_TEST_1,
                '{"matiere":"mathematiques", "difficulte":"pas facile"}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"matiere":"mathematiques","difficulte":"pas facile"\}$#'
            ),
            array(
                self::ID_TEST_1,
                '{"matiere":"mathematiques", difficulte":"pas facile"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '{"matiere":"mathematiques", "difficulte":"pas facile"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
        );
    }

    /**
     * Test Edit All
     *
     * @param int    $oemId              Resource id
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putAllProvider
     */
    public function testEditAll($oemId, $content, $expectedStatusCode, $expectedContent)
    {
        $response = $this->put(
            array('ownerResourceId' => $oemId),
            $content
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
                self::ID_TEST_1,
                'screen-output',
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_1,
                'Buzz',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                'screen-output',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
        );
    }

    /**
     * Test DELETE
     *
     * @param int    $oemId              Resource id
     * @param string $metaKey
     * @param int    $expectedStatusCode Expected status code
     *
     * @dataProvider deleteProvider
     */
    public function testDelete($oemId, $metaKey, $expectedStatusCode)
    {
        $response = $this->delete(array('ownerResourceId' => $oemId, 'metaKey' => $metaKey));

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
