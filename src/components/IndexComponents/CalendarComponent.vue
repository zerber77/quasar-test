<template>
  <div class="row q-pa-md">
    <q-date
      v-model="dateRange"
      range
      :locale="myLocale"
      mask="YYYY-MM-DD"
      dark
      bordered
      style="height: 330px"
      @range-end = setRange()
    />
<!--    <q-item>{{dateRange}}</q-item>-->
  </div>
</template>

<script setup>
import {ref} from "vue";
const emit = defineEmits(['rangeSet'])
let dateRange = ref({ from: new Date(Date.now()-86400000 * 9).toISOString().slice(0, 10) , to: new Date().toISOString().slice(0, 10) })
const myLocale = ref ({
  days: 'Воскресенье_Понедельник_Вторник_Среда_Четверг_Пятница_Суббота'.split('_'),
    daysShort: 'ВС_ПН_ВТ_СР_ЧТ_ПТ_СБ'.split('_'),
    months: 'Январь_Февраль_Март_Апрель_Май_Июнь_Июль_Август_Сентябрь_Октябрь_Ноябрь_Декабрь'.split('_'),
    monthsShort: 'Янв_Фев_Март_Апр_Mай_Июнь_Июль_Авг_Сент_Окт_Ноя_Дек'.split('_'),
    firstDayOfWeek: 1, // 0-6, 0 - Sunday, 1 Monday, ...
    format24h: true,
    pluralDay: 'дн'
})

const setRange = ()=>{
  emit('rangeSet', dateRange.value)
}

// options (date) {
//   return date >= props.boundary.split('.').reverse().join('/')
// },
</script>

<style scoped>

</style>
