<template>
  <ErrorMessageComponent
    v-model = "error"
    :errorMessage = "errorMessage"
  />
  <HelpMessageComponent
    v-model = "help"
    :helpMessage = "helpMessage"
  />
  <q-page class="flex-center">
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
    <!--      <SelectAgencyComponent-->
    <!--        v-model:selectedPerson = "agency"-->
    <!--        :inputList = "agencies"-->
    <!--      >-->
    <!--      </SelectAgencyComponent>-->
        </div>
          <div v-if="!authorised.isAuthenticated" class="col-12 col-md-8 text-center">
              <h4 class="text-green-10">Для получения доступа к функциям сайта необходимо <router-link to="/SignUpPage/">войти или зарегистрироваться</router-link></h4>
          </div>
        <div  class="row col-12 col-md-8">
            <div v-if="optionsX.length" class="text-center full" style="min-height: 300px !important;">
              <ChartBarComponent
                :optionsX = "optionsX"
                :seriesY = "seriesY"
                :height = "400"
                @selected = filterAgencies
              />
              <div v-if="optionsX.length" class="title">Количество публикаций по агентствам
                <q-icon
                  class="text-green-8"
                  style="font-size: 2rem"
                  name="help"
                  @click.prevent="setHelpMessage(HelpMessages[1])"
                />
              </div>
            </div>
            <!-- <q-item class="text-center full">
              <q-inner-loading :showing="loading">
                <q-spinner-gears size="50px" color="primary" />
              </q-inner-loading>
            </q-item> -->
          </div>
    </div>

    <div v-if="news.length" class="col-12 q-pa-lg row flex-center">
      <q-pagination
        v-model="current"
        :max="agCount"
        input
        size="30px"
        color="secondary"
        @update:model-value = "paginateNews()"
      />
    </div>
<!--flex-center items-start-->
    <div v-if="news.length" class="q-pa-lg row q-gutter-md flex-center">
      <q-card
        v-for="item in agNewsPaginated"
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
           <div><a :href="item.link" target="_blank">Ссылка</a></div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script setup>
import {useRouter} from "vue-router";
import {getLastNewsByAgency} from "components/modules/getLastNewsByAgency";
import {inject, onMounted, onUpdated, ref, watch} from "vue";
import {getAllNewsByDate} from "components/modules/newsByDate/getAllNewsByDate";

import {getAgencies} from "components/modules/getAgencies";
import SelectAgencyComponent from "components/NewsComponent/SelectAgencyComponent.vue";
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import ChartBarComponent from "components/Chart/ChartBarComponent.vue";
import {getWordOfDay} from "components/modules/wordofday/getWordOfDay";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import useMessageVars from "components/modules/messages/getMessageVars";
import {clearLoginData} from "components/SignUpComponents/clearLoginData";

 //   let agency = ref(router.currentRoute.value.query)

    let news = ref([])  ///все новости

    let agNewsFiltered = ref([]) ///отфильтрованные по агентствам
    let agNewsPaginated = ref([]) ///отфильтрованные по агентствам с пагинацией

    let agCount = ref(0)  ///количество новостей в  отфильтрованных по агентствам макс для пагинации
    let current = ref(agCount.value)  ///текущее значения пагинации
    const NEWS_PER_PAGE = 5 ///
    let agencies = ref({})  ///агентство - ключ колич-во сообщений - значение

    const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()
    const authorised = inject('authorised');
/////оси для статистики агентств
    let optionsX = ref([])
    let seriesY = ref([])

////подсчет сообщений по агентствам и построение графика
    const countAgencies = (input_news) =>{
      optionsX.value.length = 0
      seriesY.value.length = 0
      agencies.value = input_news.value.reduce((acc,item) =>{
        if (acc[item.agency] === undefined) acc[item.agency] = 1
        else acc[item.agency]  = acc[item.agency] + 1
        return acc
      },{})
      for (let key in agencies.value){
        optionsX.value.push(key)
        seriesY.value.push(agencies.value[key])
      }
    }

