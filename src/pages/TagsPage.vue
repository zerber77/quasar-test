<template>

  <q-page class="">
    <div class="row q-pa-md">
      <div class="col-12 col-md-4">
        <CalendarComponent
          :range = "false"
          @rangeSet = ""
          @rangeStart = ""
          @update = "dateChanged"
        />
      </div>
      <div class="col-12 col-md-4 text-center">
        <q-item>
          <VueApexCharts
            type="pie"
            :options="options"
            :series="series"
            class="full"
            height="500"
          >

          </VueApexCharts>
          <q-inner-loading :showing="loading">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </q-item>
      </div>

      <div class="col-12 col-md-4 text-center">
        <q-item>
          <VueApexCharts
            type="pie"
            :options="options_ru"
            :series="series_ru"
            class="full"
            height="500"
          >

          </VueApexCharts>
          <q-inner-loading :showing="loading">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </q-item>
      </div>
    </div>
<!--новости-->
    <div class="col-12 q-pa-md " style="min-height: 200px">
      <div class="q-pa-lg row q-gutter-md flex-center">
        <q-item>
          <q-inner-loading :showing="loadingNews">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </q-item>
        <q-card
          v-if="news.length"
          v-for="item in news"
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
import {onMounted, ref} from "vue";
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import {getWordOfDay} from "components/modules/wordofday/getWordOfDay";
import {getNewsWordDyDate} from "components/modules/statistics/getNewsWordDyDate";
const options = ref({
  chart: {
    width: 580,
    type: 'pie',
    events: {
      click  (event, chartContext, opts){
        if (opts.dataPointIndex === -1) return
        loadNews(opts.config.labels[opts.dataPointIndex])
      }
    }
  },
  labels: [],
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 300
      },
      legend: {
        position: 'bottom'
      }
    }
  }]
})

const options_ru = ref({
  chart: {
    width: 580,
    type: 'pie',
    events: {
      click  (event, chartContext, opts){
        if (opts.dataPointIndex === -1) return
        loadNews(opts.config.labels[opts.dataPointIndex])
      }
    }
  },
  labels: [],
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 300
      },
      legend: {
        position: 'bottom'
      }
    }
  }]
})

const  series = ref([])
const  series_ru = ref([])
const loading = ref(false)
const loadingNews = ref(false)
const news = ref([])
let russian, english
let selectedDate = ref(new Date().toISOString().slice(0, 10))
// Функция для проверки, является ли слово английским
function isEnglish(word) {
  return /^[A-Za-z]+$/.test(word); // Регулярное выражение для английских букв
}
// Функция для проверки, является ли слово русским
function isRussian(word) {
  return /^[А-Яа-яёЁ]+$/.test(word); // Регулярное выражение для русских букв
}
const clearCharts = () =>{
  options.value.labels.length = 0
  series.value.length = 0
  options_ru.value.labels.length = 0
  series_ru.value.length = 0
}

// Функция преобразования по языкам и передача на графику
const wordsArrayTransform = (arr) =>{
  clearCharts()
  english = arr.filter(item => isEnglish(item.word));
  english.forEach((item) => {
    options.value.labels.push(item.word)
    series.value.push(Number(item.frequency))
  })
  russian = arr.filter(item => isRussian(item.word));
  // let result = russian.reduce((acc, obj) => {
  //   debugger
  //   let word = Object.keys(obj)[0]; // Получаем ключ (слово)
  //   let count = obj[word]; // Получаем значение (частоту)
  //
  //   // Удаляем окончания слов, оставляя только основу
  //   let baseWord = word.replace(/(а|у|ю|и|ы|ой|ом|е|ям)$/iu, '');
  //
  //   if (acc[baseWord]) {
  //     acc[baseWord] += count; // Суммируем частоту, если слово уже есть
  //   } else {
  //     acc[baseWord] = count; // Добавляем новое слово с его частотой
  //   }
  //   return acc;
  // }, {});
  // let finalResult = Object.keys(result).map(key => ({ [key]: result[key] }));
  russian.forEach((item) => {
    options_ru.value.labels.push(item.word)
    series_ru.value.push(Number(item.frequency))
  })
}

onMounted(async ()=>{
  const {response} = await  getWordOfDay(selectedDate.value)
  const data = response.value
  wordsArrayTransform(data)

})

const dateChanged = async (date) => {
  selectedDate.value = date
  const {response} = await  getWordOfDay(date)
  const data = response.value
  wordsArrayTransform(data)
}

const loadNews = async (word ) =>{
  if (!word || !selectedDate.value) return
  loadingNews.value = true
  news.value.length = 0
  const {response} = await getNewsWordDyDate(selectedDate.value, word )
  news.value = response.value
  loadingNews.value = false
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
