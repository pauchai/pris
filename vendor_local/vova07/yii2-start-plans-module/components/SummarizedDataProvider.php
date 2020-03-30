<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 3/27/20
 * Time: 8:49 AM
 */

namespace vova07\plans\components;


use vova07\plans\models\SummarizedModel;
use yii\data\BaseDataProvider;

class SummarizedDataProvider extends BaseDataProvider
{
    public $from;
    public $to;
    public $limit = 7;
    public $format = 'Y-m-d';

    protected function prepareModels()
    {
        $models = [];
        $pagination = $this->getPagination();

            if (is_null($this->from))
                $fromDate = (new \DateTime)->setTimestamp(time());
            else
                $fromDate = \DateTime::createFromFormat($this->format, $this->from);

            if (is_null($this->to))
                $toDate = (clone $fromDate)->add(new \DateInterval('P'. $this->limit . 'D'));
            else{
                $toDate = \DateTime::createFromFormat($this->format, $this->to)->add(new \DateInterval('P1D'));
            }

            $period = new \DatePeriod($fromDate,  new  \DateInterval('P1D'), $toDate);



            foreach ($period as $dateTime){
                $models[$dateTime->format($this->format)] = new SummarizedModel([
                    'at' => $dateTime->format($this->format)
                ]);
            }


        return $models;
    }
    /**
     * {@inheritdoc}
     */
    protected function prepareKeys($models)
    {

            return array_keys($models);

    }
    /**
     * {@inheritdoc}
     */
    protected function prepareTotalCount()
    {
        if (is_null($this->from))
            $fromDate = (new \DateTime)->setTimestamp(time());
        else
            $fromDate = \DateTime::createFromFormat($this->format, $this->from);

        if (is_null($this->to))
            $toDate = (clone $fromDate)->add(new \DateInterval('P'. $this->limit . 'D'));
        else
            $toDate = \DateTime::createFromFormat($this->format, $this->to)->add(new \DateInterval('P1D'));

        $period = new \DatePeriod($fromDate,  new  \DateInterval('P1D'), $toDate);
       return $period->recurrences;

    }


}