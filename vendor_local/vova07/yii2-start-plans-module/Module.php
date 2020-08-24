<?php

namespace vova07\plans;



/**
 * Module [[Users]]
 * Yii2 users module.
 */
class Module extends \vova07\base\components\Module
{
    const PLAN_ROLE_LABEL_EDUCATOR = 'Educativ';
    const PLAN_ROLE_LABEL_PSICHOLOGIST = 'Psihologic';
    const PLAN_ROLE_LABEL_SOCIOLOGINST = 'Asistenţă socială';
    const PLAN_ROLE_LABEL_SUPERADMIN = 'superadmin';
    const PLAN_LABELS_REQUIREMENTS = "Nevoi";
    const PLAN_LABELS_PROGRAMS_REQUIREMENT = "Programe şi activităţi obligatorii";


    /**
     * @deprecated Use PlanItemGroup title
     */
    public static function getPlanLabels($key){
        $ret = [
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR => self::PLAN_ROLE_LABEL_EDUCATOR,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST => self::PLAN_ROLE_LABEL_PSICHOLOGIST,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST=> self::PLAN_ROLE_LABEL_SOCIOLOGINST,
            \vova07\rbac\Module::ROLE_SUPERADMIN=> self::PLAN_ROLE_LABEL_SUPERADMIN,

        ];
        if ($key)
            return $ret[$key];
        else
            return $ret;
    }


}
