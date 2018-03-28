<template>
  <div class="page" data-name="customer">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="left">
          <a href="#" class="link back panel-open">
            <i class="icon icon-back"></i>
            <span class="ios-only">Back</span>
          </a>
        </div>
        <div class="title">客户管理</div>
      </div>
    </div>

    <div class="page-content">
      <div class="data-table data-table-init">
        <div class="card-header">
          <!-- Table title -->
          <div class="data-table-title">客户列表</div>
          <!-- Table actions -->
          <div class="data-table-actions">
            <!-- <a class="link icon-only">
              <i class="icon f7-icons ios-only">sort</i>
              <i class="icon material-icons md-only">sort</i>
            </a> -->
            <a class="link icon-only popup-open" data-popup="#customer-popup" v-on:click="form_type = 'add'; form_content = {}">
              <i class="icon f7-icons ios-only">add</i>
              <i class="icon material-icons md-only">add</i>
            </a>
          </div>
        </div>
        <div class="card-content">
          <table id="customer-list" border="1" style="white-space: nowrap;">
            <thead>
              <tr>
                <th class="label-cell" width="100">序号</th>
                <th class="label-cell">姓名</th>
                <th class="label-cell">代理级别</th>
                <th class="label-cell">性别</th>
                <th class="label-cell">生日</th>
                <th class="label-cell">电话</th>
                <th class="label-cell">微信</th>
                <th class="label-cell">QQ</th>
                <th class="label-cell">住址</th>
                <th class="label-cell">余额</th>
                <th class="label-cell">备注</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="customer in $store.state.customers" :key="customer.id">
                <td>
                  {{ customer.id }}
                  <a class="link icon-only popup-open" data-popup="#customer-popup" v-on:click="form_type = 'update'; form_content = customer">
                    <i class="icon f7-icons ios-only">edit</i>
                    <i class="icon material-icons md-only">edit</i>
                  </a>
                  <a :db-id="customer.id" class="link icon-only" v-on:click="deleteOneCustomer($store, $event)">
                    <i class="icon f7-icons ios-only">delete</i>
                    <i class="icon material-icons md-only">delete</i>
                  </a>
                </td>
                <td>{{ customer.name }}</td>
                <td>{{ $store.state.id_level_mapping[customer.level] }}</td>
                <td>{{ customer.gender }}</td>
                <td>{{ customer.birthday }}</td>
                <td>{{ customer.tel }}</td>
                <td>{{ customer.weixin }}</td>
                <td>{{ customer.qq }}</td>
                <td>{{ customer.addr }}</td>
                <td>{{ customer.fund }}</td>
                <td>{{ customer.remark }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <f7-popup id="customer-popup" style="overflow: scroll;">
      <f7-block>
        <f7-block-title v-if="form_type === 'add'">添加客户</f7-block-title>
        <f7-block-title v-else>修改客户信息</f7-block-title>
        <form id="customer-update-form" :action="api_host + '/api/customer/' + form_type" v-on:submit.prevent="submitOneCustomer($store, $event)">
          <input v-if="form_type === 'update'" type="hidden" name="id" :value="form_content.id">
          <f7-list class="inline-labels no-hairlines-md">
            <f7-list-item class="item-input">
              <div class="item-title item-label">姓名</div>
              <div class="item-input-wrap">
                <input type="text" name="name" placeholder="客户姓名" :value="form_content.name" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">代理级别</div>
              <div class="item-input-wrap">
                <select name="level" :value="form_content.level">
                  <option v-for="(name, id) in $store.state.id_level_mapping" :key="id" :value="id">{{ name }}</option>
                </select>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">性别</div>
              <div class="item-input-wrap">
                <select name="gender" :value="form_content.gender">
                  <option disabled selected></option>
                  <option>男</option>
                  <option>女</option>
                </select>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">生日</div>
              <div class="item-input-wrap">
                <input type="date" name="birthday" :value="form_content.birthday" required validate>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">电话</div>
              <div class="item-input-wrap">
                <input type="tel" name="tel" placeholder="手机或固定电话号码" :value="form_content.tel" required validate pattern="[0-9]+" data-error-message="只能输入数字。">
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">微信</div>
              <div class="item-input-wrap">
                <input type="text" name="weixin" placeholder="微信号" :value="form_content.weixin">
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">QQ</div>
              <div class="item-input-wrap">
                <input type="text" name="qq" placeholder="QQ号" :value="form_content.qq">
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">住址</div>
              <div class="item-input-wrap">
                <input type="text" name="addr" placeholder="常用地址" :value="form_content.addr" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">备注</div>
              <div class="item-input-wrap">
                <input type="text" name="remark" placeholder="客户信息备注" :value="form_content.remark">
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
          </f7-list>
          <div class="block row">
            <div class="col"><input type="submit" class="button button-fill button-round submit-item"></div>
            <div class="col"><a class="button button-fill button-round popup-close" href="#">取消</a></div>
          </div>
        </form>
      </f7-block>
    </f7-popup>
  </div>
</template>

<script>
import Framework7 from 'framework7/dist/framework7.esm.bundle.js'
import $ from 'jquery'

// var host = 'http://10.0.0.8:8080'
var host = process.env.API_HOST

export default {
  name: 'YcCustomer',
  data: function () {
    return {
      api_host: host,
      form_type: 'add',
      form_content: {}
    }
  },
  methods: {
    submitOneCustomer: function (store, e) {
      var form = $(e.target)
      $.post(form.attr('action'), form.serialize())
      // $.ajax(form.attr('action'), {
      //   method: 'POST',
      //   data: form.serialize(),
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus) {
          new Framework7().popup.close('#customer-popup')
          refreshCustomerData(store)
        })
        .fail(function (xhr, textStatus) {
          alert('客户添加失败： ' + xhr.responseText)
        })
    },
    deleteOneCustomer: function (store, e) {
      $.post(host + '/api/customer/delete', {id: $(e.currentTarget).attr('db-id')})
      // $.ajax(host + '/api/customer/delete', {
      //   method: 'POST',
      //   data: {
      //     id: $(e.currentTarget).attr('db-id')
      //   },
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus) {
          refreshCustomerData(store)
        })
        .fail(function (xhr, textStatus) {
          alert('客户删除失败：' + xhr.responseText)
        })
    }
  }
}

function refreshCustomerData (store) {
  $.get(host + '/api/customer/list')
  // $.ajax(host + '/api/customer/list', {
  //   method: 'GET',
  //   xhrFields: { withCredentials: true },
  //   // crossDomain: true
  // })
    .done(function (data, textStatus) {
      var obj = JSON.parse(data)
      console.log(obj[0])
      store.commit('updateCustomers', obj)
    })
}
</script>
