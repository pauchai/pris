<?php
return [
    'accessBackend' => [
        'type' => 2,
        'description' => 'Can access backend',
    ],
    'administrateRbac' => [
        'type' => 2,
        'description' => 'Can administrate all "RBAC" module',
        'children' => [
            'BViewRoles',
            'BCreateRoles',
            'BUpdateRoles',
            'BDeleteRoles',
            'BViewPermissions',
            'BCreatePermissions',
            'BUpdatePermissions',
            'BDeletePermissions',
            'BViewRules',
            'BCreateRules',
            'BUpdateRules',
            'BDeleteRules',
        ],
    ],
    'BViewRoles' => [
        'type' => 2,
        'description' => 'Can view roles list',
    ],
    'BCreateRoles' => [
        'type' => 2,
        'description' => 'Can create roles',
    ],
    'BUpdateRoles' => [
        'type' => 2,
        'description' => 'Can update roles',
    ],
    'BDeleteRoles' => [
        'type' => 2,
        'description' => 'Can delete roles',
    ],
    'BViewPermissions' => [
        'type' => 2,
        'description' => 'Can view permissions list',
    ],
    'BCreatePermissions' => [
        'type' => 2,
        'description' => 'Can create permissions',
    ],
    'BUpdatePermissions' => [
        'type' => 2,
        'description' => 'Can update permissions',
    ],
    'BDeletePermissions' => [
        'type' => 2,
        'description' => 'Can delete permissions',
    ],
    'BViewRules' => [
        'type' => 2,
        'description' => 'Can view rules list',
    ],
    'BCreateRules' => [
        'type' => 2,
        'description' => 'Can create rules',
    ],
    'BUpdateRules' => [
        'type' => 2,
        'description' => 'Can update rules',
    ],
    'BDeleteRules' => [
        'type' => 2,
        'description' => 'Can delete rules',
    ],
    'SocReintegrationDepartmentHead' => [
        'type' => 1,
        'children' => [
            'SocReintegrationDepartmentEducator',
            'BPrisonerListRules',
            'PrisonerPlanView',
            'PrisonerPlanRequirementsPlaning',
            'PrisonerPlanProgramsPlaning',
            'ProgramPlaningList',
            'BPermissionFinancesAccess',
            'BPermissionFinancesListRemainOnly',
            'BPermissionElectricityAccess',
        ],
    ],
    'SocReintegrationDepartmentEducator' => [
        'type' => 1,
        'children' => [
            'SocReintegrationDepartmentExpert',
            'BPrisonerListRules',
        ],
    ],
    'SocReintegrationDepartmentExpert' => [
        'type' => 1,
        'children' => [
            'BPrisonerListRules',
            'BPermissionEventPlaningList',
            'BPermissionEventPlaningCreate',
            'BPermissionEventPlaningUpdate',
            'BPermissionEventPlaningDelete',
            'BPermissionEventPlaningView',
            'BPermissionPrisonerSecurityList',
            'BPermissionPrisonerSecurityView',
            'BPermissionPrisonerSecurityCreate',
            'BPermissionPrisonerSecurityUpdate',
            'BPermissionPrisonerSecurityDelete',
            'BPermissionCommitteeList',
            'BPermissionCommitteeView',
            'BPermissionCommitteeCreate',
            'BPermissionCommitteeUpdate',
            'BPermissionCommitteeDelete',
            'PrisonerPlanView',
            'PrisonerPlanRequirementsPlaning',
            'PrisonerPlanProgramsPlaning',
            'ProgramPlaningList',
            'BPermissionDocumentCreate',
            'BPermissionDocumentUpdate',
            'BPermissionDocumentDelete',
            'BPermissionDocumentView',
            'BPermissionHumanitarianList',
            'BPermissionHumanitarianView',
            'BPermissionHumanitarianCreate',
            'BPermissionHumanitarianUpdate',
            'BPermissionHumanitarianDelete',
            'BPermissionJobsAccess',
            'BPermissionPaidJobsList',
            'BPermissionPaidJobsCreate',
            'BPermissionPaidJobsUpdate',
            'BPermissionPaidJobsDelete',
            'BPermissionPaidJobsView',
            'BPermissionNotPaidJobsList',
            'BPermissionNotPaidJobCreate',
            'BPermissionNotPaidJobUpdate',
            'BPermissionNotPaidJobDelete',
            'BPermissionNotPaidJobView',
            'BPermissionFinancesAccess',
            'BPermissionFinancesListRemainOnly',
        ],
    ],
    'SocReintegrationDepartmentSociologist' => [
        'type' => 1,
        'children' => [
            'BPrisonerListRules',
            'BPermissionEventPlaningList',
            'BPermissionEventPlaningCreate',
            'BPermissionEventPlaningUpdate',
            'BPermissionEventPlaningDelete',
            'BPermissionEventPlaningView',
            'PrisonerPlanView',
            'BPermissionDocumentsList',
            'BPermissionDocumentCreate',
            'BPermissionDocumentUpdate',
            'BPermissionDocumentDelete',
            'BPermissionDocumentView',
            'BPermissionFinancesAccess',
            'BPermissionFinancesListRemainOnly',
        ],
    ],
    'SocReintegrationDepartmentPsychologist' => [
        'type' => 1,
        'children' => [
            'BPrisonerListRules',
            'BPermissionEventPlaningList',
            'BPermissionEventPlaningCreate',
            'BPermissionEventPlaningUpdate',
            'BPermissionEventPlaningDelete',
            'BPermissionEventPlaningView',
            'PrisonerPlanView',
        ],
    ],
    'FinanceDepartmentHead' => [
        'type' => 1,
    ],
    'FinanceDepartmentExpert' => [
        'type' => 1,
        'children' => [
            'BPrisonerListRules',
            'BPermissionFinancesAccess',
            'BPermissionFinancesList',
        ],
    ],
    'LogisticAndAdministrationDepartmentHead' => [
        'type' => 1,
    ],
    'LogisticAndAdministrationDepartmentExpert' => [
        'type' => 1,
    ],
    'CompanyHead' => [
        'type' => 1,
        'description' => 'Company Head',
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
    ],
    'superadmin' => [
        'type' => 1,
        'description' => 'Super admin',
        'children' => [
            'admin',
            'accessBackend',
            'administrateRbac',
            'SocReintegrationDepartmentHead',
            'SocReintegrationDepartmentExpert',
            'SocReintegrationDepartmentPsychologist',
            'SocReintegrationDepartmentSociologist',
            'SocReintegrationDepartmentEducator',
            'FinanceDepartmentExpert',
            'FinanceDepartmentHead',
            'LogisticAndAdministrationDepartmentExpert',
            'LogisticAndAdministrationDepartmentHead',
        ],
    ],
    'BPrisonerListRules' => [
        'type' => 2,
        'description' => 'BPrisonerListRules_DESCRIPTION',
    ],
    'BPermissionEventPlaningList' => [
        'type' => 2,
        'description' => 'BPermissionEventPlaningList_DESCRIPTION',
    ],
    'BPermissionEventPlaningCreate' => [
        'type' => 2,
        'description' => 'BPermissionEventPlaningCreate_DESCRIPTION',
    ],
    'BPermissionEventPlaningUpdate' => [
        'type' => 2,
        'description' => 'BPermissionEventPlaningUpdate_DESCRIPTION',
    ],
    'BPermissionEventPlaningDelete' => [
        'type' => 2,
        'description' => 'BPermissionEventPlaningDelete_DESCRIPTION',
    ],
    'BPermissionEventPlaningView' => [
        'type' => 2,
        'description' => 'BPermissionEventPlaningView_DESCRIPTION',
    ],
    'BPermissionPrisonerSecurityList' => [
        'type' => 2,
        'description' => 'BPermissionPrisonerSecurityList_DESCRIPTION',
    ],
    'BPermissionPrisonerSecurityView' => [
        'type' => 2,
        'description' => 'BPermissionPrisonerSecurityView_DESCRIPTION',
    ],
    'BPermissionPrisonerSecurityCreate' => [
        'type' => 2,
        'description' => 'BPermissionPrisonerSecurityCreate_DESCRIPTION',
    ],
    'BPermissionPrisonerSecurityUpdate' => [
        'type' => 2,
        'description' => 'BPermissionPrisonerSecurityUpdate_DESCRIPTION',
    ],
    'BPermissionPrisonerSecurityDelete' => [
        'type' => 2,
        'description' => 'BPermissionPrisonerSecurityDelete_DESCRIPTION',
    ],
    'BPermissionCommitteeList' => [
        'type' => 2,
        'description' => 'BPermissionCommitteeList_DESCRIPTION',
    ],
    'BPermissionCommitteeView' => [
        'type' => 2,
        'description' => 'BPermissionCommitteeView_DESCRIPTION',
    ],
    'BPermissionCommitteeCreate' => [
        'type' => 2,
        'description' => 'BPermissionCommitteeCreate_DESCRIPTION',
    ],
    'BPermissionCommitteeUpdate' => [
        'type' => 2,
        'description' => 'BPermissionCommitteeUpdate_DESCRIPTION',
    ],
    'BPermissionCommitteeDelete' => [
        'type' => 2,
        'description' => 'BPermissionCommitteeDelete_DESCRIPTION',
    ],
    'PrisonerPlanView' => [
        'type' => 2,
        'description' => 'PrisonerPlanView_DESCRIPTION',
    ],
    'PrisonerPlanRequirementsPlaning' => [
        'type' => 2,
        'description' => 'PrisonerPlanRequirementsPlaning_DESCRIPTION',
    ],
    'PrisonerPlanProgramsPlaning' => [
        'type' => 2,
        'description' => 'PrisonerPlanProgramsPlaning_DESCRIPTION',
    ],
    'ProgramPlaningList' => [
        'type' => 2,
        'description' => 'ProgramPlaningList_DESCRIPTION',
    ],
    'BPermissionDocumentsList' => [
        'type' => 2,
        'description' => 'BPermissionDocumentsList_DESCRIPTION',
    ],
    'BPermissionDocumentCreate' => [
        'type' => 2,
        'description' => 'BPermissionDocumentCreate_DESCRIPTION',
    ],
    'BPermissionDocumentUpdate' => [
        'type' => 2,
        'description' => 'BPermissionDocumentUpdate_DESCRIPTION',
    ],
    'BPermissionDocumentDelete' => [
        'type' => 2,
        'description' => 'BPermissionDocumentDelete_DESCRIPTION',
    ],
    'BPermissionDocumentView' => [
        'type' => 2,
        'description' => 'BPermissionDocumentView_DESCRIPTION',
    ],
    'BPermissionHumanitarianList' => [
        'type' => 2,
        'description' => 'BPermissionHumanitarianList_DESCRIPTION',
    ],
    'BPermissionHumanitarianView' => [
        'type' => 2,
        'description' => 'BPermissionHumanitarianView_DESCRIPTION',
    ],
    'BPermissionHumanitarianCreate' => [
        'type' => 2,
        'description' => 'BPermissionHumanitarianCreate_DESCRIPTION',
    ],
    'BPermissionHumanitarianUpdate' => [
        'type' => 2,
        'description' => 'BPermissionHumanitarianUpdate_DESCRIPTION',
    ],
    'BPermissionHumanitarianDelete' => [
        'type' => 2,
        'description' => 'BPermissionHumanitarianDelete_DESCRIPTION',
    ],
    'BPermissionJobsAccess' => [
        'type' => 2,
        'description' => 'BPermissionJobsAccess_DESCRIPTION',
    ],
    'BPermissionPaidJobsList' => [
        'type' => 2,
        'description' => 'BPermissionPaidJobsList_DESCRIPTION',
    ],
    'BPermissionPaidJobsCreate' => [
        'type' => 2,
        'description' => 'BPermissionPaidJobsCreate_DESCRIPTION',
    ],
    'BPermissionPaidJobsUpdate' => [
        'type' => 2,
        'description' => 'BPermissionPaidJobsUpdate_DESCRIPTION',
    ],
    'BPermissionPaidJobsDelete' => [
        'type' => 2,
        'description' => 'BPermissionPaidJobsDelete_DESCRIPTION',
    ],
    'BPermissionPaidJobsView' => [
        'type' => 2,
        'description' => 'BPermissionPaidJobsView_DESCRIPTION',
    ],
    'BPermissionNotPaidJobsList' => [
        'type' => 2,
        'description' => 'BPermissionNotPaidJobsList_DESCRIPTION',
    ],
    'BPermissionNotPaidJobCreate' => [
        'type' => 2,
        'description' => 'BPermissionNotPaidJobCreate_DESCRIPTION',
    ],
    'BPermissionNotPaidJobUpdate' => [
        'type' => 2,
        'description' => 'BPermissionNotPaidJobUpdate_DESCRIPTION',
    ],
    'BPermissionNotPaidJobDelete' => [
        'type' => 2,
        'description' => 'BPermissionNotPaidJobDelete_DESCRIPTION',
    ],
    'BPermissionNotPaidJobView' => [
        'type' => 2,
        'description' => 'BPermissionNotPaidJobView_DESCRIPTION',
    ],
    'BPermissionFinancesAccess' => [
        'type' => 2,
        'description' => 'BPermissionFinancesAccess_DESCRIPTION',
    ],
    'BPermissionFinancesList' => [
        'type' => 2,
        'description' => 'BPermissionFinancesList_DESCRIPTION',
        'children' => [
            'BPermissionFinancesListRemainOnly',
        ],
    ],
    'BPermissionFinancesListRemainOnly' => [
        'type' => 2,
        'description' => 'BPermissionFinancesListRemainOnly_DESCRIPTION',
    ],
    'BPermissionElectricityAccess' => [
        'type' => 2,
        'description' => 'BPermissionElectricityAccess_DESCRIPTION',
        'children' => [
            'BPermissionElectricityList',
            'BPermissionElectricityCreate',
            'BPermissionElectricityUpdate',
            'BPermissionElectricityDelete',
            'BPermissionElectricityView',
        ],
    ],
    'BPermissionElectricityList' => [
        'type' => 2,
        'description' => 'BPermissionElectricityList_DESCRIPTION',
    ],
    'BPermissionElectricityCreate' => [
        'type' => 2,
        'description' => 'BPermissionElectricityCreate_DESCRIPTION',
    ],
    'BPermissionElectricityUpdate' => [
        'type' => 2,
        'description' => 'BPermissionElectricityUpdate_DESCRIPTION',
    ],
    'BPermissionElectricityDelete' => [
        'type' => 2,
        'description' => 'BPermissionElectricityDelete_DESCRIPTION',
    ],
    'BPermissionElectricityView' => [
        'type' => 2,
        'description' => 'BPermissionElectricityView_DESCRIPTION',
    ],
];
