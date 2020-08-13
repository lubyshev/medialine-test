class Application {

  constructor() {
    this.blocks = {};
    this.categoryId = null;
  }

  isLoggedIn() {
    return this.blocks.auth.$data.auth.isLoggedIn;
  }

  markAsLoggedIn() {
    this.blocks.auth.$data.auth.isLoggedIn = true;
  }

  markAsLoggedOut() {
    this.blocks.auth.$data.auth.isLoggedIn = false;
  }

  init(isLoggedIn) {
    for (var cId in Components) {
      this.registerComponent(Components[cId]);
    }
    blocks.createAppBlocks(this, this.pageId, isLoggedIn);
    return this;
  }

  registerComponent(component) {
    console.log('Register component: ' + component.id);
    Vue.component(
      component.id,
      component.data
    );
    return this;
  }

  run() {
    this.loadNews(1);
  }

  login() {
    let $self = this;
    this.getRestClient('auth').read()
      .done(function (data) {
        $self.applyCommonResponseRules(data.commonRules);
        if (data.success && data.schema === "form-dialog") {
          $self.showDialog(data.data);
        }
      });
  }

  showDialog(formData) {
    let dialogId = "#" + formData.id;
    this.dialogId = formData.id;
    $(dialogId).remove();
    $("body").append(formData.template);
    let methods = {};
    for (let id in formData.methods) {
      methods[formData.methods[id].name] = function () { // jshint ignore:line
        eval(formData.methods[id].code); // jshint ignore:line
      };
    }
    this.blocks[formData.id] = new Vue({
      el:      dialogId,
      data:    formData.fields,
      methods: methods
    });

    $(dialogId).show();
  }

  closeDialog() {
    if (this.dialogId) {
      $("#" + this.dialogId).remove();
      this.blocks[this.dialogId] = null;
      this.dialogId = null;
    }
  }

  postLoginForm() {
    let data = this.blocks.login_dialog;
    let $self = this;
    let formData = {
      'login':    data.login,
      'password': data.password,
      'csrf':     data.csrf,
    };
    this.getRestClient('login').create(formData)
      .done(function (data) {
        $self.applyCommonResponseRules(data.commonRules);
        $self.closeDialog();
        if (data.success) {
          console.log('User login.');
          $self.user = data.user;
          $self.markAsLoggedIn();
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        $self.user = null;
        $self.markAsLoggedOut();
        console.log('postLoginForm Error:', error);
      })
    ;
  }

  logout() {
    let $self = this;
    this.getRestClient('logout').create({})
      .done(function (data) {
        $self.applyCommonResponseRules(data.commonRules);
        if (data.success) {
          console.log('User logout.');
          $self.user = null;
          $self.markAsLoggedOut();
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        console.log('Logout Error:', error);
      })
    ;
  }

  loadNews(page) {
    let $self = this;
    this.getRestClient("news").read({page: page})
      .done(function (data) {
        $self.applyCommonResponseRules(data.commonRules);
        if (data.success) {
          $self.categoryId = null;
          $self.clearNews();
          for (let id in data.items) {
            $self.addNewsItem(data.items[id]);
          }
          $self.setPages(data.page, data.pageCount);
          $self.page("news").activatePage();
        } else {
          console.log("loadNews:", data);
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        console.log('loadNews Error:', error);
      });
  }

  addNewsItem(item) {
    this.blocks.news.$data.items.push(
      {
        id:          item.id,
        breadcrumbs: item.breadcrumbs,
        date:        item.date,
        user:        item.userName,
        title:       item.title,
        content:     item.content
      }
    );
  }

  clearNews() {
    this.blocks.news.$data.items = [];
  }

  setPages(page, pageCount) {
    this.blocks.pagination.$data.page = page;
    this.blocks.pagination.$data.page_count = pageCount;
  }

  getPage() {
    return this.blocks.pagination.$data.page;
  }

  nextPage() {
    if (!this.categoryId) {
      this.loadNews(this.getPage() + 1);
    } else {
      this.loadCategoryNews(this.categoryId, this.getPage() + 1);
    }
  }

  prevPage() {
    if (!this.categoryId) {
      this.loadNews(this.getPage() - 1);
    } else {
      this.loadCategoryNews(this.categoryId, this.getPage() - 1);
    }
  }

  applyCommonResponseRules(rules) {
    if (rules) {
      if (rules.loggedIn) {
        this.markAsLoggedIn();
      } else {
        this.markAsLoggedOut();
      }
    }
  }

  getRestClient($route) {
    let client = null;
    const opts = {stripTrailingSlash: true};
    let result = null;
    switch ($route) {
      case 'categories':
        client = new $.RestClient('/');
        client.add('categories', opts);
        result = client.categories;
        break;
      case 'news':
        client = new $.RestClient('/');
        client.add('news', opts);
        result = client.news;
        break;
      case 'auth':
        client = new $.RestClient('/');
        client.add('auth', opts);
        result = client.auth;
        break;
      case 'login':
        client = new $.RestClient('/auth/');
        client.add('login', opts);
        result = client.login;
        break;
      case 'logout':
        client = new $.RestClient('/auth/');
        client.add('logout', opts);
        result = client.logout;
        break;
      case 'categoryNews':
        client = new $.RestClient('/news/');
        client.add('category', opts);
        result = client.category;
        break;
    }

    return result;
  }

  loadCategory(event, categoryId) {
    this.loadCategoryNews(categoryId, 1);
    event.preventDefault();
  }

  loadCategoryNews(categoryId, page) {
    let $self = this;
    this.getRestClient("categoryNews").read(categoryId, {page: page})
      .done(function (data) {
        $self.applyCommonResponseRules(data.commonRules);
        if (data.success) {
          $self.categoryId = categoryId;
          $self.clearNews();
          for (let id in data.items) {
            $self.addNewsItem(data.items[id]);
          }
          $self.setPages(data.page, data.pageCount);
          $self.page("news").activatePage();
          $("#jumbotron h1:first").text("Новости");
          $("#jumbotron p:first").html("Все новости из рубрики &laquo;" + data.categoryTitle + "&raquo;.");

        } else {
          console.log("Categories:", data);
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        console.log('loadCategoriesList Error:', error);
      });

    console.log("Load news category: " + categoryId);
  }

  page(pageId) {
    this.pageId = pageId;
    return this;
  }

  loadCategoriesList() {
    let $self = this;
    this.getRestClient('categories').read({})
      .done(function (data) {
        $self.applyCommonResponseRules(data.commonRules);
        if (data.success) {
          $self.categoryId = null;
          $self.clearCategories();
          $self.addCategoriesItems(data.items);
          $self.page('categories').activatePage();
        } else {
          console.log('Categories:', data);
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        console.log('loadCategoriesList Error:', error);
      });
  }

  activatePage() {
    let h1 = $("#jumbotron h1:first");
    let p = $("#jumbotron p:first");
    switch (this.pageId) {
      case 'news':
        this.blocks.categories.$data.active = false;
        this.blocks.pagination.$data.active = true;
        this.blocks.news.$data.active = true;
        h1.text('Новости');
        p.text('Последние новости, события, факты.');
        break;
      case 'categories':
        this.blocks.categories.$data.active = true;
        this.blocks.pagination.$data.active = false;
        this.blocks.news.$data.active = false;
        h1.text('Рубрики');
        p.text('Все рубрики портала.');
        break;
    }

    return this;
  }

  clearCategories() {
    this.blocks.categories.$data.items = [];
  }

  addCategoriesItems(items) {
    this.blocks.categories.$data.items = items;
  }

}

const app = new Application(); // jshint ignore:line
