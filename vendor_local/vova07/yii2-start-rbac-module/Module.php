<?php

namespace vova07\rbac;

use Yii;

/**
 * Module [[RBAC]]
 * Yii2 RBAC module.
 */
class Module extends \vova07\base\components\Module
{
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD = 'SocReintegrationDepartmentHead';
    const ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR = 'SocReintegrationDepartmentEducator';
    const ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT = 'SocReintegrationDepartmentExpert';
    const ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST = 'SocReintegrationDepartmentSociologist';
    const ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST = 'SocReintegrationDepartmentPsychologist';
    const ROLE_FINANCE_DEPARTMENT_HEAD = 'FinanceDepartmentHead';
    const ROLE_FINANCE_DEPARTMENT_EXPERT = 'FinanceDepartmentExpert';
    const ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_HEAD = 'LogisticAndAdministrationDepartmentHead';
    const ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT = 'LogisticAndAdministrationDepartmentExpert';
    const ROLE_COMPANY_HEAD = 'CompanyHead';

    const PERMISSION_ACCESS_BACKEND = 'accessBackend';
    const PERMISSION_ADMINISTRATE_RBAC = 'administrateRbac';
    const PERMISSION_VIEW_ROLES = 'BViewRoles';
    const PERMISSION_CREATE_ROLES = 'BCreateRoles';
    const PERMISSION_UPDATE_ROLES = 'BUpdateRoles';
    const PERMISSION_DELETE_ROLES = 'BDeleteRoles';
    const PERMISSION_VIEW_PERMISSIONS = 'BViewPermissions';
    const PERMISSION_CREATE_PERMISSIONS = 'BCreatePermissions';
    const PERMISSION_UPDATE_PERMISSIONS = 'BUpdatePermissions';
    const PERMISSION_DELETE_PERMISSIONS = 'BDeletePermissions';
    const PERMISSION_CREATE_RULES = 'BCreateRules';
    const PERMISSION_UPDATE_RULES = 'BUpdateRules';
    const PERMISSION_DELETE_RULES = 'BDeleteRules';
    const PERMISSION_VIEW_RULES = 'BViewRules';

    public const PERMISSION_PRISONERS_LIST = "BPrisonerListRules";
    public const PERMISSION_PRISONERS_DELETE = "BPrisonerDelete";
    public const PERMISSION_PRISONERS_UPDATE = "BPrisonerUpdate";
    public const PERMISSION_PRISONERS_VIEW = "BPrisonerVeiw";

    public const PERMISSION_PROGRAM_PLANING_CREATE = 'ProgramPlaningCreate';
    public const PERMISSION_PROGRAM_PLANING_DELETE = 'ProgramPlaningDelete';
    public const PERMISSION_PROGRAM_PLANING_LIST = 'ProgramPlaningList';
    public const PERMISSION_PROGRAM_PLANING_UPDATE = 'ProgramPlaningUpdate';
    public const PERMISSION_PROGRAM_PLANING_VIEW = 'ProgramPlaningView';

    public const PERMISSION_PROGRAM_LIST = 'ProgramList';

    public const PERMISSION_PRISONER_PLAN_VIEW = 'PrisonerPlanView';
    public const PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING = 'PrisonerPlanProgramsPlaning';
    public const PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING = 'PrisonerPlanRequirementsPlaning';


    public const PERMISSION_EVENT_PLANING_LIST = 'BPermissionEventPlaningList';
    public const PERMISSION_EVENT_PLANING_CREATE = 'BPermissionEventPlaningCreate';
    public const PERMISSION_EVENT_PLANING_UPDATE = 'BPermissionEventPlaningUpdate';
    public const PERMISSION_EVENT_PLANING_VIEW = 'BPermissionEventPlaningView';
    public const PERMISSION_EVENT_PLANING_DELETE = 'BPermissionEventPlaningDelete';

    public const PERMISSION_PRISONERS_SECURITY_LIST = 'BPermissionPrisonerSecurityList';
    public const PERMISSION_PRISONERS_SECURITY_CREATE = 'BPermissionPrisonerSecurityCreate';
    public const PERMISSION_PRISONERS_SECURITY_UPDATE = 'BPermissionPrisonerSecurityUpdate';
    public const PERMISSION_PRISONERS_SECURITY_VIEW = 'BPermissionPrisonerSecurityView';
    public const PERMISSION_PRISONERS_SECURITY_DELETE = 'BPermissionPrisonerSecurityDelete';

    public const PERMISSION_COMMITTIEE_LIST = 'BPermissionCommitteeList';
    public const PERMISSION_COMMITTIEE_CREATE = 'BPermissionCommitteeCreate';
    public const PERMISSION_COMMITTIEE_UPDATE = 'BPermissionCommitteeUpdate';
    public const PERMISSION_COMMITTIEE_VIEW = 'BPermissionCommitteeView';
    public const PERMISSION_COMMITTIEE_DELETE = 'BPermissionCommitteeDelete';

