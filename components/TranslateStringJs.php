<?php

namespace app\components;

use yii\base\Component;
use Yii;

class TranslateStringJs extends Component {


    public static function translateJs()
    {
        return [
            'Men' => Yii::t('app','Men'),
            'Women' => Yii::t('app','Women'),
            'Activities' => Yii::t('app','Activities'),
            'Amount of people' => Yii::t('app','Amount of people'),
            'Participants' => Yii::t('app','Participants'),
            'Total:' => Yii::t('app','Total:'),
            'PARTICIPANTS BY TYPE OF PERSON' => Yii::t('app','PARTICIPANTS BY TYPE OF PERSON'),
            'Types' => Yii::t('app','Types'),
            'Amount of people' => Yii::t('app','Amount of people'),
            'PARTICIPANTS BY AGE' => Yii::t('app','PARTICIPANTS BY AGE'),
            'Ages' => Yii::t('app','Ages'),
            'PARTICIPANTS BY FISCAL YEAR' => Yii::t('app','PARTICIPANTS BY FISCAL YEAR'),
            'Years' => Yii::t('app','Years'),
            'PARTICIPANTS BY EDUCATION' => Yii::t('app','PARTICIPANTS BY EDUCATION'),
            'Education' => Yii::t('app','Education'),
            'Participants by Nationality' => Yii::t('app','Participants by Nationality'),
            'Geographic location of participants' => Yii::t('app','Geographic location of participants'),
            'Total Participants' => Yii::t('app','Total Participants'),
            'Events' => Yii::t('app','Events'),
            'Participants reached, by sex' => Yii::t('app','Participants reached, by sex'),
            'Total amount ' => Yii::t('app','Total amount '),
            'ORGANIZATIONS' => Yii::t('app','ORGANIZATIONS'),
            ' total, in ' => Yii::t('app',' total, in '),
            ' Categories' => Yii::t('app',' Categories'),
            'Organizations' => Yii::t('app','Organizations'),
            'Total participants achieved and goals, by sex' => Yii::t('app','Total participants achieved and goals, by sex'),
            'Persons' => Yii::t('app','Persons'),
            'Goals' => Yii::t('app','Goals'),
            'PARTICIPANTS BY ACTIVITY' => Yii::t('app','PARTICIPANTS BY ACTIVITY'),
          ];
    }
}
