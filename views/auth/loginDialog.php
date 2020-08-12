<?php ?>
<div id="login_dialog" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
      </div>
      <form>
        <div class="modal-body">
          <div class="form-group">
            <label for="login">Login</label>
            <input type="text" class="form-control" id="login" aria-describedby="emailHelp"
                   placeholder="Enter your login" v-model="login">
            <small id="emailHelp" class="form-text text-muted">Enter your login name (admin or demo).</small>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password"
                   placeholder="Password" v-model="password">
            <small id="emailHelp" class="form-text text-muted">Enter your password (admin or demo).</small>
          </div>
          <input type="hidden" class="form-check-input" id="csrf" v-model="csrf">
        </div>
        <div class="modal-footer">
          <button @click.stop.prevent="submit" type="submit" class="btn btn-primary">Login</button>
          <button @click.stop.prevent="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
