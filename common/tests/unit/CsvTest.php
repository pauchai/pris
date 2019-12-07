<?php namespace common\tests\unit;

use common\fixtures\CompanyDepartmentFixture;
use common\fixtures\CompanyFixture;
use common\fixtures\DepartmentFixture;
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
use common\fixtures\UserFixture;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestResult;
use vova07\base\components\CsvFile;
use vova07\base\ModelGenerator\Facade;
use vova07\base\models\Ownableitem;
use vova07\base\ModelsFactory;
use vova07\base\ModelTableGenerator;
use vova07\countries\models\Country;
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
use vova07\rbac\models\Permission;
use vova07\rbac\rules\AuthorRule;
use vova07\rbac\rules\OwnRule;
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\db\IntegrityException;


class CsvTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    /* @var  \Faker\Generator $faker */



    public function _before()
    {



    }

    protected function _after()
    {
    }

    public function testA()
    {
        $dataDir =  \Yii::getAlias('@common/data/');
        $csvFile = $dataDir . 'csvExample.csv';
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);
        //$lineArray = $csv->readArray();
        //expect($lineArray[0])->equals('A');
        //expect($lineArray[1])->equals('B');
        $i = 2;
        while ($csv->eof ===false) {
            $csv->read();
            expect($csv->getField("A"))->equals('A' . $i);
            expect($csv->getField("B"))->equals('B' . $i);
            expect($csv->getField("C"))->equals('3' . $i);
            expect($csv->getField("D"))->equals('4' . $i);

            $i++;
        }

    }


}