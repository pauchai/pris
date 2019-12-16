<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/9/19
 * Time: 8:53 AM
 */

namespace vova07\site\helpers;


use vova07\countries\models\Country;
use vova07\finances\models\Balance;
use vova07\finances\models\BalanceCategory;
use vova07\jobs\models\JobNotPaidType;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidType;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPrisoner;
use vova07\prisons\models\Company;
use vova07\documents\models\Document;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\prisons\Module;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use Yii;
use yii\db\Expression;
use yii\db\IntegrityException;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FormatConverter;
use vova07\base\components\CsvFile;


class ImportHelper
{
    const PU1_LOCATION_SECTOR1 = 1;
    const PU1_LOCATION_SECTOR2 = 2;
    const PU1_LOCATION_SECTOR3 = 3;
    const PU1_LOCATION_SECTOR4 = 4;
    const PU1_LOCATION_BLOCK1 = 5;
    const PU1_LOCATION_GOSPODARI = 6;
    const PU1_LOCATION_ETAP = 7;
    const PU1_LOCATION_ART91 = 8;
    const PU1_LOCATION_ART92 = 9;
    const PU1_LOCATION_LIBER = 10;



    const DOCUMENT_STATUS_ACTIVE = 1;
    const DOCUMENT_STATUS_INACTIVE = -1;
    const DOCUMENT_STATUS_MAKING = 0;

    const MATERIALS_DIR = '@common/../_materials/';

    public static function mapOriginSectorToCompanyNameAndSectorId()
    {
        return [
            self::PU1_LOCATION_SECTOR1 => ['companyName' => Company::PRISON_PU1, 'sectorName' => 'sector 1'],
            self::PU1_LOCATION_SECTOR2 => ['companyName' => Company::PRISON_PU1, 'sectorName' => 'sector 2'],
            self::PU1_LOCATION_SECTOR3 => ['companyName' => Company::PRISON_PU1, 'sectorName' => 'sector 3'],
            self::PU1_LOCATION_SECTOR4 => ['companyName' => Company::PRISON_PU1, 'sectorName' => 'sector 4'],
            self::PU1_LOCATION_BLOCK1 =>  ['companyName' => Company::PRISON_PU1, 'sectorName' => 'block 1'],
            self::PU1_LOCATION_GOSPODARI => ['companyName' => Company::PRISON_PU1, 'sectorName' => 'hoz'],
            self::PU1_LOCATION_ETAP => ['companyName' => Company::PRISON_PU1, 'sectorName' => null],
            self::PU1_LOCATION_ART91 => ['companyName' => Company::PRISON_PU1, 'sectorName' =>  null],
            self::PU1_LOCATION_ART92 => ['companyName' => Company::PRISON_PU1, 'sectorName' => null],
            self::PU1_LOCATION_LIBER => ['companyName' => Company::PRISON_PU1, 'sectorName' => null],

        ];
    }


    public static function ImportPrisoners($prison_slug = 'pu-1', $importPhotoEnabled = true,$limit = null)
    {
        $originPersons = Yii::$app->db2->createCommand("SELECT * FROM Persons")->queryAll();
        $usersModule = Yii::$app->getModule('users');

        $cnt = 0;
        foreach ($originPersons as $originPerson) {
            //print_r($originPerson);

            $prisoner = new Prisoner();

            $prisoner->person = new Person();
            $prisoner->person->first_name = $originPerson['firstName'];
            $prisoner->person->second_name = $originPerson['secondName'];
            $prisoner->person->patronymic = $originPerson['fatherName'];
            $prisoner->person->birth_year = $originPerson['bYear'];
            $prisoner->person->ident = new Ident();
            $prisoner->person->citizen_id = \vova07\countries\models\Country::find()->where(['iso'=>'MD'])->one()->id;
            $prisoner->person->IDNP = $originPerson['IDNP'];
            $prisoner->person->address = $originPerson['location'];
            $prisoner->origin_id = $originPerson['id'];
           // $prisoner->location_id = $locationId;//self::mapOriginLocationId($originPerson['location']);

            switch ($originPerson['sectorId']){
                case self::PU1_LOCATION_ETAP:
                    $prisoner->status_id = Prisoner::STATUS_ETAP;
                    break;
                case self::PU1_LOCATION_ART91:
                    $prisoner->status_id = Prisoner::STATUS_TERM_91;
                    break;
                case self::PU1_LOCATION_ART92:
                    $prisoner->status_id = Prisoner::STATUS_TERM_92;
                    break;
                case self::PU1_LOCATION_LIBER:
                    $prisoner->status_id = Prisoner::STATUS_TERM;
                    break;
                default:
                    $prisoner->status_id = Prisoner::STATUS_ACTIVE;
            }

            $prisonIdAndSectorId = self::mapOriginSectorToCompanyNameAndSectorId()[$originPerson['sectorId']];

            $company = Company::findOne(['alias'=>$prisonIdAndSectorId['companyName']]);
            $prisoner->prison_id = $company->primaryKey;
            if ($prisonIdAndSectorId['sectorName']){
                $sector = Sector::findOne(['title' =>$prisonIdAndSectorId['sectorName']]);
                $prisoner->sector_id =  $sector->primaryKey;
            }


            if ($importPhotoEnabled) {
                $originPhotoFilePattern = $originPerson['id'] . '_photo.jpg';
                $originPhotoFile = Yii::getAlias($usersModule->personPhotoPath)  . $originPhotoFilePattern;
                $originPreviewFile = Yii::getAlias($usersModule->personPreviewPath)  . $originPhotoFilePattern;
                echo $originPhotoFile;
                if (file_exists($originPhotoFile)) {
                    echo " photo EXISTS \n";
                    $prisoner->person->photo_url = $usersModule->personPhotoUrl . '/' . $originPhotoFilePattern;

                    echo $prisoner->person->photo_url . "\n";
                }
                if (file_exists($originPreviewFile)) {
                    echo " preview EXISTS \n";

                    $prisoner->person->photo_preview_url = $usersModule->personPreviewUrl . '/' . $originPhotoFilePattern;
                    echo $prisoner->person->photo_preview_url . "\n";
                }
            }
            $prisoner->save();
            echo $prisoner->person->fio;
            $cnt++;
            if ($cnt == $limit)
                    break;

        }
    }

