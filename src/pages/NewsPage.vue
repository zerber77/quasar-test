<template>
  <q-page class="flex-center">
    <div class="col-12 q-pa-lg row flex-center">
      <q-pagination
        v-model="current"
        :max="agCount"
        input
        @update:model-value = "paginateNews()"
      />
    </div>

    <div class="col-12 q-pa-lg row items-start q-gutter-md flex-center">
      <q-card
        v-for="item in agNews"
        class="my-card text-white"
        style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"
      >
        <q-card-section>
          <div class="text-h6">{{item.head}}</div>
          <div class="text-subtitle2">{{item.agency}} - {{item.pubdate}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          {{item.text}}
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import {useRouter} from "vue-router";
import {getLastNewsByAgency} from "components/modules/getLastNewsByAgency";
import {onMounted, onUpdated, ref, watch} from "vue";
import {getCountByAgency} from "components/modules/getCountByAgency";
import {getFirstNewsByAgency} from "components/modules/getFirstNewsByAgency";
import {getLowerNews} from "components/modules/getLowerNews";
import {getUpperNews} from "components/modules/getUpperNews";

export default {
  name: "NewsPage",
  props: {
    // ag: {
    //   type: String,
    //   required: true
    // },
  },
  setup(props){
    const router = useRouter()
    let agency = ref(router.currentRoute.value.query)
    let agNews = ref({})
    let agCount = ref(0)
    let current = ref(agCount.value)
    const NEWS_PER_PAGE = 5

///////////////////////////////////////////////////////////////////////
    async function getLastNews(ag){
       const {response} = await  getLastNewsByAgency(ag)
       agNews.value = response.value
    }

    async function getFirstNews(ag){
      const {response} = await  getFirstNewsByAgency(ag)
      agNews.value = response.value
    }

    async function getCountAg(ag){
      const {response} = await  getCountByAgency(ag)
      agCount.value = Math.ceil(Number(response.value)/NEWS_PER_PAGE)
      current.value = Number(agCount.value)
    }

    async function getLNews(ag,id){
      const {response} = await  getLowerNews(ag,id)
      agNews.value = response.value
    }
    async function getUNews(ag,id){
      const {response} = await  getUpperNews(ag,id)
      if(response.value.length < NEWS_PER_PAGE) await getLastNews(ag)
      else agNews.value = response.value
    }

///////////////////////////////////////////////////////////////////////
    onMounted(()=>{
      agency.value = router.currentRoute.value.query.ag
      getCountAg(agency.value)
      getLastNews(agency.value)
    })
    onUpdated(()=>{
      agency.value = router.currentRoute.value.query.ag
      getCountAg(agency.value)
      getLastNews(agency.value)
    })

/////////////////////////////////////////////////////////////////////////
    watch((current),(newVal,oldVal)=>{
      console.log(current.value, oldVal)
      if(current.value === 1 ){
        getFirstNews(agency.value)
      }
      else if(current.value === agCount.value){
        getLastNews(agency.value)
      }
      else if(current.value < oldVal){
        getLNews(agency.value,agNews.value[0].id)
      }
      else if(current.value > oldVal){
        getUNews(agency.value,agNews.value[NEWS_PER_PAGE-1].id)
      }
    })

    return{
      agency,agNews,agCount,
      current,
      paginateNews(val){
        console.log(current.value)
      }
    }
  }
}
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
</style>
