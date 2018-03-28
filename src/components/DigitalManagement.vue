<template>
  <div class="data-table data-table-init">
    <f7-card-header>
      <!-- Table title -->
      <div class="data-table-title">{{ $store.state.user }}</div>
      <!-- Table actions -->
      <div class="data-table-actions">
        <input type="date" name="wastebook-date" v-model="view_date">
        <a class="link icon-only">
          <i class="icon f7-icons ios-only">sort</i>
          <i class="icon material-icons md-only">sort</i>
        </a>
        <a class="link icon-only">
          <i class="icon f7-icons ios-only">more_vertical_round</i>
          <i class="icon material-icons md-only">more_vert</i>
        </a>
        <a class="link icon-only popup-open" data-popup="#action-popup" v-on:click="form_type = 'add'; form_content = {}">
          <i class="icon f7-icons ios-only">add</i>
          <i class="icon material-icons md-only">add</i>
        </a>
      </div>
    </f7-card-header>

    <f7-card-content :padding="false">
      <table id="action-list" border="1" style="white-space: nowrap;">
        <thead>
          <tr>
            <th class="label-cell" width="100">项目</th>
            <th class="numeric-cell">周一</th>
            <th class="numeric-cell">周二</th>
            <th class="numeric-cell">周三</th>
            <th class="numeric-cell">周四</th>
            <th class="numeric-cell">周五</th>
            <th class="numeric-cell">周六</th>
            <th class="numeric-cell">周日</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in $store.state.items" :key="item.id">
            <td>
              {{ item.name }}
            </td>
            <td v-for="date in action_dates" v-if="actions[item.id] && actions[item.id][date]" :key="date">
              {{ actions[item.id][date]["completion"] }}
              <a class="link icon-only popup-open" data-popup="#action-popup" v-on:click="form_type = 'update'; form_content=actions[item.id][date]">
                <i class="icon f7-icons ios-only">edit</i>
                <i class="icon material-icons md-only">edit</i>
              </a>
              <a :db-id="actions[item.id][date].id" class="link icon-only" v-on:click="deleteOneAction">
                <i class="icon f7-icons ios-only">delete</i>
                <i class="icon material-icons md-only">delete</i>
              </a>
            </td>
            <td v-else>-</td>
          </tr>
        </tbody>
      </table>
    </f7-card-content>

    <f7-popup id="action-popup">
      <f7-block>
        <f7-block-title v-if="form_type === 'add'">添加活动</f7-block-title>
        <f7-block-title v-else>修改活动</f7-block-title>
        <form id="action-update-form" :action="api_host + '/api/action/' + form_type" method="POST" v-on:submit.prevent="submitOneAction">
          <input v-if="form_type === 'update'" type="hidden" name="id" :value="form_content.id">
          <!-- <input v-if="form_type === 'update'" type="hidden" name="user_id" :value="form_content.user_id"> -->
          <!-- <input v-else type="hidden" name="user_id" :value="$store.state.user_id"> -->
          <f7-list class="inline-labels no-hairlines-md">
            <f7-list-item class="item-input">
              <div class="item-title item-label">项目</div>
              <div class="item-input-wrap">
                <select name="item_id" :value="form_content.item_id" required validate>
                  <option disabled selected></option>
                  <option v-for="item in $store.state.items" :key="item.id" :value="item.id">{{ item.name }}</option>
                </select>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">日期</div>
              <div class="item-input-wrap">
                <input type="date" name="date" :value="form_content.date" required validate>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">完成度</div>
              <div class="item-input-wrap">
                <input type="text" name="completion" placeholder="完成次数" :value="form_content.completion" required validate pattern="[0-9]+" data-error-message="只能输入数字。">
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">备注</div>
              <div class="item-input-wrap">
                <input type="text" name="remark" placeholder="活动备注信息" :value="form_content.remark">
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
          </f7-list>
          <div class="block row">
            <div class="col"><input type="submit" class="button button-fill button-round"></div>
            <div class="col"><a class="button button-fill button-round popup-close" href="#">取消</a></div>
          </div>
        </form>
      </f7-block>
    </f7-popup>
  </div>
