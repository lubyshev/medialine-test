<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Новости.
 *
 * @property integer $id
 * @property string  $title
 * @property string  $text
 * @property integer $ownerId
 * @property string  $createdAt
 * @property string  $updatedAt
 */
class News extends ActiveRecord
{

}
