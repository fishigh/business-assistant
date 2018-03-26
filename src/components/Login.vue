<template>
  <f7-page data-name="item">
    <f7-navbar title="用户登录"></f7-navbar>

    <f7-page-content>
      <form>
        <f7-list class="inline-labels no-hairlines-md">
          <f7-list-item class="item-input">
            <div class="item-title item-label">手机号</div>
            <div class="item-input-wrap">
              <input type="number" name="name" placeholder="手机号" :value="form_content.tel" required validate>
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
        </f7-list>
      </form>
    </f7-page-content>
  </f7-page>
</template>

<script>
export default {
  name: 'YcLogin',
  data: function () {
    return {
      form_content: {}
    }
  }
}
</script>
