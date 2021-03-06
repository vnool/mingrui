<?php

namespace backend\models;

use Yii;
use common\models\User;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "mingrui_reportstore".
 *
 * @property string $id
 * @property string $uid
 * @property string $sick
 * @property integer $age
 * @property string $sex
 * @property string $product
 * @property string $tel
 * @property string $diagnose
 * @property string $gene
 * @property string $pingjia
 * @property string $attachements
 * @property string $createtime
 */
class MingruiReportstore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mingrui_reportstore';
    }
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'createtime',
                'updatedAtAttribute' => false,
                //'value'              => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'pingjia', 'createtime'], 'integer'],
            [['diagnose', 'sex','attachements'], 'string'],
            [['age'], 'number'],
            [['sick', 'tel', 'gene'], 'string', 'max' => 64],
            [['product'], 'string', 'max' => 128],
            [['extra1','extra2','extra3'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '医生的id',
            'sick' => '姓名',
            'age' => '年龄',
            'sex' => '性别',
            'product' => '检测项目',
            'tel' => '联系电话',
            'diagnose' => '临床诊断',
            'gene' => '基因型',
            'pingjia' => '星级评价',
            'attachements' => '报告',
            'createtime' => '时间',
            'extra1'=>'临床症状',
            'extra2'=>'extra2',
            'extra3'=>'extra3',
        ];
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }    

}
