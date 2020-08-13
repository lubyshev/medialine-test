class Blocks {
  createAppBlocks(app, isLoggedIn) {
    this.navigationBlock(app);
    this.authBlock(app, isLoggedIn);
    this.newsBlock(app);
    this.paginationBlock(app);
  }

  navigationBlock(app) {
    app.blocks.navigation = new Vue({
      el:      '#navigation',
      methods: {
        news_click:  function () {
          alert("news_click");
        },
        categories_click:  function () {
          alert("categories_click");
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

  newsBlock(app) {
    app.blocks.news = new Vue({
      el:   '#news',
      data: {
        news: null,
      }
    });
  }

  paginationBlock(app) {
    app.blocks.pagination = new Vue({
      el:      '#pagination',
      data:    {
        page:      0,
        page_count: 0
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
