<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Новости.
 *
 * @property integer  $id
 * @property string   $title
 * @property integer  $subtree
 * @property integer  $level
 * @property integer  $left
 * @property integer  $right
 * @property ?integer $parentId
 * @property integer  $ownerId
 * @property string   $createdAt
 * @property string   $updatedAt
 */
class Category extends ActiveRecord
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function formName()
    {
        return '';
    }

    public function initCategory(array $category): self
    {
        $this->setAttributes($category, false);

        return $this;
    }

    public function beforeSave($insert)
    {
        $this->updatedAt = date('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public static function tableName()
    {
        return '{{%categories}}';
    }

    public function parent(): ?self
    {
        return self::findOne(['id' => $this->parentId]);
    }

}
