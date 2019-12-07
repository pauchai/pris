<?php namespace common\tests\unit;

use vova07\base\models\Item;
use vova07\countries\models\Country;
use vova07\users\models\Ident;
use vova07\users\models\User;

class ActiveRecordTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }


    public function testRelations()
    {

        $item = new Item();
        $item->save();

        $ident = new Ident();
        $ident->link('item', $item);
        $item_id = $item->id;
        unset($ident, $item);

        $ident = Ident::findOne($item_id);
        $itemModel = $ident->item;
        expect("", $itemModel)->isInstanceOf(Item::class);
        unset($ident, $itemModel);

    }



    private function testExtraAttributes()
    {
        $faker =   \Faker\Factory::create('ro_RO');


        $country = new Country();
        $country->title = $faker->country;
        $country->iso = $faker->countryCode;
        $country->noteExistsAttribute = $faker->name;
        expect("country save with validation true", $country->save(true))->true();
        expect("country save without validateion true", $country->save(false))->true();
    }






}