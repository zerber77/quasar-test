<template>
  <q-page class="">
<!--    <div class="row">-->
<!--      <div class="col-12">-->
<!--        <h4>Страница на этапе разработки</h4>-->
<!--      </div>-->
<!--    </div>-->
    <div class="row q-pa-md">
      <div class="col-12 col-md-4">
              <q-input
                v-model="word"
                label="Слово для счета"
                :input-style="{ fontSize: '26px' }"
              />
              <CalendarComponent
                @rangeSet = "setRange"
                @rangeStart = "rangeStart"
              />
      </div>
      <div class="col-12 col-md-8 text-center">
              <q-btn
                v-if = "calcButton"
                :label="loading ? 'Остановить загрузку' : 'Посчитать'"
                color="primary"
                @click="click()"
              />
               <q-item>
                  <VueApexCharts
                     type="bar"
                     :options="options"
                     :series="series"
                     class="full"
                     height="400"
                   >

                   </VueApexCharts>
                   <q-inner-loading :showing="loading">
                     <q-spinner-gears size="50px" color="primary" />
                   </q-inner-loading>
               </q-item>
        <div v-if="options.xaxis.categories.length" class="title">Количество упоминаний слова {{word}} в новостях</div>
      </div>
    </div>

    <div v-if="optionsX.length" class="row">
        <div class="col-12 col-md-4 text-center" style="min-height: 300px !important;">
          <ChartBarComponent
            :optionsX = "optionsX"
            :seriesY = "seriesY"
            @selected = filterAgencies
          />
          <div v-if="optionsX.length" class="title">Количество упоминаний слова {{word}} по агентствам</div>
        </div>

    </div>

    <div class="col-12 q-pa-md " style="min-height: 200px">
      <div class="q-pa-lg row q-gutter-md flex-center">
        <q-item>
            <q-inner-loading :showing="loadingNews">
              <q-spinner-gears size="50px" color="primary" />
            </q-inner-loading>
        </q-item>
          <q-card
            v-if="optionsX.length"
            v-for="item in filteredNews"
            class="my-card text-white"
            style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"
          >
          <q-card-section>
            <div class="text-h6">{{item.head}}</div>
            <div class="text-subtitle2">{{item.agency}} - {{item.pubdate}}</div>
          </q-card-section>

          <q-card-section class="q-pt-none">
            {{item.text}}
            <div><a :href="item.link" target="_blank" style="color: yellow">Ссылка</a></div>
          </q-card-section>
        </q-card>
      </div>

    </div>

  </q-page>
</template>

<script setup>

import {ref} from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import ChartBarComponent from "components/Chart/ChartBarComponent.vue";
import {getIndex} from "components/modules/getIndex";
import {getWordCountByDate} from "components/modules/statistics/getWordCountByDate";
import {getNewsWordDyDate} from "components/modules/statistics/getNewsWordDyDate";

const word = ref('Trump')
const calcButton = ref(true)
const loading = ref(false)
const loadingNews = ref(false)
let dateRange = ref({ from: new Date(Date.now()-86400000 * 9).toISOString().slice(0, 10) , to: new Date().toISOString().slice(0, 10) })
let count = ref({})
let news = ref([])
let filteredNews = ref([])

/////оси для статистики агентств
let optionsX = ref([])
let seriesY = ref([])

const loadNews = async (word , date ) =>{
  if (!word || !date) return
  loadingNews.value = true
  news.value = []
  optionsX.value.length = 0
  seriesY.value.length = 0
  const {response} = await getNewsWordDyDate(date, word )
  news.value = response.value
  filteredNews.value = news.value
  loadingNews.value = false
  const agencyCounter = news.value.reduce((acc,item) =>{
    if (acc[item.agency] === undefined) acc[item.agency] = 1
    else acc[item.agency]  = acc[item.agency] + 1
    return acc
  },{})
  for (let key in agencyCounter){
    optionsX.value.push(key)
    seriesY.value.push(agencyCounter[key])
  }
}

///////////////////////////////////////////// настройки графика
const options =  ref({
  chart: {
    id: 'vuechart-example',
    height: '500',
    events: {
      click  (event, chartContext, opts){
          if (opts.seriesIndex === -1 || opts.dataPointIndex === -1) return
          loadNews(opts.config.series[opts.seriesIndex].name, opts.config.xaxis.categories[opts.dataPointIndex])
      }
    }
  },
  xaxis: {
    categories: [],
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: true,
    }

  },
  plotOptions: {
    bar: {
      borderRadius: 10,
    }
  },
})
const series = ref([{
  name: word.value,
  data: []
}])
///////////////////////////////////////////////////

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
    news.value = {}
    options.value.xaxis.categories.length = 0
    series.value[0].data.length = 0
    series.value[0].name = word.value

    optionsX.value.length = 0
    seriesY.value.length = 0

    await getDatesArray(dateRange.value.from, dateRange.value.to)
  }
  loading.value = false
}

function setRange (range) {
  calcButton.value = true
  if (typeof range === 'string') {
    dateRange.value.from = dateRange.value.to = range ///ыбрана одна дата
    return
  }
  dateRange.value.from =  range.from
  dateRange.value.to = range.to
}
const rangeStart = () =>{
  calcButton.value = false
}

const filterAgencies = (agency) => {
  if (!agency) {
    filteredNews.value = news.value
    return
  }
  filteredNews.value = news.value.filter((item) => item.agency === agency)
}



</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
.full
  width: 90%

.title
  font-size: 1.5em
  font-weight: bold
  color: #2669a1
.chart
  min-height: 300px !important
  max-height: 500px !important
</style>
