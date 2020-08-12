class Application {

  constructor() {
    this.blocks = {};
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
    blocks.createAppBlocks(this, isLoggedIn);
    return this;
  }

  registerComponent(component) {
    console.log('Register component:', component);
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
    let client = new $.RestClient('/');
    client.add('auth');
    client.auth.read().done(function (data) {
      console.log('getLoginForm:', data);
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
    let client = new $.RestClient('/auth/');
    let formData = {
      'login':    data.login,
      'password': data.password,
      'csrf':     data.csrf,
    };
    client.add('login');
    client.login.create(formData)
      .done(function (data) {
        console.log('postLoginForm:', data);
        $self.closeDialog();
        if (data.success) {
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
    let client = new $.RestClient('/auth/');
    client.add('logout');
    client.logout.create({})
      .done(function (data) {
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
    let client = new $.RestClient('/');
    client.add('news');
    client.news.read({page: page})
      .done(function (data) {
        if (data.success) {
          $self.clearNews();
          for (let id in data.items) {
            $self.addNewsItem(data.items[id]);
          }
          $self.setPages(data.page, data.pageCount);
        } else {
          console.log('News:', data);
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        console.log('loadNews Error:', error);
      });
  }

  addNewsItem(item) {
    this.blocks.news.$data.news.push(
      {id: item.id, date: item.createdAt, title: item.title, content: item.content}
    );
  }

  clearNews() {
    this.blocks.news.$data.news = [];
  }

  setPages(page, pageCount) {
    this.blocks.pagination.$data.page = page;
    this.blocks.pagination.$data.page_count = pageCount;
  }

  getPage() {
    return this.blocks.pagination.$data.page;
  }

  nextPage() {
    this.loadNews(this.getPage() + 1);
  }

  prevPage() {
    this.loadNews(this.getPage() - 1);
  }

}

const app = new Application(); // jshint ignore:line