    public static function ImportDocuments()
    {
        //$originDocuments = Yii::$app->db2->createCommand("SELECT * FROM PersonDocument  LIMIT 10 ORDER BY userId")->where(['statusId'=>self::DOCUMENT_STATUS_ACTIVE])->queryAll();
        $query = new Query();

        $query->select('*')->from('PersonDocument')->orderBy('userId')->where(['statusId' => self::DOCUMENT_STATUS_ACTIVE]);
        $originDocuments = $query->createCommand(Yii::$app->db2)->queryAll();




        foreach ($originDocuments as $originDocument) {
            //print_r($originPerson);

            if (!array_key_exists( $originDocument['docTypeId'], Document::getTypesForCombo()))
                    continue;
                $prisoner = Prisoner::findOne(['origin_id' => $originDocument['userId']]);
                if (is_null($prisoner))
                        continue;
                $document = new Document();
                $document->person_id = $prisoner->primaryKey;
                $document->type_id = $originDocument['docTypeId'];
                if (is_null($originDocument['seria']))
                        continue;
                $document->seria = $originDocument['seria'];
                $document->country = Country::findOne(['iso'=>'md']);

                $dateObj = new \DateTime();
                if (is_null($originDocument['dateIssue']) == false) {
                    $dateParts = preg_split('/\//', $originDocument['dateIssue']);
                    $dateObj->setDate($dateParts[2], $dateParts[0], $dateParts[1]);
                    $document->date_issue = $dateObj->getTimestamp();
                }

                if (is_null($originDocument['dateExpiration']) == false) {
                    $dateParts = preg_split('/\//', $originDocument['dateExpiration']);
                    $dateObj->setDate($dateParts[2], $dateParts[0], $dateParts[1]);
                    $document->date_expiration = $dateObj->getTimestamp();
                }



//                $personDocument->date_expiration = $originDocument['dateExpiration'];
                //\DateTime::
                $document->status_id = $originDocument['statusId'];

        try{
            $document->save();

        }
        //catch (IntegrityException $exception)
        catch (\Exception $exception)
        {
            print_r($exception);
        }

         if ($document->firstErrors)
              print_r($document->getFirstErrors());


        }
    }

    public static function ImportPrograms()
    {
        $query = (new Query())->select("*")->from("ProgramTable")->where("divisionId in (4,5)");
        $originSociologPrograms = ArrayHelper::map($query->createCommand(Yii::$app->db2)->queryAll(), 'id', "title");

        foreach ($originSociologPrograms as $originProgramId=>$originProgramTitle)
        {
            echo $originProgramTitle;
            $program = new ProgramDict();
            $program->origin_id = $originProgramId;
            $program->title = $originProgramTitle;

            $program->save();
        }

    }

