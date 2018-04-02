<template>
  <div>
    <f7-card-header>
      <!-- Table title -->
      <div class="data-table-title">{{ $store.state.user }}</div>
      <!-- Table actions -->
      <div class="data-table-actions">
        <input type="date" name="wastebook-date" v-model="view_date">
        <!-- <a class="link icon-only">
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
        </a> -->
      </div>
    </f7-card-header>

    <f7-card-content :padding="false">
      <!-- <table id="action-list" border="1" style="white-space: nowrap; overflow: auto">
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
            <td v-else>
              <a class="link icon-only popup-open" data-popup="#action-popup" v-on:click="form_type = 'add'; form_content = {date: date, item_id: item.id}">
                <i class="icon f7-icons ios-only">add</i>
                <i class="icon material-icons md-only">add</i>
              </a>
            </td>
          </tr>
        </tbody>
      </table> -->
      <v-table 
        is-vertical-resize
        style="width:100%"
        is-horizontal-resize
        :vertical-resize-offset='5'
        :min-height='200'
        :columns="columns"
        :table-data="tableData"
        row-hover-color="#eee"
        row-click-color="#edf7ff"
        @on-custom-comp="cellClick"></v-table>
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
            <div class="col"><input type="submit" class="button button-fill button-round" :value="form_type === 'add' ? '添加' : '修改'"></div>
            <div class="col" v-if="form_type === 'update'">
              <input 
                type="submit"
                class="button button-fill button-round"
                value="删除"
                :db-id="form_content['id']"
                v-on:click.prevent="deleteOneAction">
            </div>
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
// import 'vue-easytable/umd/css/index.css'
import 'vue-easytable/libs/themes-base/index.css'
import {VTable, VPagination} from 'vue-easytable'
import EditCell from '@/components/EditCell'

import {dateFormat} from '../yc.common.js'

Vue.component(VTable.name, VTable)
Vue.component(VPagination.name, VPagination)
Vue.component('edit-cell', EditCell)

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
      // action_dates: [],
      actions: {},
      columns: [
      ],
      tableData: [
      ]
    }
  },
  beforeMount () {
    this.columns = initTableHead(new Date(this.view_date))
    // $.get(host + '/api/item/list')
    // // $.ajax(host + '/api/item/list', {
    // //   method: 'GET',
    // //   xhrFields: { withCredentials: true },
    // //   // crossDomain: true
    // // })
    //   .done(function (data, textStatus) {
    //     var obj = JSON.parse(data)
    //     console.log(this.$store.state.items)
    //     this.$store.commit('updateItems', obj)
    // })
  },
  mounted () {
    console.log('store:')
    console.log(this.$store.state.items)
    var view_date = this.view_date
    var columns = this.columns
    console.log(columns)
    var store = this.$store
    var actions = this.actions
    var tableData = this.tableData
    $.get(host + '/api/item/list')
      .done(function (data, textStatus) {
        var obj = JSON.parse(data)
        console.log(obj[0])
        console.log(store)
        store.commit('updateItems', obj)
        refreshActionData(new Date(view_date), columns, store.state.items, actions, tableData)
      })
  },
  methods: {
    cellClick: function (args) {  // event emit from sub components
      console.log(JSON.stringify(args))
      console.log(this.form_type)
      console.log(JSON.stringify(this.actions))
      if (this.actions[args['id']] && this.actions[args['id']][args['date']]) {
        this.form_type = 'update'
        this.form_content = this.actions[args['id']][args['date']]
      } else {
        this.form_type = 'add'
        this.form_content = {}
        this.form_content['item_id'] = args['id']
        this.form_content['date'] = args['date']
      }
    },
    submitOneAction: function (e) {
      var form = $(e.target)
      var viewDate = this.view_date
      var columns = this.columns
      var store = this.$store
      var actions = this.actions
      var tableData = this.tableData
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
          // console.log(actionDates)
          refreshActionData(new Date(viewDate), columns, store.state.items, actions, tableData)
        })
        .fail(function (xhr, textStatus) {
          alert('行动添加失败： ' + xhr.responseText)
        })
    },
    deleteOneAction: function (e) {
      var viewDate = this.view_date
      var columns = this.columns
      var store = this.$store
      var actions = this.actions
      var tableData = this.tableData
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
          var app = new Framework7()          
          app.popup.close('#action-popup')
          refreshActionData(new Date(viewDate), columns, store.state.items, actions, tableData)
        })
        .fail(function (xhr, textStatus) {
          alert('行动删除失败：' + xhr.responseText)
        })
    }
  },
  watch: {
    view_date: function (newDate, oldDate) {
      console.log('old: ' + oldDate + ', new: ' + newDate)
      this.columns = initTableHead(new Date(newDate))
      var columns = this.columns
      var store = this.$store
      var actions = this.actions
      var tableData = this.tableData
      refreshActionData(new Date(newDate), columns, store.state.items, actions, tableData)
    }
  }
}

