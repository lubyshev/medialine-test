class Blocks {
  createAppBlocks(app, isLoggedIn) {
    this.authBlock(app, isLoggedIn);
    this.newsBlock(app);
    this.paginationBlock(app);
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
