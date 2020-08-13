<?php
declare(strict_types=1);

namespace app\models;

use app\repositories\NewsRepository;

/**
 * Список новостей.
 *
 * @property integer $id
 * @property string  $title
 * @property string  $text
 * @property integer $ownerId
 * @property string  $createdAt
 * @property string  $updatedAt
 * @property string  $date
 * @property string  $breadcrumbs
 */
class NewsList extends News
{
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['date', 'breadcrumbs', 'userName']
        );
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        $fields['breadcrumbs'] = function () {
            return (new NewsRepository())->getBreadcrumbs($this);
        };

        $fields['date'] = function () {
            return (new \DateTimeImmutable($this->createdAt))->format('d.m.Y H:i');
        };

        $fields['userName'] = function () {
            return (User::findIdentity($this->ownerId))->username;
        };

        return $fields;
    }

}
