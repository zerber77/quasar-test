<template>
  <q-page class="column items-center">
    <div class="row">
      <div class="col-12">
        <h2>Страница на этапе разработки</h2>
      </div>
    </div>

    <CalendarComponent/>
<!--    <ModalComponent-->
<!--      v-model="modal"-->
<!--      :boundary-->
<!--    >-->
<!--    </ModalComponent>-->
    <div class="row justify-center">
      <div class="col-12">
         <q-item>
           <VueApexCharts
             width="500"
             type="bar"
             :options="options"
             :series="series"
           >
           </VueApexCharts>
    <!--        <q-card>-->
    <!--           <h1 class="justify-center">Ответ:{{}}</h1>-->
    <!--         </q-card>-->
            <q-btn label="Dialog" color="primary" @click="click()" />
        </q-item>
      </div>
    </div>

<!--    <div class="q-pa-md row items-start q-gutter-md">-->
<!--        <q-card-->
<!--          v-for="item in apiServer"-->
<!--          class="my-card text-white"-->
<!--          style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"-->
<!--        >-->
<!--          <q-card-section>-->
<!--            <div class="text-h6">{{item.head}}</div>-->
<!--            <div class="text-subtitle2">by {{item.agency}}</div>-->
<!--          </q-card-section>-->

<!--          <q-card-section class="q-pt-none">-->
<!--            {{item.text}}-->
<!--          </q-card-section>-->
<!--        </q-card>-->
<!--    </div>-->
  </q-page>
</template>

<script setup>

import {ref} from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import {getIndex} from "components/modules/getIndex";
import {getWordCountByDate} from "components/modules/statistics/getWordCountByDate";


const name = ref('1111')
const modal = ref(false)
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
  // modal.value = true
  const {response} = await getWordCountByDate('2024-12-19', 'Путин')//   await axios.get('http://quasar-test/api/')
  count.value = response.value
  console.log(count.value)
  options.value.xaxis.categories.push('2024-12-17')
  series.value[0].data.push(count.value)
}

const getDatesArray = (start, end) => {
  const arr = [];
  while(start <= end) {
    arr.push(new Date(start).toISOString().slice(0, 10))
    const dt = new Date((start))//.toISOString().slice(0, 10)
    dt.setDate(dt.getDate() + 1)
    start = dt.toISOString().slice(0,10)
  }
  return arr;
}

getDatesArray(dateRange.value.from, dateRange.value.to).forEach(async(item)=>{
  const {response} = await getWordCountByDate(item, 'Trump')//   await axios.get('http://quasar-test/api/')
  count.value = response.value
  options.value.xaxis.categories.push(item)
  series.value[0].data.push(count.value)
})


</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
</style>
