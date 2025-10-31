<?php

namespace App\Enums;

class ProgramTypeEnum {
    const EDUC   = "Education";
    const ADHERENCE  = "Adherence";
    const DRUGADMIN  = "Drug Administration";
    const FOC  = "FOC";
    const COPAY  = "Co-pay";
    const PHYSIOCONS  = "Physiotherapy Consultation";
    const PSYCHOCONS  = "Psychology Consultation";
    const NUTCONS  = "Nutritionist consultation";
    const ORTHOCONS  = "Orthophoria Consultation";
    const ERGO  = "Ergo therapist";

    const ALL = [
        self::EDUC,
        self::ADHERENCE,
        self::DRUGADMIN,
        self::FOC,
        self::COPAY,
        self::PHYSIOCONS,
        self::PSYCHOCONS,
        self::NUTCONS,
        self::ORTHOCONS,
        self::ERGO,
    ];
}
