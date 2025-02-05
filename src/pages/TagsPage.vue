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

  </q-page>
</template>

<script setup>
import {onMounted, ref} from "vue";
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import {getWordOfDay} from "components/modules/wordofday/getWordOfDay";
const options = ref({
  chart: {
    width: 580,
    type: 'pie',
  },
  labels: [],
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 500
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
  },
  labels: [],
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 500
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

// Функция реобразования по языкам и передача на графику
const wordsArrayTransform = (arr) =>{
  clearCharts()
  let english = arr.filter(item => isEnglish(item.word));
  english.forEach((item) => {
    options.value.labels.push(item.word)
    series.value.push(Number(item.frequency))
  })
  let russian = arr.filter(item => isRussian(item.word));
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
  const {response} = await  getWordOfDay(new Date().toISOString().slice(0, 10))
  const data = response.value
  wordsArrayTransform(data)

})

const dateChanged = async (date) => {
  const {response} = await  getWordOfDay(date)
  const data = response.value
  wordsArrayTransform(data)
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
