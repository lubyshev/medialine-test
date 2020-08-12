const Components = [
  {
    id:   "auth",
    data: {
      props:    ["auth"],
      template: '<li><a v-if="!auth.isLoggedIn" href="/auth/login" ' +
                  'v-on:click.stop.prevent="$emit(\'login-click\')">Login</a>' +
                  '<a v-if="auth.isLoggedIn" href="/auth/logout" ' +
                  'v-on:click.stop.prevent="$emit(\'logout-click\')">Logout</a></li>'
    }
  }
];