function initTableHead (date) {
  var columns = []
  // var date = new Date()
  console.log(date.getDay())

  var start = new Date(date)
  if (date.getDay() === 0) {
    start.setDate(date.getDate() - 6)
  } else {
    start.setDate(date.getDate() - date.getDay() + 1)
  }

  var end = new Date(date)
  end.setDate(start.getDate() + 6)
  // if (date.getDay() !== 0) {
    // end.setDate(date.getDate() + 7 - date.getDay())
  // }

  var weekDay = ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
  columns.push({
    field: 'item',
    title: '项目', 
    width: 80, 
    titleAlign: 'center', 
    columnAlign: 'center', 
    isResize:true, isFrozen: true, componentName: 'edit-cell'
  })
  for (var i = 0; i < 7; i++) {
    var d = new Date(start)
    d.setDate(start.getDate() + i);

    columns.push({
      field: dateFormat(d),
      title: weekDay[i], 
      width: 80, 
      titleAlign: 'center', 
      columnAlign: 'center', 
      isResize:true, componentName: 'edit-cell'
    })
  }  
  console.log(columns)
  return columns;
}

function refreshActionData (date, columns, items, actions, tableData) {
  console.log('refreshActionData')
  // var date = new Date()
  console.log(date.getDay())

  var start = new Date(date)
  if (date.getDay() === 0) {
    start.setDate(date.getDate() - 6)
  } else {
    start.setDate(date.getDate() - date.getDay() + 1)
  }

  var end = new Date(date)
  end.setDate(start.getDate() + 6)

  console.log(dateFormat(start))
  console.log(dateFormat(end))
  $.get(host + '/api/action/range', {
    start: dateFormat(start),
    end: dateFormat(end)
  })
    .done(function (data, textStatus) {
      console.log(data)
      var obj = JSON.parse(data)
      for (i in actions) {
        Vue.delete(actions, i)
      }
      for (i in obj) {
        Vue.set(actions, i, obj[i])
      }
      console.log(items)
      console.log(columns)      
      tableData.splice(0)
      for (var i in items) {
        console.log(items[i].id)
        var cell = {
          'item': items[i].name,
          'id': items[i].id,
          'attr': {
            'class': 'link icon-only popup-open',
            'data-popup': '#action-popup'
          }
        }
        console.log(cell)
        for (var j = 1; j < columns.length; j++) {
          console.log(j)
          console.log(columns[j])
          console.log(columns[j].field)
          if (actions[items[i].id] && actions[items[i].id][columns[j].field]) {
            // Vue.set(tableData, i, obj[i])
            cell[columns[j].field] = actions[items[i].id][columns[j].field]['completion']
          } else {
            cell[columns[j].field] = '-'
          }
        }
        console.log(cell)
        tableData.push(cell)
      //   Vue.delete(actions, i)
      }
      console.log(tableData)
      // for (i in obj) {
      //   Vue.set(actions, i, obj[i])
      // }
    })
}

</script>

<style>
/* .v-table-class {
  font-size: 14px;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.v-table-title-class {
  font-weight: normal;
  color: #333333;
  text-shadow: 0 0 0 #333333;
}
.v-table-body-class {
  font-weight: normal;
  color: #333333;
  text-shadow: 0 0 0 #333333;
}

.v-scrollbar-wrap::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    border-radius: 10px;
    background-color: #F5F5F5;
}

.v-scrollbar-wrap::-webkit-scrollbar {
    height:12px;
    width:10px;
    background-color: #F5F5F5;
}

.v-scrollbar-wrap::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #666;
} */
</style>
