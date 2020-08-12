<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

  <div class="jumbotron">
    <h1>Новости</h1>
    <p class="lead">Последние новости, события, факты.</p>
  </div>

  <div class="body-content">

    <div class="row" id="news">
      <ul class="list-unstyled">
        <news
            v-for="item in news"
            :key="item.id"
            :item="item"
        ></news>
      </ul>
    </div>
    <pagination
        id="pagination"
        :page="page"
        :page_count="page_count"
        @next-click="nextPage"
        @prev-click="prevPage"
    ></pagination>

  </div>
</div>
