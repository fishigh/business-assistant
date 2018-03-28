<template>
  <f7-page data-name="item">
    <f7-navbar title="用户登录"></f7-navbar>

    <f7-page-content>
      <form id="login-form" :action="api_host + '/user/' + form_type" v-on:submit.prevent="submitLoginOrRegister($event)">
      <!-- <form id="login-form" method="POST" :action="api_host + '/user/' + form_type"> -->
        <f7-list class="inline-labels no-hairlines-md">
          <f7-list-item v-if="form_type === 'register'" class="item-input">
            <div class="item-title item-label">姓名</div>
            <div class="item-input-wrap">
              <input type="text" name="name" placeholder="姓名" :value="form_content.tel" required validate>
              <span class="input-clear-button"></span>
            </div>
          </f7-list-item>
          <f7-list-item class="item-input">
            <div class="item-title item-label">手机号</div>
            <div class="item-input-wrap">
              <input type="number" name="tel" placeholder="手机号" :value="form_content.tel" required validate>
              <span class="input-clear-button"></span>
            </div>
          </f7-list-item>
          <f7-list-item class="item-input">
            <div class="item-title item-label">密码</div>
            <div class="item-input-wrap">
              <input type="password" name="password" placeholder="密码" :value="form_content.password" required validate>
              <span class="input-clear-button"></span>
            </div>
          </f7-list-item>
          <f7-list-item v-if="form_type === 'register'" class="item-input">
            <div class="item-title item-label">确认密码</div>
            <div class="item-input-wrap">
              <input type="password" name="verify_password" placeholder="密码" :value="form_content.password" required validate>
              <span class="input-clear-button"></span>
            </div>
          </f7-list-item>
        </f7-list>
        <div class="block row">
          <div class="col"><input type="submit" class="button button-fill button-round" :value="form_type === 'login' ? '登录' : '注册'"></div>
          <div class="col"><a class="button" href="#" v-on:click="form_type = form_type === 'register' ? 'login' : 'register'">{{ form_type === 'login' ? '注册' : '登录' }}</a></div>
        </div>
      </form>
    </f7-page-content>
  </f7-page>
</template>

<script>
import $ from 'jquery'

var host = process.env.API_HOST

export default {
  name: 'YcLogin',
  data: function () {
    return {
      api_host: host,
      form_type: 'login',
      form_content: {}
    }
  },
  methods: {
    submitLoginOrRegister: function (e) {
      var form = $(e.target)
      console.log(JSON.stringify(form.serializeArray()))
      $.post(form.attr('action'), form.serialize())
      // $.ajax(form.attr('action'), {
      //   method: 'POST',
      //   data: form.serialize(),
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus, xhr) {
          // alert(data);
          // console.log(xhr)
          // console.log(xhr.getResponseHeader("Set-Cookie"))
          // var setCookie = xhr.getResponseHeader("Set-Cookie")
          // var cookies = setCookie.split(';')
          // for (index in cookies) {
          //   var halves = cookies[index].split('=')
          //   $.setCookie(halves[0], halves[1])
          // }
          location.reload()
        })
        .fail(function (xhr, textStatus) {
          console.log(textStatus)
          alert((this.form_type === 'login' ? '登录' : '注册') + '失败： ' + xhr.responseText)
        })
    }
  }
}
</script>
