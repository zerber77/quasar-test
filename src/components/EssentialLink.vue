<template>
  <q-item
    clickable
    @click="getNews(link)"
  >
<!--    @click=""-->
<!--    <q-item-section-->
<!--      v-if="icon"-->
<!--      avatar-->
<!--    >-->
<!--      <q-icon :name="icon" />-->
<!--    </q-item-section>-->

    <q-item-section>
      <q-item-label>{{ agency}}</q-item-label>
<!--      <q-item-label caption>{{ agency }}</q-item-label>-->
    </q-item-section>
  </q-item>
</template>

<script>
import { defineComponent } from 'vue'
import {getNewsByAgency} from "components/modules/getNewsByAgency";
import {getAgencies} from "components/modules/getAgencies";
//import {createRouter as $router, createRouter as router} from "vue-router";
import { useRouter } from 'vue-router';
import routes from "src/router/routes";
import NewsPage from "pages/NewsPage.vue";


export default defineComponent({
  name: 'EssentialLink',
  props: {
    agency: {
      type: String,
      required: true
    },
    link: {
      type: String,
      default: '#'
    },
  },
  setup(){
    const router = useRouter()
//    let obj = {}
    return{
      async getNews(url){
        const {response} = await  getNewsByAgency(url)
//       console.log(response)
        let obj = response.value
        console.log(router)
 //      if (router.currentRoute.value.fullPath.includes('NewsPage')) router.go(-1)
  //      console.log(router)
 //       await router.push({path:'/', query: {ag:url} })
        await router.push({path:'/NewsPage/', query: {ag:url} })
      }
    }
  }
})
</script>
