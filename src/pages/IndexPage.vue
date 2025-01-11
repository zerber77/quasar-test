<template>
  <q-page class="column items-center">
    <div class="row">
      <div class="col-12">
        <h4>Страница на этапе разработки</h4>
      </div>
    </div>

    <CalendarComponent/>
<!--    <ModalComponent-->
<!--      v-model="modal"-->
<!--      :boundary-->
<!--    >-->
<!--    </ModalComponent>-->
    <div class="row justify-center">
      <q-btn v-if="loading" label="Остановить загрузку" color="primary" @click="click()" />
      <div class="col-12">
         <q-item>
           <VueApexCharts
             width="500"
             type="bar"
             :options="options"
             :series="series"
           >
           </VueApexCharts>
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


const name = ref('1111')
const loading = ref(false)
let dateRange = ref({ from: new Date(Date.now()-86400000 * 9).toISOString().slice(0, 10) , to: new Date().toISOString().slice(0, 10) })
let count = ref({})

const options =  ref({
  chart: {
    id: 'vuechart-example'
  },
  xaxis: {
    categories: []
  }
})
const series = ref([{
  name: 'series-1',
  data: []
}])

const  click = async ()=>{
 loading.value = false
}

const  getDatesArray = async (start, end) => {
  const arr = [];
  loading.value = true
  while(start <= end) {
    if (!loading.value) break
    const {response} = await getWordCountByDate(start, 'Trump')//   await axios.get('http://quasar-test/api/')
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

getDatesArray(dateRange.value.from, dateRange.value.to)
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
</style>
