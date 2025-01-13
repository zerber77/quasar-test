<template>
  <q-page class="">
<!--    <div class="row">-->
<!--      <div class="col-12">-->
<!--        <h4>Страница на этапе разработки</h4>-->
<!--      </div>-->
<!--    </div>-->
    <div class="row q-pa-md">

      <div class="col-12 col-md-4 q-pa-md">
              <q-input v-model="word" label="Слово для счета" />
              <CalendarComponent
                @rangeSet = "setRange"
              />
      </div>

      <div class="col-12 col-md-8 q-pa-md">
              <q-btn :label="loading ? 'Остановить загрузку' : 'Посчитать'" color="primary" @click="click()" />
               <q-item>
                  <VueApexCharts
                     type="bar"
                     :options="options"
                     :series="series"
                     class="full"
                   >
                   </VueApexCharts>
                   <q-inner-loading :showing="loading">
                     <q-spinner-gears size="50px" color="primary" />
                   </q-inner-loading>
               </q-item>
      </div>

    </div>

  </q-page>
</template>

<script setup>

import {ref} from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import {getIndex} from "components/modules/getIndex";
import {getWordCountByDate} from "components/modules/statistics/getWordCountByDate";


const word = ref('Trump')
const loading = ref(false)
let dateRange = ref({ from: new Date(Date.now()-86400000 * 9).toISOString().slice(0, 10) , to: new Date().toISOString().slice(0, 10) })
let count = ref({})

const options =  ref({
  chart: {
    id: 'vuechart-example',
    events: {
      click(event, chartContext, opts) {
        console.log(opts.config.series[opts.seriesIndex].name)
        console.log(opts.config.xaxis.categories[opts.dataPointIndex])
      }
    }
  },
  xaxis: {
    categories: []
  },
})
const series = ref([{
  name: word.value,
  data: []
}])


const  getDatesArray = async (start, end) => {
  const arr = [];
  loading.value = true
  while(start <= end) {
    if (!loading.value) break
    ////отлов ишибок try!
    const {response} = await getWordCountByDate(start, word.value)//   await axios.get('http://quasar-test/api/')
    count.value = response.value
    options.value.xaxis.categories.push(start)
    series.value[0].data.push(count.value)
    arr.push(new Date(start).toISOString().slice(0, 10))
    const dt = new Date((start))//.toISOString().slice(0, 10)
    dt.setDate(dt.getDate() + 1)
    start = dt.toISOString().slice(0,10)
  }
  loading.value = false
  return arr;
}

const  click = async ()=>{
  if (!loading.value) {
    options.value.xaxis.categories.length = 0
    series.value[0].data.length = 0
    series.value[0].name = word.value
    await getDatesArray(dateRange.value.from, dateRange.value.to)
  }
  loading.value = false
}

function setRange (range) {
  dateRange.value.from =  range.from
  dateRange.value.to = range.to
}
//getDatesArray(dateRange.value.from, dateRange.value.to)
//   .forEach(async (item)=>{
//   const {response} = await getWordCountByDate(item, 'Trump')//   await axios.get('http://quasar-test/api/')
//   count.value = response.value
//   options.value.xaxis.categories.push(item)
//   series.value[0].data.push(count.value)
// })


</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
.full
  width: 80%
</style>
