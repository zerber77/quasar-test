<template>
  <q-page class="flex-center">
    <div class="row q-pa-md">
        <div class="col-12 col-md-4">
        <CalendarComponent
          :range = "false"
          @rangeSet = ""
          @rangeStart = ""
          @update = "dateChanged"
        />
  <!--      <SelectAgencyComponent-->
  <!--        v-model:selectedPerson = "agency"-->
  <!--        :inputList = "agencies"-->
  <!--      >-->
  <!--      </SelectAgencyComponent>-->
         </div>

      <div  class="row col-12 col-md-8">
        <div v-if="optionsX.length" class="text-center full" style="min-height: 300px !important;">
          <ChartBarComponent
            :optionsX = "optionsX"
            :seriesY = "seriesY"
            :height = "400"
            @selected = filterAgencies
          />
          <div v-if="optionsX.length" class="title">Количество публикаций по агентствам</div>
        </div>
        <q-item class="text-center full">
          <q-inner-loading :showing="loading">
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </q-item>
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

<script>
import {useRouter} from "vue-router";
import {getLastNewsByAgency} from "components/modules/getLastNewsByAgency";
import {computed, onMounted, onUpdated, ref, watch} from "vue";
import {getCountByAgency} from "components/modules/getCountByAgency";
import {getFirstNewsByAgency} from "components/modules/getFirstNewsByAgency";
import {getLowerNews} from "components/modules/getLowerNews";
import {getUpperNews} from "components/modules/getUpperNews";
import {getAllNewsByDate} from "components/modules/newsByDate/getAllNewsByDate";

import {getAgencies} from "components/modules/getAgencies";
import SelectAgencyComponent from "components/NewsComponent/SelectAgencyComponent.vue";
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import ChartBarComponent from "components/Chart/ChartBarComponent.vue";
import {getWordOfDay} from "components/modules/wordofday/getWordOfDay";

export default {
  name: "NewsPage",
  components: {SelectAgencyComponent, CalendarComponent, ChartBarComponent},
  props: {
    // ag: {
    //   type: String,
    //   required: true
    // },
  },
  setup(props){
 //   let agency = ref(router.currentRoute.value.query)

    let news = ref([])  ///все новости

    let agNewsFiltered = ref([]) ///отфильтрованные по агентствам
    let agNewsPaginated = ref([]) ///отфильтрованные по агентствам с пагинацией

    let agCount = ref(0)  ///количество новостей в  отфильтрованных по агентствам макс для пагинации
    let current = ref(agCount.value)  ///текущее значения пагинации
    const NEWS_PER_PAGE = 5 ///
    let agencies = ref({})  ///агентство - ключ колич-во сообщений - значение
    let loading = ref(false) //индиуатор загрузки

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

    onMounted(async ()=>{
      //const {response} = await  getAgencies()
      optionsX.value.length = 0
      seriesY.value.length = 0
      loading.value = true
      const {response} = await  getAllNewsByDate(new Date().toISOString().slice(0, 10))
      news.value = response.value
      if (news.value.length) {
        countAgencies(news)
      }
      else {

      }
      agNewsFiltered.value = news.value
      setPaginationData()

    })

    //////отслеживаем выбор персоны в селекте searchPersonComponent
    // watch(() => agency, () => {
    //     console.log('watch',agency.value)
    //     getCountAg(agency.value.name)
    //   },
    //   {deep: true,}
    // );

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
      loading.value = false
    }

    watch((current),(newVal,oldVal)=>{
      agNewsPaginated.value = agNewsFiltered.value.slice(current.value * NEWS_PER_PAGE-NEWS_PER_PAGE, current.value * NEWS_PER_PAGE)
    })

    return{
      news, agCount, agNewsFiltered,agNewsPaginated,
      current, agencies,optionsX,seriesY,
      loading,
      setPaginationData,
      paginateNews(val){
        console.log(current.value)
      },

      async dateChanged (date)  {
        news.value.length = 0
        optionsX.value.length = 0
        seriesY.value.length = 0
        loading.value = true
        const {response} = await  getAllNewsByDate(date)
        news.value = response.value
        countAgencies(news)
        agNewsFiltered.value = news.value
        setPaginationData()
      },

      filterAgencies(agency){
        if (!agency) agNewsFiltered.value = news.value
        else agNewsFiltered.value = news.value.filter((item) => item.agency === agency)
        setPaginationData()

      }
    }
  }
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

