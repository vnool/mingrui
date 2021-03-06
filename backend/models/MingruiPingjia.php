<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "mingrui_pingjia".
 *
 * @property string $id
 * @property string $report_id
 * @property string $uid
 * @property integer $pingjia
 * @property string $linchuang
 * @property string $createtime
 */
class MingruiPingjia extends \yii\db\ActiveRecord
{

    public static $pingjiaText =
        [
        1 => ['key' => 'fa-question-circle', 'label' => '疑似阳性', 'description' => '缺乏家系验证；致病性不明确'],
        2 => ['key' => 'fa-plus-circle', 'label' => '阳性', 'description' => '基因型和临床表型相符'],
        3 => ['key' => 'fa-plus-circle|fa-thumbs-o-up', 'label' => '阳性+好案例', 'description' => '罕见病例；出乎意料'],
        4 => ['key' => 'fa-minus-circle', 'label' => '阴性', 'description' => '基因无发现'],
        5 => ['key' => 'fa-minus-circle|fa-thumbs-o-up', 'label' => '阴性+特殊案例', 'description' => '临床诊断明确，基因无突变'],
        6 => ['key' => ' x', 'label' => '自定义'],
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mingrui_pingjia';
    }
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'createtime',
                'updatedAtAttribute' => false,
                'value'              => new Expression('NOW()'),
            ],
        ];
    }
    public static function getTextArray()
    {
        $text = [];
        foreach (self::$pingjiaText as $key => $value) {
            $icon       = "<i class='fa " . $value['key'] . "'></i>";
            $text[$key] = $icon . $value['label'] . ' ';
        }
        return $text;
    }
    public static function getSimpleArray()
    {
        $text = ['' => '全部'];
        foreach (self::$pingjiaText as $key => $value) {
            $text[$key] = $value['label'] . ' ';
        }
        return $text;
    }
    public function getTxt()
    {
        if ($this->pingjia == count(self::$pingjiaText)) {
            return $this->pingjiaDiy;
        }
        if ($this->pingjia) {
            $jo = self::$pingjiaText[$this->pingjia];
            return $jo['label'];
        }

    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_id'], 'required'],
            [['report_id', 'sample_id', 'uid', 'pingjia'], 'integer'],
            [['linchuang'], 'string'],
            [['createtime', 'pingjiaDiy'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'report_id'  => 'Report ID',
            'sample_id'  => 'sample_id',
            'uid'        => 'Uid',
            'pingjia'    => 'Pingjia',
            'linchuang'  => '临床',
            'createtime' => 'Createtime',
        ];
    }
}
