class Application {

  constructor() {
    this.blocks = {};
  }

  init(isLoggedIn) {
    for (var cId in Components) {
      this.registerComponent(Components[cId]);
    }
    this.blocks.auth = new Vue({
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
  }

  login() {
    let $self = this;
    let client = new $.RestClient('/');
    client.add('auth');
    client.auth.read().done(function (data, textStatus, xhrObject) {
      console.log('getLoginForm:', data);
      if (data.success && data.schema === "form-dialog") {
        $self.showDialog(data.data);
      }
    });
  }

  showDialog(formData) {
    let dialogId = "#" + formData.id;
    this.dialogId = dialogId;
    $(dialogId).remove();
    $("body").append(formData.template);
    let methods = {};
    for (let id in formData.methods) {
      methods[formData.methods[id].name] = function () {
        eval(formData.methods[id].code);
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
    $(this.dialogId).remove();
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
          $self.blocks.auth.$data.auth.isLoggedIn = true;
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        $self.blocks.auth.$data.auth.isLoggedIn = false;
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
        $self.closeDialog();
        if (data.success) {
          console.log('User logout.');
          $self.user = null;
          $self.blocks.auth.$data.auth.isLoggedIn = false;
        }
      })
      .fail(function (data) {
        let error = data.responseJSON.error;
        alert("Ошибка " + error.statusCode + "!\n" + error.message);
        console.log('Logout Error:', error);
      })
    ;
  }

}

const app = new Application();
