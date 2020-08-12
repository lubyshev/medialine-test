<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Список новостей.
 *
 * @property integer $id
 * @property string  $title
 * @property string  $text
 * @property integer $ownerId
 * @property string  $createdAt
 * @property string  $updatedAt
 */
class NewsList extends News
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['date'];
        return $fields;
    }

}
