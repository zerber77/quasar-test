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
<!--    <div class="row">-->
<!--      <div class="col-12">-->
<!--        <h4>Страница на этапе разработки</h4>-->
<!--      </div>-->
<!--    </div>-->
    <div class="row q-pa-md">
      <div class="col-12 col-md-4">
             <div class="row">
                <q-input
                  v-model="word"
                  label="Слово для счета"
                  :input-style="{ fontSize: '26px' }"
                />
                <q-icon
                  class="q-mt-md text-green-8"
                  style="font-size: 2rem"
                  name="help"
                  @click.prevent="setHelpMessage(HelpMessages[0])"
                />
             </div>
              <CalendarComponent
                :range = "true"
                :disabled = "loading"
                @rangeSet = "setRange"
                @rangeStart = "rangeStart"
                @update = "dateSet"
              />
      </div>
      <div v-if="authorised.isAuthenticated" class="col-12 col-md-8 text-center">
              <q-btn
                v-if = "calcButton && word.length > 1"
                :label="'Посчитать'"
                color="primary"
                @click="click()"
              />
          <div class="col-12 col-md-12 text-center" style="min-height: 300px !important;">
                  <ChartBarComponent
                    :optionsX = "options"
                    :seriesY = "series"
                    :height = 400
                    :word = "word"
                    @selected = loadNews
                  />
                    <!-- <q-inner-loading :showing="loading">
                      <q-spinner-gears size="50px" color="primary" />
                    </q-inner-loading> -->
                    <div v-if="options.length" class="title">Количество упоминаний слова {{word}} в новостях
                      <q-icon
                        class="text-green-8"
                        style="font-size: 2rem"
                        name="help"
                        @click.prevent="setHelpMessage(HelpMessages[1])"
                      />
                    </div>
          </div>

      </div>
    </div>

    <div v-if="optionsX.length" class="row">
        <div class="col-12 col-md-4 text-center" style="min-height: 300px !important;">
          <ChartBarComponent
            :optionsX = "optionsX"
            :seriesY = "seriesY"
            @selected = filterAgencies
          />
          <div v-if="optionsX.length" class="title">Количество упоминаний слова {{word}} по агентствам
            <q-icon
              class="text-green-8"
              style="font-size: 2rem"
              name="help"
              @click.prevent="setHelpMessage(HelpMessages[2])"
            />
          </div>
        </div>
    </div>

    <div v-if="authorised.isAuthenticated" class="col-12 q-pa-md " style="min-height: 200px">
      <div v-if="optionsX.length" class="q-pa-lg row q-gutter-md flex-center">
        <!-- <q-item>
            <q-inner-loading :showing="loadingNews">
              <q-spinner-gears size="50px" color="primary" />
            </q-inner-loading>
        </q-item > -->
          <q-card
            v-for="item in filteredNews"
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

import {inject, onMounted, ref} from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import ChartBarComponent from "components/Chart/ChartBarComponent.vue";
import {getIndex} from "components/modules/getIndex";
import {getWordCountByDate} from "components/modules/statistics/getWordCountByDate";
import {getNewsWordDyDate} from "components/modules/statistics/getNewsWordDyDate";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import useMessageVars from "components/modules/messages/getMessageVars";

const word = ref('Trump')
const calcButton = ref(true)
const loading = ref(false)
const loadingNews = ref(false)
let dateRange = ref({ from: new Date(Date.now()-86400000 * 9).toISOString().slice(0, 10) , to: new Date().toISOString().slice(0, 10) })
let count = ref({})
let news = ref([])
let filteredNews = ref([])

const authorised = inject('authorised');
const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()

let options = ref([])
const series = ref([])
/////оси для статистики агентств
let optionsX = ref([])
let seriesY = ref([])

onMounted(()=>{
  if (!authorised.isAuthenticated) {
    setErrorMessage(`Вы не авторизованы на сайте. Для получения доступа ко всем функциям необходимо зарегистрироваться`)
  }
})

const loadNews = async (date, word ) =>{
  if (!word || !date) return
  loadingNews.value = true
  news.value = []
  optionsX.value.length = 0
  seriesY.value.length = 0
  const {response} = await getNewsWordDyDate(date, word)
  news.value = response.value
//  console.log('news',news.value)
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

const  getDatesArray = async (start, end) => {
  const arr = [];
  loading.value = true
  try {
    while (start <= end) {
      if (!loading.value) break
      ////отлов ишибок try!
      const {response} = await getWordCountByDate(start, word.value)//   await axios.get('http://quasar-test/api/')
      count.value = response.value
      if (count.value.error){
        setErrorMessage(`Ошибка:`+ count.value.error )
        authorised.isAuthenticated = false
        return
      }
      options.value.push(start)
      series.value.push(count.value)
      // options.value.xaxis.categories.push(start)
      // series.value[0].data.push(count.value)
      arr.push(new Date(start).toISOString().slice(0, 10))
      const dt = new Date((start))//.toISOString().slice(0, 10)
      dt.setDate(dt.getDate() + 1)
      start = dt.toISOString().slice(0, 10)
    }
    if (series.value.every(item => item === '0')) {
      setErrorMessage(`Для выбранного диапазона дат данные отсутствуют`)
    }
    
    loading.value = false
    return arr
  }catch (err){
    setErrorMessage(`Ошибка сервера, попробуйте позже`)
  }
}

const  click = async ()=>{
  if (!loading.value) {
    news.value = {}
    options.value.length = 0
    series.value.length = 0
    optionsX.value.length = 0
    seriesY.value.length = 0
    await getDatesArray(dateRange.value.from, dateRange.value.to)
  }
  loading.value = false
}

function setRange (range) {
  if (!authorised.isAuthenticated) calcButton.value = false
  else calcButton.value = true
  if (typeof range === 'string') {
    dateRange.value.from = dateRange.value.to = range ///ыбрана одна дата
    return
  }
  dateRange.value.from =  range.from
  dateRange.value.to = range.to
}
const rangeStart = () =>{
  console.log('rangeStart')
  calcButton.value = false
}
const dateSet = (date) =>{
  console.log('dateSet', date)
}
const filterAgencies = (agency) => {
  if (!agency) {
    filteredNews.value = news.value
    return
  }
  filteredNews.value = news.value.filter((item) => item.agency === agency)
}

const HelpMessages = ref([
  'В данном разделе можно посчитать, сколько раз встречается слово в новостях за определенный промежуток времени.' +
  'Наберите интересующее слово в поле ввода слева (не менее 2-х букв), затем укажите диапазон дат в календаре.' +
  'Если все введено правильно, появится кнопка "ПОСЧИТАТЬ". Нажмите ее и дождитесь, когда будет нарисована диаграмма.',

  'Кликнув по столбику на диаграмме, вы получите все новости с введенным словом за указанный день. ' +
  'Также будет нарисована диаграмма с количеством опубликованных новостей по инф. агентствам.',

  'На диаграмме представлены все новости с интересующим словом в выбранный день,' +
  'Кликнув по сторлбику, новости будут отфильтрованы  по выбранному агентству, ',
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
