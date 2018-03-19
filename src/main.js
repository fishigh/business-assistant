// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuex from 'vuex'
// Import F7 Bundle
import Framework7 from 'framework7/dist/framework7.esm.bundle.js'
// Import F7-Vue Plugin Bundle (with all F7 components registered)
import Framework7Vue from 'framework7-vue/dist/framework7-vue.esm.bundle.js'
// import Dom7 from 'dom7/dist/dom7.min.js'
// Import jquery
// import $ from 'jquery'
// Import User Components
import YcItem from '@/components/Item'
import YcUser from '@/components/User'
import WasteBook from '@/components/WasteBook'
import DigitalManagement from '@/components/DigitalManagement'

Vue.config.productionTip = false

Vue.use(Vuex)
Vue.use(Framework7Vue, Framework7)

// var $$ = Dom7

Vue.component('waste-book', WasteBook)
Vue.component('digital-management', DigitalManagement)

const store = new Vuex.Store({
  state: {
    user: '沈丹萍',
    user_id: 1,
    items: [],
    id_item_mapping: {},
    users: [],
    id_user_mapping: {},
    id_level_mapping: {}
  },
  mutations: {
    updateItems (state, newone) {
      state.items = newone
      for (var i in newone) {
        state.id_item_mapping[newone[i].id] = newone[i].name
      }
    },
    updateUsers (state, newone) {
      state.users = newone
      for (var i in newone) {
        state.id_user_mapping[newone[i].id] = newone[i].name
      }
    },
    updateLevelMapping (state, newone) {
      for (var i in newone) {
        state.id_level_mapping[newone[i].id] = newone[i].name
      }
    }
  }
})

// console.log('store.state')
// console.log(store.state.items)
// console.log(store.items)
// const routes = [
//   { path: '/item', component: YcItem },
//   { path: '/user', component: YcUser }
// ]

// const router = new VueRouter({
//   routes // （缩写）相当于 routes: routes
// })

var host = 'http://127.0.0.1:8080'

var params = {
  api_host: host,
  today: dateFormat(new Date()),
  view_date: dateFormat(new Date()),
  // user: '沈丹萍',
  // user_id: 1,
  action_dates: [],
  actions: [],
  form_type: 'add',
  form_content: {}
}

// YcItem.items = [{id: 1, name: 'test'}]
/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  // router,
  // App root data
  data: params,
  // F7 component
  framework7: {
    // pushState: true,
    // Array with app routes
    routes: [
      {
        path: '/item/',
        component: YcItem
      },
      {
        path: '/user/',
        component: YcUser
      }
    ],
    // App Name
    name: '沅初团队助手',
    // App id
    id: 'com.yyt.yc',
    // Enable swipe panel
    panel: {
      swipe: 'left'
    },
    on: {
      init: function () {
        // alert('init')
        loadGlobal()

        // refreshManageData(new Date(params.today))
      }
    }
    // ...
  },
  // App root methods
  methods: {
    // submitOneAction: function (e) {
    //   var form = $(e.target)
    //   $.post(form.attr('action'), form.serialize())
    //     .done(function (data, textStatus) {
    //       var app = new Framework7()
    //       app.popup.close('#action-popup')
    //       refreshManageData()
    //     })
    //     .fail(function (xhr, textStatus) {
    //       alert('行动添加失败： ' + xhr.responseText)
    //     })
    // },
    // deleteOneAction: function (e) {
    //   $.post(host + '/api/action/delete', {id: $(e.currentTarget).attr('db-id')})
    //     .done(function (data, textStatus) {
    //       refreshManageData()
    //     })
    //     .fail(function (xhr, textStatus) {
    //       alert('行动删除失败：' + xhr.responseText)
    //     })
    // },
    // fillAction: function(data) {
    //   alert(JSON.stringify(data))
    //   params.form_type = 'update'
    //   new Framework7().form.fillFromData('#action-update-form', data);
    // }
  },
  watch: {
    // view_date: function (newDate, oldDate) {
    //   console.log('old: ' + oldDate + ', new: ' + newDate)
    //   refreshManageData(new Date(newDate))
    // }
  }
})

function dateFormat (date) {
  var fmt = date.getFullYear() + '-'
  fmt += ('0' + (date.getMonth() + 1)).substr(-2) + '-'
  fmt += ('0' + date.getDate()).substr(-2)
  return fmt
}

function loadGlobal () {
  Framework7.request.get(host + '/api/item/list', function (data) {
    var obj = JSON.parse(data)
    console.log(obj[0])
    store.commit('updateItems', obj)
  })

  Framework7.request.get(host + '/api/user/list', function (data) {
    var obj = JSON.parse(data)
    console.log(obj[0])
    store.commit('updateUsers', obj)
  })

  Framework7.request.get(host + '/api/level/list', function (data) {
    var obj = JSON.parse(data)
    console.log(obj[0])
    store.commit('updateLevelMapping', obj)
  })
}

// function refreshManageData (date) {
//   // var date = new Date()
//   console.log(date.getDay())

//   var start = new Date(date)
//   if (date.getDay() === 0) {
//     start.setDate(date.getDate() - 6)
//   } else {
//     start.setDate(date.getDate() - date.getDay() + 1)
//   }

//   var end = new Date(date)
//   if (date.getDay() !== 0) {
//     end.setDate(date.getDate() + 7 - date.getDay())
//   }

//   params.action_dates = []
//   for (var i = new Date(start); i <= end; i.setDate(i.getDate() + 1)) {
//     params.action_dates.push(dateFormat(i))
//   }
//   console.log(params.action_dates)

//   console.log(dateFormat(start))
//   console.log(dateFormat(end))
//   Framework7.request.get(host + '/api/action/range', {
//     start: dateFormat(start),
//     end: dateFormat(end)
//   }, function (data) {
//     console.log(data)
//     var obj = JSON.parse(data)
//     params.actions = obj
//   })
//   // addActionOp()
// }

// function addActionOp () {
//   var marc = 'action'
//   $('.delete').on('click', function (e) {
//     // alert('delete ' + this.getAttribute('db-id'));
//     Framework7.request.post('/api/' + marc + '/delete', {id: this.getAttribute('db-id')}, function (data) {
//       refreshManageData()
//     })
//   })
// }
