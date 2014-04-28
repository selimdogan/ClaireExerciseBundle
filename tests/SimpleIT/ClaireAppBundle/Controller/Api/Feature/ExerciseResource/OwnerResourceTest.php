<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseResource;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerResource Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const ID_TEST_1 = 9;

    const ID_TEST_2 = 30;

    const ID_TEST_3 = 50;

    const ID_TEST_4 = 53;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '#^\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text"\}$#';

    const TEST_RESOURCE_2 = '#^\{"id":30,"resource":130,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture"\}$#';

    const TEST_RESOURCE_3 = '#^\{"id":50,"resource":150,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question"\}$#';

    const TEST_RESOURCE_4 = '#^\{"id":53,"resource":153,"owner":1000001,"public":true,"metadata":\[\],"type":"sequence"\}$#';

    const TEST_RESOURCE_LIST_1 = '#^\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":10,"resource":110,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":11,"resource":111,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":12,"resource":112,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":13,"resource":113,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":14,"resource":114,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":15,"resource":115,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":16,"resource":116,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":17,"resource":117,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":18,"resource":118,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":19,"resource":119,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":20,"resource":120,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":21,"resource":121,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":22,"resource":122,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":23,"resource":123,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":24,"resource":124,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":25,"resource":125,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img1.jpeg","object_type":"picture"\}\},\{"id":26,"resource":126,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img2.jpeg","object_type":"picture"\}\},\{"id":27,"resource":127,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img3.jpeg","object_type":"picture"\}\},\{"id":28,"resource":128,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img4.jpeg","object_type":"picture"\}\},\{"id":29,"resource":129,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img5.jpeg","object_type":"picture"\}\},\{"id":30,"resource":130,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img6.jpeg","object_type":"picture"\}\},\{"id":31,"resource":131,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img7.jpeg","object_type":"picture"\}\},\{"id":32,"resource":132,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"picture","content":\{"source":"img8.jpeg","object_type":"picture"\}\},\{"id":47,"resource":147,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question","content":\{"question":"Une contraction isometrique correspond a :","object_type":"multiple_choice_question"\}\},\{"id":48,"resource":148,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question","content":\{"question":"Le diametre d\'une fibre musculaire correspond a :","object_type":"multiple_choice_question"\}\},\{"id":49,"resource":149,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question","content":\{"question":"La direction des fibres musculaires d\'un muscle","object_type":"multiple_choice_question"\}\},\{"id":50,"resource":150,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question","content":\{"question":"Un tendon relie","object_type":"multiple_choice_question"\}\},\{"id":51,"resource":151,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question","content":\{"question":"Les deux dernieres cotes s\'appellent","object_type":"multiple_choice_question"\}\},\{"id":52,"resource":152,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"multiple-choice-question","content":\{"object_type":"multiple_choice_question"\}\},\{"id":53,"resource":153,"owner":1000001,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":54,"resource":154,"owner":1000001,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":55,"resource":155,"owner":1000002,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":56,"resource":156,"owner":1000002,"public":true,"metadata":\[\],"type":"multiple-choice-question","content":\{"question":"Les deux dernieres cotes s\'appellent","object_type":"multiple_choice_question"\}\},\{"id":57,"resource":109,"owner":1000002,"public":true,"metadata":\[\],"type":"text","content":\{"object_type":"text"\}\}\]$#';

    const TEST_RESOURCE_LIST_2 = '#^\[\{"id":55,"resource":155,"owner":1000002,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":56,"resource":156,"owner":1000002,"public":true,"metadata":\[\],"type":"multiple-choice-question","content":\{"question":"Les deux dernieres cotes s\'appellent","object_type":"multiple_choice_question"\}\}\]$#';

    const TEST_RESOURCE_LIST_3 = '#^\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":10,"resource":110,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":11,"resource":111,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":12,"resource":112,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":13,"resource":113,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":14,"resource":114,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":15,"resource":115,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":16,"resource":116,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":17,"resource":117,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":18,"resource":118,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":19,"resource":119,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":20,"resource":120,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":21,"resource":121,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":22,"resource":122,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":23,"resource":123,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":24,"resource":124,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\}\]$#';

    const TEST_RESOURCE_LIST_4 = '#^\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":10,"resource":110,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":11,"resource":111,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":12,"resource":112,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":13,"resource":113,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":14,"resource":114,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":15,"resource":115,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":16,"resource":116,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\}\]$#';

    const TEST_RESOURCE_LIST_5 = '#^\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\}\]$#';
    const TEST_RESOURCE_LIST_6 = '#^\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":10,"resource":110,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":11,"resource":111,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":12,"resource":112,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":13,"resource":113,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":14,"resource":114,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":15,"resource":115,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":16,"resource":116,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":17,"resource":117,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":18,"resource":118,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":19,"resource":119,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":20,"resource":120,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":21,"resource":121,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":22,"resource":122,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":23,"resource":123,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":24,"resource":124,"owner":1000001,"public":true,"metadata":\{[^}]*\},"type":"text","content":\{"object_type":"text"\}\},\{"id":53,"resource":153,"owner":1000001,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":54,"resource":154,"owner":1000001,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":55,"resource":155,"owner":1000002,"public":true,"metadata":\[\],"type":"sequence","content":\{"sequence_type":"text","object_type":"sequence"\}\},\{"id":57,"resource":109,"owner":1000002,"public":true,"metadata":\[\],"type":"text","content":\{"object_type":"text"\}\}\]$#';

    /**
     * @var string
     */
    protected $path = '/owner-resources/{ownerResourceId}';

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
        $response = $this->get(array('ownerResourceId' => $inputIdentifier));

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
                self::TEST_RESOURCE_LIST_1,
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
                array("public-except-user" => 1000001)
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
                array("metadata" => 'prog-language:C')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_4,
                array("keywords" => 'screen-output')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_5,
                array("keywords" => 'programme')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_5,
                array("keywords" => 'algo')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_5,
                array("keywords" => 'factorielle')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_6,
                array("type" => 'text,sequence')
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
        $response = $this->delete(array('ownerResourceId' => $inputIdentifier));

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
