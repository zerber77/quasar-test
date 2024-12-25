<template>
  <q-page class="flex flex-center">
    <div class="col-12 row">
      <h2>Страница на этапе разработки</h2>
    </div>
<!--    <ModalComponent-->
<!--      v-model="modal"-->
<!--      :boundary-->
<!--    >-->
<!--    </ModalComponent>-->
    <div class="col-12 row">
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
import {getIndex} from "components/modules/getIndex";

const name = ref('1111')
const modal = ref(false)
const boundary = ref('05.12.2024')
let apiServer = ref({})

const options =  ref({
  chart: {
    id: 'vuechart-example'
  },
  xaxis: {
    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
  }
})
const series = ref([{
  name: 'series-1',
  data: [30, 40, 45, 50, 49, 60, 70, 200]
}])

const  click = async ()=>{
  // modal.value = true
  // const {response} = await getIndex()//   await axios.get('http://quasar-test/api/')
  // apiServer.value = response.value
  // console.log(apiServer.value)
  options.value.xaxis.categories.push(1999)
  series.value[0].data.push(180)

}

</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
</style>
