<?php

namespace App\Enums;

/**
 * @property string FREE_TEXT
 * @property string SELECT_ONE
 * @property string SELECT_MANY
 */
class QuestionTypeEnum
{
    const FREE_TEXT = "Free Text";
    const YES_NO = "Yes/No";
    const SELECT_ONE = "Select One";
    const SELECT_MANY = "Select Many";
    const ALL = [
        self::FREE_TEXT,
        self::YES_NO,
        self::SELECT_ONE,
        self::SELECT_MANY,
    ];
}
