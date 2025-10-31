<?php

namespace App\Enums;

class PermissionEnum
{
    const MANAGE_USERS      = "manage users";
    const MANAGE_COUNTRIES  = "manage countries";
    const MANAGE_NURSES     = "manage nurses";
    const MANAGE_HOSPITALS  = "manage hospitals";
    const VIEW_HOSPITALS  = "view hospitals";
    const MANAGE_PHYSICIANS = "manage physicians";
    const MANAGE_CLIENTS    = "manage clients";
    const VIEW_CLIENTS    = "view clients";
    const MANAGE_PROGRAMS   = "manage programs";
    const VIEW_PROGRAMS   = "view programs";
    const MANAGE_SUBPROGRAMS   = "manage sub programs";
    const VIEW_SUBPROGRAMS   = "view sub programs";
    const MANAGE_Distributors   = "manage distributors";
    const VIEW_Distributors   = "view distributors";
    const MANAGE_PATIENTS   = "manage patients";
    const VIEW_PATIENTS   = "view patients";
    const MANAGE_DOCUMENTS  = "manage documents";
    const MANAGE_VISITS     = "manage visits";

    const VIEW_VISITS     = "view visits";
    const MANAGE_QUESTIONS  = "manage questions";
    const VIEW_QUESTIONS  = "view questions";
    const MANAGE_PHARMACIES = "manage pharmacies";
    const VIEW_PHARMACIES = "view pharmacies";
    const MANAGE_DRUGS      = "manage drugs";
    const VIEW_DRUGS      = "view drugs";
    const PatientConsent      = "manage PatientConsent";

    const MANAGE_ServiceProvider      = "manage ServiceProvider";
    const VIEW_ServiceProvider      = "view ServiceProvider";

    const VIEW_ShowTimeLine      = "view visits ShowTimeLine";

    const MANAGE_DOCTORS      = "manage doctors";
    const VIEW_DOCTORS      = "view doctors";

    const MANAGE_FOC      = "manage FOC";
    const VIEW_FOC      = "view FOC";
    const VIEW_FOC_Visits_ShowTimeLine      = "FOC Visits ShowTimeLine";

    const MANAGE_SafetyReport      = "manage Safety Report";
    const VIEW_SafetyReport      = "view Safety Report";
}
