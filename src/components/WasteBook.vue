<template>
  <div class="data-table data-table-init">
    <div class="card-header">
        <!-- Table title -->
        <div class="data-table-title">账目表</div>
        <!-- Table actions -->
        <div class="data-table-actions">
        <!-- <a class="link icon-only">
            <i class="icon f7-icons ios-only">sort</i>
            <i class="icon material-icons md-only">sort</i>
        </a> -->
        <a class="link icon-only popup-open" data-popup="#record-popup" v-on:click="form_type = 'add'; form_content = {}">
            <i class="icon f7-icons ios-only">add</i>
            <i class="icon material-icons md-only">add</i>
        </a>
        </div>
    </div>

    <div class="card-content">
        <table id="record-list" border="1" style="white-space: nowrap;">
        <thead>
            <tr>
            <th class="label-cell" width="100">序号</th>
            <th class="label-cell">日期</th>
            <th class="label-cell">代理</th>
            <th class="label-cell">客户</th>
            <th class="label-cell">消费项目</th>
            <th class="label-cell">进单价</th>
            <th class="label-cell">出单价</th>
            <th class="label-cell">出货量</th>
            <th class="label-cell">运费</th>
            <th class="label-cell">运费支付方式</th>
            <th class="label-cell">收入</th>
            <th class="label-cell">发货仓库</th>
            <th class="label-cell">备注</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="record in records" :key="record.id">
            <td>
                {{ record.id }}
                <a class="link icon-only popup-open" data-popup="#record-popup" v-on:click="form_type = 'update'; form_content = record">
                <i class="icon f7-icons ios-only">edit</i>
                <i class="icon material-icons md-only">edit</i>
                </a>
                <a :db-id="record.id" class="link icon-only" v-on:click="deleteOneRecord(records, $event)">
                <i class="icon f7-icons ios-only">delete</i>
                <i class="icon material-icons md-only">delete</i>
                </a>
            </td>
            <td>{{ record.date }}</td>
            <td>{{ $store.state.id_customer_mapping[record.seller_id] }}</td>
            <td>{{ $store.state.id_customer_mapping[record.buyer_id] }}</td>
            <td>{{ id_product_mapping[record.product_id] }}</td>
            <td>{{ record.in_price }}</td>
            <td>{{ record.out_price }}</td>
            <td>{{ record.quantity }}</td>
            <td>{{ record.carriage }}</td>
            <td>{{ record.carriage_pay_type }}</td>
            <td>{{ (record.out_price - record.in_price) * record.quantity }}</td>
            <td>{{ id_store_mapping[record.store_id] }}</td>
            <td>{{ record.remark }}</td>
            </tr>
        </tbody>
        </table>
    </div>

    <f7-popup id="record-popup" style="overflow: scroll;">
      <f7-block>
        <f7-block-title v-if="form_type === 'add'">记账</f7-block-title>
        <f7-block-title v-else>改账</f7-block-title>
        <form id="record-form" :action="api_host + '/api/wastebook/' + form_type" v-on:submit.prevent="submitOneRecord(records, $event)">
          <input v-if="form_type === 'update'" type="hidden" name="id" :value="form_content.id">
          <f7-list class="inline-labels no-hairlines-md">
            <f7-list-item class="item-input">
              <div class="item-title item-label">日期</div>
              <div class="item-input-wrap">
                <input type="date" name="date" placeholder="交易日期" :value="form_content.date" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">代理</div>
              <div class="item-input-wrap">
                <select name="seller_id" placeholder="代理姓名" :value="form_content.seller_id" required validate>
                  <option disabled selected></option>
                  <option v-for="customer in $store.state.customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                </select>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">客户</div>
              <div class="item-input-wrap">
                <select name="buyer_id" placeholder="客户姓名" :value="form_content.buyer_id" required validate>
                  <option disabled selected></option>
                  <option v-for="customer in $store.state.customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                </select>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">消费项目</div>
              <div class="item-input-wrap">
                <select name="product_id" placeholder="消费项目" :value="form_content.product_id" required validate>
                  <option disabled selected></option>
                  <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
                </select>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">进单价</div>
              <div class="item-input-wrap">
                <input type="number" name="in_price" :value="form_content.in_price" placeholder="单价" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">出单价</div>
              <div class="item-input-wrap">
                <input type="number" name="out_price" :value="form_content.out_price" placeholder="单价" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">出货量</div>
              <div class="item-input-wrap">
                <input type="number" name="quantity" :value="form_content.quantity" placeholder="出货量" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">运费</div>
              <div class="item-input-wrap">
                <input type="number" name="carriage" :value="form_content.carriage"  placeholder="运费" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">运费支付方式</div>
              <div class="item-input-wrap">
                <input type="text" name="carriage_pay_type" :value="form_content.carriage_pay_type"  placeholder="运费支付方式" required validate>
                <span class="input-clear-button"></span>
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">发货仓库</div>
              <div class="item-input-wrap">
                <select name="store_id" placeholder="发货仓库" :value="form_content.store_id"  required validate>
                  <option disabled selected></option>
                  <option v-for="store in stores" :key="store.id" :value="store.id">{{ store.name }}</option>
                </select>
                <!-- <span class="input-clear-button"></span> -->
              </div>
            </f7-list-item>
            <f7-list-item class="item-input">
              <div class="item-title item-label">备注</div>
              <div class="item-input-wrap">
                <input type="text" name="remark" :value="form_content.remark" placeholder="交易备注">
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
import Framework7 from 'framework7/dist/framework7.esm.bundle.js'
import $ from 'jquery'

