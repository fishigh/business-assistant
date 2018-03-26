<template>
  <div class="page" data-name="item">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="left">
          <a href="#" class="link back panel-open">
            <i class="icon icon-back"></i>
            <span class="ios-only">Back</span>
          </a>
        </div>
        <div class="title">项目管理</div>
      </div>
    </div>

    <div class="page-content">
      <div class="data-table data-table-init">
        <div class="card-header">
          <!-- Table title -->
          <div class="data-table-title">项目列表</div>
          <!-- Table actions -->
          <div class="data-table-actions">
            <!-- <a class="link icon-only">
              <i class="icon f7-icons ios-only">sort</i>
              <i class="icon material-icons md-only">sort</i>
            </a> -->
            <a class="link icon-only popup-open" data-popup="#item-popup" v-on:click="form_type = 'add'; form_content = {}">
              <i class="icon f7-icons ios-only">add</i>
              <i class="icon material-icons md-only">add</i>
            </a>
          </div>
        </div>
        <div class="card-content">
          <table id="item-list" border="1" style="white-space: nowrap;">
            <thead>
              <tr>
                <th class="label-cell" width="100">序号</th>
                <th class="label-cell">项目名称</th>
                <th class="label-cell">备注</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in $store.state.items" :key="item.id">
                <td>
                  {{ item.id }}
                  <a class="link icon-only popup-open" data-popup="#item-popup" v-on:click="form_type = 'update'; form_content = item">
                    <i class="icon f7-icons ios-only">edit</i>
                    <i class="icon material-icons md-only">edit</i>
                  </a>
                  <a :db-id="item.id" class="link icon-only" v-on:click="deleteOneItem($store, $event)">
                    <i class="icon f7-icons ios-only">delete</i>
                    <i class="icon material-icons md-only">delete</i>
                  </a>
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.remark }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <f7-popup id="item-popup">
      <f7-block>
        <f7-block-title v-if="form_type === 'add'">添加事项</f7-block-title>
        <f7-block-title v-else>修改事项</f7-block-title>
        <form id="item-update-form" :action="api_host + '/api/item/' + form_type" v-on:submit.prevent="submitOneItem($store, $event)">
          <input v-if="form_type === 'update'" type="hidden" name="id" :value="form_content.id">
          <f7-list class="inline-labels no-hairlines-md">
            <f7-list-item class="item-input">
              <div class="item-title item-label">事项</div>
              <div class="item-input-wrap">
                <input type="text" name="name" placeholder="事项名称" :value="form_content.name" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">备注</div>
              <div class="item-input-wrap">
                <input type="text" name="remark" placeholder="事项备注信息" :value="form_content.remark">
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
  name: 'YcItem',
  // props: ['items', 'user'],
  data: function () {
    return {
      api_host: host,
      form_type: 'add',
      form_content: {}
    }
  },
  on: {
    // pageInit: function (e, page) {
    //   console.log('item init' + this.$parent)
    //   // console.log(this.$parent)
    //   console.log(this.$store.state.items)
    // }
  },
  methods: {
    submitOneItem: function (store, e) {
      var form = $(e.target)
      $.post(form.attr('action'), form.serialize())
        .done(function (data, textStatus) {
          new Framework7().popup.close('#item-popup')
          refreshItemData(store)
        })
        .fail(function (xhr, textStatus) {
          alert('事项添加失败： ' + xhr.responseText)
        })
    },
    deleteOneItem: function (store, e) {
      $.post(host + '/api/item/delete', {id: $(e.currentTarget).attr('db-id')})
        .done(function (data, textStatus) {
          refreshItemData(store)
        })
        .fail(function (xhr, textStatus) {
          alert('事项删除失败：' + xhr.responseText)
        })
    }
  }
}

function refreshItemData (store) {
  Framework7.request.get(host + '/api/item/list', function (data) {
    var obj = JSON.parse(data)
    console.log(obj[0])
    store.commit('updateItems', obj)
  })
}
</script>
