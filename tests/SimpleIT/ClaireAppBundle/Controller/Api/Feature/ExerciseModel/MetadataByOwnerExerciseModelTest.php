<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseModel;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test MetadataByExerciseModel Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByOwnerExerciseModelTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const ID_TEST_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/owner-exercise-models/{ownerExerciseModelId}/metadatas/{metaKey}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(self::ID_TEST_1, 'niveau', ApiResponse::STATUS_CODE_OK, '{"niveau":"L1"}'),
            array(self::ID_TEST_2, 'niveau', ApiResponse::STATUS_CODE_OK, '{"niveau":"L1"}'),
            array(self::ID_TEST_3, 'niveau', ApiResponse::STATUS_CODE_OK, '{"niveau":"TS"}'),
            array(self::ID_TEST_4, 'niveau', ApiResponse::STATUS_CODE_OK, '{"niveau":"TS"}'),
            array(
                self::ID_TEST_NOT_EXISTING,
                'niveau',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_TEST_1,
                'niveauddddd',
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
            array('ownerExerciseModelId' => $ownerModelId, 'metaKey' => $metaKey)
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
                '{"matiere":"Anatomie","niveau":"L1","_misc":"anatomie;test;basique"}'

            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                '{"niveau":"L1","matiere":"Programmation"}'

            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_OK,
                '{"niveau":"TS","matiere":"SVT"}'

            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                '{"niveau":"TS","matiere":"SVT"}'

            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int | string $inputIdentifier    Exercise model id
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
            array('ownerExerciseModelId' => $inputIdentifier),
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
     * @param int    $inputIdentifier    Exercise model id
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider postProvider
     */
    public function testCreate($inputIdentifier, $content, $expectedStatusCode, $expectedContent)
    {
        $response = $this->post(array('ownerExerciseModelId' => $inputIdentifier), $content);

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
                'matiere',
                '{"value":"mathematiques"}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"matiere":"mathematiques"\}$#'
            ),
            array(
                self::ID_TEST_2,
                'matiere',
                '{"val":"mathematiques"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_2,
                'matiere',
                '{"key":"matiere","value":"mathematiques"}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_2,
                'matiere',
                '{"value:"mathematiques"}',
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
                'matiere',
                '{"value":"mathematiques"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $oemId              Exercise model id
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
            array('ownerExerciseModelId' => $oemId, 'metaKey' => $metaKey),
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
                self::ID_TEST_3,
                '{"matiere":"mathematiques", "difficulte":"pas facile"}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"matiere":"mathematiques","difficulte":"pas facile"\}$#'
            ),
            array(
                self::ID_TEST_3,
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
     * @param int    $oemId              Exercise model id
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putAllProvider
     */
    public function testEditAll($oemId, $content, $expectedStatusCode, $expectedContent)
    {
        $response = $this->put(
            array('ownerExerciseModelId' => $oemId),
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
                'niveau',
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_1,
                'Buzz',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                'niveau',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
        );
    }

    /**
     * Test DELETE
     *
     * @param int    $oemId              Exercise model id
     * @param string $metaKey
     * @param int    $expectedStatusCode Expected status code
     *
     * @dataProvider deleteProvider
     */
    public function testDelete($oemId, $metaKey, $expectedStatusCode)
    {
        $response = $this->delete(array('ownerExerciseModelId' => $oemId, 'metaKey' => $metaKey));

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