    public const PERMISSION_DOCUMENTS_LIST = 'BPermissionDocumentsList';
    public const PERMISSION_DOCUMENT_CREATE = 'BPermissionDocumentCreate';
    public const PERMISSION_DOCUMENT_UPDATE = 'BPermissionDocumentUpdate';
    public const PERMISSION_DOCUMENT_VIEW = 'BPermissionDocumentView';
    public const PERMISSION_DOCUMENT_DELETE = 'BPermissionDocumentDelete';

    public const PERMISSION_HUMANITARIAN_LIST = 'BPermissionHumanitarianList';
    public const PERMISSION_HUMANITARIAN_VIEW = 'BPermissionHumanitarianView';
    public const PERMISSION_HUMANITARIAN_CREATE = 'BPermissionHumanitarianCreate';
    public const PERMISSION_HUMANITARIAN_UPDATE = 'BPermissionHumanitarianUpdate';
    public const PERMISSION_HUMANITARIAN_DELETE = 'BPermissionHumanitarianDelete';

    public const PERMISSION_JOBS_ACCESS = 'BPermissionJobsAccess';
    public const PERMISSION_PAID_JOBS_LIST = 'BPermissionPaidJobsList';
    public const PERMISSION_PAID_JOB_CREATE = 'BPermissionPaidJobsCreate';
    public const PERMISSION_PAID_JOB_UPDATE = 'BPermissionPaidJobsUpdate';
    public const PERMISSION_PAID_JOB_DELETE = 'BPermissionPaidJobsDelete';
    public const PERMISSION_PAID_JOB_VIEW = 'BPermissionPaidJobsView';

    public const PERMISSION_NOT_PAID_JOBS_LIST = 'BPermissionNotPaidJobsList';
    public const PERMISSION_NOT_PAID_JOB_CREATE = 'BPermissionNotPaidJobCreate';
    public const PERMISSION_NOT_PAID_JOB_UPDATE = 'BPermissionNotPaidJobUpdate';
    public const PERMISSION_NOT_PAID_JOB_DELETE = 'BPermissionNotPaidJobDelete';
    public const PERMISSION_NOT_PAID_JOB_VIEW = 'BPermissionNotPaidJobView';



    public const PERMISSION_FINANCES_ACCESS = 'BPermissionFinancesAccess';
    public const PERMISSION_FINANCES_LIST = 'BPermissionFinancesList';
    public const PERMISSION_FINANCES_LIST_REMAIN_ONLY = 'BPermissionFinancesListRemainOnly';



    public const PERMISSION_ELECTRICITY_ACCESS = 'BPermissionElectricityAccess';
    public const PERMISSION_ELECTRICITY_CREATE = 'BPermissionElectricityCreate';
    public const PERMISSION_ELECTRICITY_UPDATE = 'BPermissionElectricityUpdate';
    public const PERMISSION_ELECTRICITY_DELETE = 'BPermissionElectricityDelete';
    public const PERMISSION_ELECTRICITY_LIST = 'BPermissionElectricityList';
    public const PERMISSION_ELECTRICITY_VIEW = 'BPermissionElectricityView';

    public const PERMISSION_PROGRAM_PRISONERS_COMMENT_CREATE = "BPermissionProgramPrisonersCommentCreate";

    public const PERMISSION_PSYCHO_LIST = "BPermissionPsychoList";


    public const PERMISSION_QUICK_SWITCH_USER = "BPermissionQuickSwitchUser";


    public const PERMISSION_CONCEPTS_LIST = 'BPermissionConceptList';
    public const PERMISSION_CONCEPT_CREATE = 'BPermissionConceptCreate';
    public const PERMISSION_CONCEPT_UPDATE = 'BPermissionConceptUpdate';
    public const PERMISSION_CONCEPT_VIEW = 'BPermissionConceptView';
    public const PERMISSION_CONCEPT_DELETE = 'BPermissionConcepteDelete';

    public const PERMISSION_CONCEPT_PARTICIPANT_LIST = 'BPermissionConceptParticipantList';
    public const PERMISSION_CONCEPT_PARTICIPANT_CREATE = 'BPermissionConceptParticipantCreate';
    public const PERMISSION_CONCEPT_PARTICIPANT_DELETE = 'BPermissionConceptParticipantDelete';

    public const PERMISSION_CONCEPT_CLASS_CREATE = 'BPermissionConceptClassCreate';
    public const PERMISSION_CONCEPT_CLASS_DELETE = 'BPermissionConceptClassDelete';

    public const PERMISSION_CONCEPT_VISIT_CREATE = 'BPermissionConceptVisitCreate';
    public const PERMISSION_CONCEPT_VISIT_DELETE = 'BPermissionConceptVisitDelete';



    /**
     * @inheritdoc
     */
    public static $name = 'rbac';
}
