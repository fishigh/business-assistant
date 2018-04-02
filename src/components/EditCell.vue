<template>
  <div :class="rowData['attr']['class']" :data-popup="rowData['attr']['data-popup']" style="width: 100%" v-on:click="cli(rowData, field)">{{ rowData[field] }}</div>
</template>

<script>
export default {
  name: 'EditCell',
  props: {
    rowData: Object,
    field: String,
    index: Number
  },
  data: function () {
    return {
    }
  },
  methods: {
    cli: function (rowData, field) {
      console.log('emit')
      console.log(rowData)
      console.log(field)
      this.$emit('on-custom-comp', {id: rowData['id'], date: field})
    }
  }
}
</script>
