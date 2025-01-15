<template>
  <q-page class="flex-center">
    <div class="col-12 q-pa-lg row flex-center">
      <SelectAgencyComponent
        v-model:selectedPerson = "agency"
        :inputList = "agencies"
      >

      </SelectAgencyComponent>
    </div>

    <div class="col-12 q-pa-lg row flex-center">
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
    <div class="q-pa-lg row q-gutter-md flex-center">
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
import {getAgencies} from "components/modules/getAgencies";
import SelectAgencyComponent from "components/NewsComponent/SelectAgencyComponent.vue";

export default {
  name: "NewsPage",
  components: {SelectAgencyComponent},
  props: {
    // ag: {
    //   type: String,
    //   required: true
    // },
  },
  setup(props){
 //   let agency = ref(router.currentRoute.value.query)
    let agency = ref({})
    let agNews = ref({})
    let agCount = ref(0)
    let current = ref(agCount.value)
    const NEWS_PER_PAGE = 5

    let agencies = ref([])
    onMounted(async ()=>{
      const {response} = await  getAgencies()
      for (let i = 0; i < response.value.length; ++i)
        agencies.value.push({'id': i,'name':  response.value[i].agency})
      console.log('ag',agencies.value)
      agency.value = agencies.value[0]
    })

    //////отслеживаем выбор персоны в селекте searchPersonComponent
    watch(() => agency, () => {
        console.log('watch',agency.value)
        getCountAg(agency.value.name)
//        getLastNews(agency.value.name)
      },
      {deep: true,}
    );

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
    watch((current),(newVal,oldVal)=>{
      console.log(current.value, oldVal)
      if(current.value === 1 ){
        getFirstNews(agency.value.name)
      }
      else if(current.value === agCount.value){
        getLastNews(agency.value.name)
      }
      else if(current.value < oldVal){
        getLNews(agency.value.name,agNews.value[0].id - NEWS_PER_PAGE + 1)
      }
      else if(current.value > oldVal){
        getUNews(agency.value.name,agNews.value[NEWS_PER_PAGE-1].id)
      }
    })

    return{
      agency,agNews,agCount,
      current, agencies,
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
