<template>
  <ErrorMessageComponent
    v-model = "error"
    :errorMessage = "errorMessage"
  />
  <HelpMessageComponent
    v-model = "help"
    :helpMessage = "helpMessage"
  />
  <q-page class="">
    <div class="row q-pa-md">
      <div class="col-12 col-md-4 row">
        <CalendarComponent
          :range = "false"
          @update = "dateChanged"
        />

        <q-icon
          class="q-mt-md text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="setHelpMessage(HelpMessages[0])"
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
        <p v-if="series.length" class="title">Наиболее часто встречающиеся слова в новостях на английском
          <q-icon
            class="text-green-8"
            style="font-size: 2rem"
            name="help"
            @click.prevent="setHelpMessage(HelpMessages[1])"
          />
        </p>
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
        <p v-if="series_ru.length" class="title">Наиболее часто встречающиеся слова в новостях на русском
        <q-icon
          class="text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="setHelpMessage(HelpMessages[2])"
        />
        </p>
      </div>
    </div>
<!--новости-->
    <div class="col-12 q-pa-md flex-center" style="min-height: 200px">
      <h3 v-if="news.length" class="title text-center q-ma-none">Новости со словом {{word}}. Дата {{selectedDate}}
        <q-icon
          class="text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="setHelpMessage(HelpMessages[3])"
        />
      </h3>
      <div v-if="news.length" class="q-pa-lg row q-gutter-md flex-center">
        <q-item class="title text-center q-ma-none">
          <q-inner-loading :showing="loadingNews">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </q-item>

        <q-card
          v-for="item in news"
          :key="item.id"
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
import {onMounted, ref, inject} from "vue";
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import {getWordOfDay} from "components/modules/wordofday/getWordOfDay";
import {getNewsWordDyDate} from "components/modules/statistics/getNewsWordDyDate";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import useMessageVars from "components/modules/messages/getMessageVars";


///НАСТРОЙКИ ПИРОГОВ (вынести в компонент надо)
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
  tooltip: {
    custom: function({series, seriesIndex, dataPointIndex, w}) {
      return '<div style="margin: 3px">' +
        '<span>' + w.config.labels[seriesIndex]+ '</span>' +
        '</div>'
    }
  },
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
  tooltip: {
    custom: function({series, seriesIndex, dataPointIndex, w}) {
      return '<div style="margin: 3px">' +
        '<span>' + w.config.labels[seriesIndex]+ '</span>' +
        '</div>'
    }
  },
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
////////////////////////////////////////////////////////////////////////////////////////
const  series = ref([])
const  series_ru = ref([])
const loading = ref(false)
const loadingNews = ref(false)

const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()
const authorised = inject('authorised');

const news = ref([])
let word = ref('')
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
  news.value.length = 0
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
  console.log('RUS',russian)
  russian.forEach((item) => {
    options_ru.value.labels.push(item.word)
    series_ru.value.push(Number(item.frequency))
  })
}

const loadWords = async () => {
  try {
    const {response} = await  getWordOfDay(selectedDate.value)
    const data = response.value
    if (data.error){
      setErrorMessage(`Ошибка:`+ data.error )
      return
    }
    if (!data.data.length) {
      setErrorMessage(`Для даты ${selectedDate.value} данные отсутствуют`)
    }
    else wordsArrayTransform(data.data)
  }catch (err){   ////все ошибки токена возвращают код 401
    if (err === 'Request failed with status code 401') setErrorMessage(`Ошибка, вы не зарегистрированы в системе. Зарегистрируйтесь или войдите на сайт.`)
    else setErrorMessage(`Неполадки на сервере, попробуйте позже.`)
  }
}

onMounted( ()=>{
  // if (!authorised.isAuthenticated) {
  //   setErrorMessage(`Вы не авторизованы на сайте. Для получения доступа ко всем функциям необходимо зарегистрироваться`)
  //   return
  // }
  loadWords()
})

const dateChanged = (date) => {
  selectedDate.value = date
  if (!selectedDate.value) {
    setErrorMessage(`Дата не выбрана`)
    return
  }
  clearCharts()
  loadWords()
}

const loadNews = async (wordParam ) =>{
  if (!wordParam || !selectedDate.value) return
  loadingNews.value = true
  news.value.length = 0
  word.value  = wordParam
  const {response} = await getNewsWordDyDate(selectedDate.value, wordParam)
  news.value = response.value
  loadingNews.value = false
  if (!news.value.length) {
    setErrorMessage(`Ошибка базы данных: записей от ${selectedDate.value} для слова ${wordParam} не обнаружено`)
  }
}
const HelpMessages = ref([
  'В данном разделе можно увидеть наиболее часто встречающиеся слова в новостях за определенный день. ' +
  'Выберите интересующую дату и дождитесь, когда будут нарисованы диаграммы. Кликнув по интересующему ' +
  'сектору диаграмы вы можете получить список новостей с нужным словом.',

  'На диаграмме представлены часто встречающиеся слова в сообщениях на английском языке. Есть ряд недочетов: ' +
  'слово nato встречается чаще всего, так как новостей от данного источника достаточно много и добавить его в ' +
  'стоп-слова нет возможности, надо будет поработать над этим. Также python с помощью библиотеки NLTK не справляется с именами,' +
  'и множественными числами, поэтому можно встретить два разных слова trump  и trumps.',

  'На диаграмме представлены часто встречающиеся слова в сообщениях на русском языке. Как и для новостей на английском,' +
  'можно встретить одинаковые слова в различных падежах, особенно это характерно для имен.',

  'Ниже представлен список новостей с выбранныи словом. Стоит отметить, что количество упоминаний слов в ' +
  'новостях может отличаться от представленного на диаграмме. Это происходит потому, что python-скрипт на сервере ' +
  'с помощью библиотеки NLTK производит ряд преобразований над словами. Также могут присутствовать новости ' +
  'с предыдущей датой из-за разности часовых поясов.',
])

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
