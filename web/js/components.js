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
  },
  {
    id:   "news",
    data: {
      props:    ["item"],
      template: '<li><ul class="list-group">\n' +
                  '  <li class="list-group-item active">' +
                  '    ' +
                  '    <h3>{{ item.title }}</h3></li>\n' +
                  '  <li class="list-group-item">&nbsp;<span class="badge badge-warning">{{ item.date }}</span></li>\n' +
                  '  <li class="list-group-item" v-html="item.content"></li>\n' +
                  '</ul></li>'
    }
  },
  {
    id:   "pagination",
    data: {
      props:    ["page", "page_count"],
      template: '<nav aria-label="News navigation">\n' +
                  '  <ul class="pagination">\n' +
                  '    <li class="page-item" :class="page <= 1 ? \'disabled\' : false">\n' +
                  '      <a @click.stop.prevent="$emit(\'prev-click\')"\n' +
                  '         class="page-link" href="#" aria-label="Previous">\n' +
                  '        <span aria-hidden="true">&laquo;</span>\n' +
                  '        <span class="sr-only">Previous</span>\n' +
                  '      </a>\n' +
                  '    </li>\n' +
                  '    <li class="page-item" :class="(page >= page_count) ? \'disabled\' : false" >\n' +
                  '      <a @click.stop.prevent="$emit(\'next-click\')"\n' +
                  '         class="page-link" href="#" aria-label="Next">\n' +
                  '        <span aria-hidden="true">&raquo;</span>\n' +
                  '        <span class="sr-only">Next</span>\n' +
                  '      </a>\n' +
                  '    </li>\n' +
                  '  </ul>\n' +
                  '</nav>'
    }
  },

];
