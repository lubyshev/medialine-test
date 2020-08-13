<?php
declare(strict_types=1);

use app\models\Category;

/** @var Category[] $parents */
/** @var Category $item */

?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
      <?php foreach ($parents as $parent): ?>
        <li class="breadcrumb-item">
          <a href="#" onclick="app.loadCategory(event, <?= $parent->id ?>)"><?= $parent->title ?></a>
        </li>
      <?php endforeach; ?>
    <li class="breadcrumb-item">
      <a href="#" onclick="app.loadCategory(event, <?= $item->id ?>)"><?= $item->title ?></a>
    </li>
  </ol>
</nav>
