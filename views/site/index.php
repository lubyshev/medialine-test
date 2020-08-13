<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

  <div id="jumbotron" class="jumbotron">
    <h1>Новости</h1>
    <p class="lead">Последние новости, события, факты.</p>
  </div>

  <div class="body-content">
    <!-- Новости -->
    <div class="row" id="news" v-if="active">
      <ul class="list-unstyled">
        <news
            v-for="item in items"
            :key="item.id"
            :item="item"
        ></news>
      </ul>
    </div>
    <pagination
        id="pagination"
        v-if="active"
        :page="page"
        :page_count="page_count"
        @next-click="nextPage"
        @prev-click="prevPage"
    ></pagination>
    <!-- Категории -->
    <div class="row" id="categories"  v-if="active">
      <ul class="list-group">
      <categories
          v-for="item in items"
          :key="item.id"
          :item="item"
          @category-click="category_click"
      ></categories>
      </ul>
    </div>
  </div>
</div>
