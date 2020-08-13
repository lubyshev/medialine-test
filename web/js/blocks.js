class Blocks {
  createAppBlocks(app, pageId, isLoggedIn) {
    this.navigationBlock(app);
    this.authBlock(app, isLoggedIn);
    this.newsBlock(app, pageId === 'news');
    this.paginationBlock(app, pageId === 'news');
    this.categoriesBlock(app, pageId === 'categories');
  }

  navigationBlock(app) {
    app.blocks.navigation = new Vue({
      el:      '#navigation',
      methods: {
        news_click:       function () {
          app.loadNews(1);
        },
        categories_click: function () {
          app.loadCategoriesList();
        }
      }
    });
  }

  authBlock(app, isLoggedIn) {
    app.blocks.auth = new Vue({
      el:      '#auth',
      data:    {
        auth: {
          isLoggedIn: isLoggedIn,
        },
      },
      methods: {
        login:  function () {
          app.login();
        },
        logout: function () {
          app.logout();
        },
      }
    });
  }

  newsBlock(app, active) {
    app.blocks.news = new Vue({
      el:   '#news',
      data: {
        items:  null,
        active: active
      }
    });
  }

  categoriesBlock(app, active) {
    app.blocks.categories = new Vue({
      el:      '#categories',
      data:    {
        items:  null,
        active: active
      },
      methods: {
        category_click: function (categoryId) {
          app.loadNewsCategory(categoryId);
        }
      }
    });
  }

  paginationBlock(app, active) {
    app.blocks.pagination = new Vue({
      el:      '#pagination',
      data:    {
        page:       0,
        page_count: 0,
        active:     active
      },
      methods: {
        nextPage: function () {
          app.nextPage();
        },
        prevPage: function () {
          app.prevPage();
        },
      }
    });
  }
}

const blocks = new Blocks();  // jshint ignore:line
