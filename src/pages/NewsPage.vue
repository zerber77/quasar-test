<template>
  <q-page class="flex flex-center">
    <div class="q-pa-lg flex flex-center">
      <q-pagination
        v-model="current"
        :max="agCount"
        input
      />
    </div>

    <div class="q-pa-md row items-start q-gutter-md">
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
import {getNewsByAgency} from "components/modules/getNewsByAgency";
import {onMounted, onUpdated, ref} from "vue";
import {getCountByAgency} from "components/modules/getCountByAgency";

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

    async function getNews(ag){
       const {response} = await  getNewsByAgency(ag)
       agNews.value = response.value
    }

    async function getCountAg(ag){
      const {response} = await  getCountByAgency(ag)
      agCount.value = Number(response.value)
      current.value = Number(agCount.value)
    }

    onMounted(()=>{
      agency.value = router.currentRoute.value.query.ag
      getCountAg(agency.value)
      getNews(agency.value)
    })
    onUpdated(()=>{
      agency.value = router.currentRoute.value.query.ag
      getCountAg(agency.value)
      getNews(agency.value)
    })


//    let obj = {}
    return{
      agency,agNews,agCount,
      current,
    }
  }
}
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
</style>