</template>

<script>
import Vue from 'vue'
import Framework7 from 'framework7/dist/framework7.esm.bundle.js'
import $ from 'jquery'

// var host = 'http://10.0.0.8:8080'
var host = process.env.API_HOST

export default {
  name: 'DigitalManagement',
  data: function () {
    return {
      api_host: host,
      view_date: dateFormat(new Date()),
      form_type: 'add',
      form_content: {},
      action_dates: [],
      actions: {}
    }
  },
  mounted () {
    console.log('store:')
    console.log(this.$store.state.items)
    refreshActionData(new Date(this.view_date), this.action_dates, this.actions)
  },
  methods: {
    submitOneAction: function (e) {
      var form = $(e.target)
      var viewDate = this.view_date
      var actionDates = this.action_dates
      var actions = this.actions
      $.post(form.attr('action'), form.serialize())
      // $.ajax(form.attr('action'), {
      //   method: 'POST',
      //   data: form.serialize(),
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus) {
          var app = new Framework7()
          app.popup.close('#action-popup')
          console.log(viewDate)
          console.log(actionDates)
          refreshActionData(new Date(viewDate), actionDates, actions)
        })
        .fail(function (xhr, textStatus) {
          alert('行动添加失败： ' + xhr.responseText)
        })
    },
    deleteOneAction: function (e) {
      var viewDate = this.view_date
      var actionDates = this.action_dates
      var actions = this.actions
      $.post(host + '/api/action/delete', {id: $(e.currentTarget).attr('db-id')})
      // $.ajax(host + '/api/action/delete', {
      //   method: 'POST',
      //   data: {
      //     id: $(e.currentTarget).attr('db-id')
      //   },
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus) {
          refreshActionData(new Date(viewDate), actionDates, actions)
        })
        .fail(function (xhr, textStatus) {
          alert('行动删除失败：' + xhr.responseText)
        })
    }
  },
  watch: {
    view_date: function (newDate, oldDate) {
      console.log('old: ' + oldDate + ', new: ' + newDate)
      refreshActionData(new Date(newDate), this.action_dates, this.actions)
    }
  }
}

function refreshActionData (date, actionDates, actions) {
  // var date = new Date()
  console.log(date.getDay())

  var start = new Date(date)
  if (date.getDay() === 0) {
    start.setDate(date.getDate() - 6)
  } else {
    start.setDate(date.getDate() - date.getDay() + 1)
  }

  var end = new Date(date)
  if (date.getDay() !== 0) {
    end.setDate(date.getDate() + 7 - date.getDay())
  }

  actionDates.splice(0)
  for (var i = new Date(start); i <= end; i.setDate(i.getDate() + 1)) {
    actionDates.push(dateFormat(i))
  }
  console.log(actionDates)

  console.log(dateFormat(start))
  console.log(dateFormat(end))
  $.get(host + '/api/action/range', {
    start: dateFormat(start),
    end: dateFormat(end)
  })
  // $.ajax(host + '/api/action/range', {
  //   method: 'GET',
  //   data: {
  //     start: dateFormat(start),
  //     end: dateFormat(end)
  //   },
  //   xhrFields: { withCredentials: true },
  //   // crossDomain: true
  // })
    .done(function (data, textStatus) {
      console.log(data)
      var obj = JSON.parse(data)
      for (i in actions) {
        Vue.delete(actions, i)
      }
      for (i in obj) {
        Vue.set(actions, i, obj[i])
      }
    })
}

function dateFormat (date) {
  var fmt = date.getFullYear() + '-'
  fmt += ('0' + (date.getMonth() + 1)).substr(-2) + '-'
  fmt += ('0' + date.getDate()).substr(-2)
  return fmt
}
</script>
