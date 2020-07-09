<?php namespace common\tests\unit;

use common\fixtures\CompanyDepartmentFixture;
use common\fixtures\CompanyFixture;
use common\fixtures\DepartmentFixture;
use common\fixtures\DeviceAccountingFixture;
use common\fixtures\DeviceFixture;
use common\fixtures\IdentFixture;
use common\fixtures\ItemFixture;
use common\fixtures\OfficerFixture;
use common\fixtures\OwnableitemFixture;
use common\fixtures\PersonFixture;
use common\fixtures\PrisonerFixture;
use common\fixtures\PrisonFixture;
use common\fixtures\ProgramDictFixture;
use common\fixtures\ProgramFixture;
use common\fixtures\ProgramPrisonerFixture;
use common\fixtures\ProgramVisitFixture;
use common\fixtures\SectorFixture;
use common\fixtures\SettingFixture;
use common\fixtures\UserFixture;
use common\fixtures\VideoFicture;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestResult;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Facade;
use vova07\base\models\Ownableitem;
use vova07\base\ModelsFactory;
use vova07\base\ModelTableGenerator;
use vova07\countries\models\Country;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\Officer;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Prisoner;
use vova07\prisons\models\Sector;
use vova07\rbac\Module;
use vova07\site\models\Setting;
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use vova07\videos\models\Video;
use vova07\videos\models\VideoMetaData;
use vova07\videos\models\VideoSubInfo;
use yii\db\IntegrityException;


class VideoMetadataTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    private $fixtures;




    protected function _after()
    {
       // $this->generateFixtures();
    }
    public function testMetaData()
    {
       $metaData = new VideoMetaData;
       $metaData->subTitles[] = new VideoSubInfo([
          'name' => "testName",
          'type' => 'vtt',
          'filename' => 'testFile'
       ]);

    }



}