    public static function ImportProgramPrisoner($prison_slug = 'pu-1')
    {
        $companyModel = Company::findOne(['alias'=> $prison_slug]);

        $query = (new Query())->select("*")->from("ProgramUserTable");
        $originUserPrograms = $query->createCommand(Yii::$app->db2)->queryAll();



        foreach ($originUserPrograms as $originUserProgram) {
            $prisoner = Prisoner::findOne(['origin_id' => $originUserProgram['userId']]);
            $programDict = ProgramDict::findOne(['origin_id'=>$originUserProgram['programId']]);
            if (is_null($programDict) ||is_null($prisoner))
                continue;
            $programPrisoner = new ProgramPrisoner();

            $programPrisoner->programdict_id =  $programDict->primaryKey;
            $programPrisoner->prisoner_id = $prisoner->primaryKey;//TODO:: duplicated

            $programPrisoner->prison_id = $companyModel->primaryKey;

            $programPrisoner->status_id = ProgramPrisoner::STATUS_INIT;
            $programPrisoner->save();

            print_r($programPrisoner->getFirstErrors());
            assert(!$programPrisoner->hasErrors());

            unset($programPrisoner);
            unset($prisoner);


        }

    }

    public static function ImportJobs()
    {
        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . 'prisons_dbo_JobsTable.tsv';
        $fileHandler = fopen($csvFile,'r');
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);

        while($csv->eof === false){
            $csv->read();

            $jobPaidType = new JobPaidType();
            $jobPaidType->id = $csv->getField('id');
            $jobPaidType->title = $csv->getField('title');
            $jobPaidType->compensation_id = $csv->getField('compensationCategoryId');
            $jobPaidType->category_id = $csv->getField('categoryName');
            $jobPaidType->hours_per_day = $csv->getField('hoursPerDay');
            $jobPaidType->hours_per_sa = $csv->getField('hoursPerSubb');
            $jobPaidType->save();



        }

    }


public static function ImportUserJobs()
{
    $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
    $csvFile = $dataDir . 'prisons_dbo_UserJobPaidTable.tsv';
    $fileHandler = fopen($csvFile,'r');
    $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);
    while($csv->eof === false){
        $csv->read();
        $jobPaid = new JobPaid();

        $jobPaid->prison_id = Company::findOne(['alias'=>'pu-1'])->primaryKey;
        if ($prisoner=Prisoner::findOne(['origin_id'=>$csv->getField('userId')])){
            $jobPaid->prisoner_id = $prisoner->primaryKey;
        }

        $jobPaid->type_id = $csv->getField('jobId');
        $jobPaid->month_no = $csv->getField('monthNo');
        $jobPaid->year = $csv->getField('yearNo');
        $jobPaid->half_time = $csv->getField('polstavki');

        for($i=1;$i<=31;$i++){
            $attrName = $i . 'd';
           $jobPaid->$attrName = $csv->getField($attrName);

        }

        $jobPaid->save();



    }

}


    public static function ImportNotPaidJobsTypes()
    {
        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . 'prisons_dbo_JobNotPaidTable.tsv';
        $fileHandler = fopen($csvFile,'r');
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);

        while($csv->eof === false){
            $csv->read();

            $jobPaidType = new JobNotPaidType();
            $jobPaidType->id = $csv->getField('id');
            $jobPaidType->title = $csv->getField('title');
            $jobPaidType->save();



        }

    }

    public static function ImportBalanceCategories()
    {
        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . 'prisons_dbo_balansTypeCategoryTable.tsv';
        $fileHandler = fopen($csvFile,'r');
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);

        while($csv->eof === false){
            $csv->read();

            $balanceCategory = new BalanceCategory();
            $balanceCategory->type_id = $csv->getField('btId');
            $balanceCategory->category_id = $csv->getField('id');
            $balanceCategory->title = $csv->getField('title');
            $balanceCategory->short_title = $csv->getField('shortTitle');
            $balanceCategory->save();
        }
    }

    public static function ImportBalance()
    {
        $testDate = (new \DateTime())->format('Y-m-d H:i:s.u');

        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . 'prisons_dbo_balansTable.tsv';
        $fileHandler = fopen($csvFile,'r');
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);
        while($csv->eof === false){
            $csv->read();
            $balanceCategory = BalanceCategory::findOne(['category_id' => $csv->getField('btcId')]);
            $prisoner = Prisoner::findOne(['origin_id' =>  $csv->getField('userId')]);
            if (!$prisoner)
                    continue;
            $balance = new Balance();
            $balance->type_id = $balanceCategory->type_id;
            $balance->category_id = $balanceCategory->category_id;
            $balance->prisoner_id = $prisoner->primaryKey;
            $balance->reason = $csv->getField('reason');
            $balance->amount = $csv->getField('amount');
            if ($atDate = \DateTime::createFromFormat('Y-m-d H:i:s.u' ,$csv->getField('at')))
                $balance->at = $atDate->format('Y-m-d') ;

            $balance->save();
        }

    }








}