// var host = 'http://10.0.0.8:8080'
var host = process.env.API_HOST

export default {
  name: 'WasteBook',
  data: function () {
    return {
      api_host: host,
      form_type: 'add',
      form_content: {},
      records: [],
      products: [
        {
          id: 1,
          name: '初己胶原蛋白'
        },
        {
          id: 2,
          name: '豪享受酵素'
        },
        {
          id: 3,
          name: '老北京穴位贴（草）'
        },
        {
          id: 4,
          name: '老北京穴位贴（姜）'
        },
        {
          id: 5,
          name: '豪享受代餐粉'
        },
        {
          id: 6,
          name: '洗衣片'
        },
        {
          id: 7,
          name: '吸色片'
        }
      ],
      id_product_mapping: {
        1: '初己胶原蛋白',
        2: '豪享受酵素',
        3: '老北京穴位贴（草）',
        4: '老北京穴位贴（姜）',
        5: '豪享受代餐粉',
        6: '洗衣片',
        7: '吸色片'
      },
      stores: [
        {
          id: 1,
          name: '厂家'
        },
        {
          id: 2,
          name: '自家'
        }
      ],
      id_store_mapping: {
        1: '厂家',
        2: '自家'
      }
    }
  },
  mounted () {
    refreshRecordData(this.records)
  },
  methods: {
    submitOneRecord: function (records, e) {
      var form = $(e.target)
      $.post(form.attr('action'), form.serialize())
      // $.ajax(form.attr('action'), {
      //   method: 'POST',
      //   data: form.serialize(),
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus) {
          new Framework7().popup.close('#record-popup')
          refreshRecordData(records)
        })
        .fail(function (xhr, textStatus) {
          alert('账目添加失败： ' + xhr.responseText)
        })
    },
    deleteOneRecord: function (records, e) {
      $.post(host + '/api/wastebook/delete', {id: $(e.currentTarget).attr('db-id')})
      // $.ajax(host + '/api/wastebook/delete', {
      //   method: 'POST',
      //   data: {
      //     id: $(e.currentTarget).attr('db-id')
      //   },
      //   xhrFields: { withCredentials: true },
      //   // crossDomain: true
      // })
        .done(function (data, textStatus) {
          refreshRecordData(records)
        })
        .fail(function (xhr, textStatus) {
          alert('账目删除失败：' + xhr.responseText)
        })
    }
  }
}

function refreshRecordData (records) {
  $.get(host + '/api/wastebook/list')
  // $.ajax(host + '/api/wastebook/list', {
  //   method: 'GET',
  //   xhrFields: { withCredentials: true },
  //   // crossDomain: true
  // })
    .done(function (data, textStatus) {
      var obj = JSON.parse(data)
      console.log(obj[0])
      records.splice(0)
      for (var i in obj) {
        records.push(obj[i])
      }
      // records = obj
      // store.commit('updateUsers', obj)
    })
}
</script>