/////////загрузка данных по выбору даты
    const loadNews = async(date, free) =>{
      try{
        const {response} = await  getAllNewsByDate(date, free)
        news.value = response.value
        if (news.value.error){
          setErrorMessage(`Ошибка:`+ news.value.error ) ///ошибка истек токен
          clearLoginData(authorised)
          return
        }
        
        if (news.value.length) {
          countAgencies(news)
          agNewsFiltered.value = news.value
          setPaginationData()
        }
        else {
          setErrorMessage(`Для выбранной даты новостей в базе данных не найдено`)
        }
    }catch(err){
      setErrorMessage(`Ошибка:`+ err)
      return
    }
    }

    onMounted( ()=>{
      //const {response} = await  getAgencies()
      optionsX.value.length = 0
      seriesY.value.length = 0
      //const token = localStorage.getItem('authToken')
      authorised.isAuthenticated = true ///еали есть токен, значит мы авторизованы, его валидность будет проверена в запросе
      loadNews(new Date().toISOString().slice(0, 10), true)
  
    })

    
///////////////////////////////////////////////////////////////////////
//     async function getLastNews(ag){
//        const {response} = await  getLastNewsByAgency(ag)
//        agNews.value = response.value
//     }
//
//     async function getFirstNews(ag){
//       const {response} = await  getFirstNewsByAgency(ag)
//       agNews.value = response.value
//     }
//
//     async function getCountAg(ag){
//       const {response} = await  getCountByAgency(ag)
//       agCount.value = Math.ceil(Number(response.value)/NEWS_PER_PAGE)
//       current.value = Number(agCount.value)
//     }
//
//     async function getLNews(ag,id){
//       const {response} = await  getLowerNews(ag,id)
//       agNews.value = response.value
//     }
//     async function getUNews(ag,id){
//       const {response} = await  getUpperNews(ag,id)
//       if(response.value.length < NEWS_PER_PAGE) await getLastNews(ag)
//       else agNews.value = response.value
//     }

///////////////////////////////////////////////////////////////////////
//     onMounted(()=>{
//  //     agency.value = router.currentRoute.value.query.ag
//       getCountAg(agency.value)
//       getLastNews(agency.value)
//     })
//     onUpdated(()=>{
//       getCountAg(agency.value.name)
//       getLastNews(agency.value.name)
//     })

/////////////////////////////////////////////////////////////////////////
const setPaginationData = () =>{
      agCount.value = Math.ceil(agNewsFiltered.value.length/NEWS_PER_PAGE)
      current.value = agCount.value
      agNewsPaginated.value = agNewsFiltered.value.slice(-NEWS_PER_PAGE)
    }

    watch((current),(newVal,oldVal)=>{
      agNewsPaginated.value = agNewsFiltered.value.slice(current.value * NEWS_PER_PAGE-NEWS_PER_PAGE, current.value * NEWS_PER_PAGE)
    })

function paginateNews(val){
 // console.log(current.value)
}

async function dateChanged (date)  {
  news.value.length = 0
  optionsX.value.length = 0
  seriesY.value.length = 0
  loadNews(date, false)
}

function filterAgencies(agency){
  if (!agency) agNewsFiltered.value = news.value
  else agNewsFiltered.value = news.value.filter((item) => item.agency === agency)
  setPaginationData()
}

const HelpMessages = ref([
  'В данном разделе можно посмотреть все новости в определенный день.' +
  'Выберите нужную дату на календаре и дождитесь загрузки диаграммы и новостей.' +
  'Есть одна особенность: из-за различия часовых поясов некоторые новости будут иметь предыдущую дату.',

  'На диаграмме представлено количество публикаций новостей по агентствам в выбранный день.' +
  'Ниже представлены все новости с пагинацией (листанием). Кликнув по столбику на диаграмме,' +
  'можно отфильтровать новости только для выбранного агентства. ',

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

