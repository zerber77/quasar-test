<template>
  <q-layout v-if="essentialLinks"  view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

<!--        <q-toolbar-title>-->
<!--         МИРОВЫЕ НОВОСТИ-->
<!--        </q-toolbar-title>-->

        <q-separator dark vertical />
        <q-item >
            <q-btn-dropdown color="primary" label="Агентства">
              <q-list>

                <q-item
                  v-for="link in essentialLinks"
                  clickable
                  v-close-popup
                  @click="getNews(link.agency)">
                  <q-item-section>
                    <q-item-label>{{link.agency}}</q-item-label>
                  </q-item-section>
                </q-item>

              </q-list>
            </q-btn-dropdown>

        </q-item>

<!--        <q-separator dark vertical />-->
<!--        <q-item  to="/" v-ripple exact>-->
<!--          <q-toolbar-title text-color="white" >Штаты</q-toolbar-title>-->
<!--        </q-item>-->

      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list class="">
        <q-item-label
          header
          class="text-h4 text-center"
        >
          Агентства
        </q-item-label>

        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.agency"
          :agency="link.agency"
          :link="link.agency"
        />
      </q-list>
    </q-drawer>
<!--<pre>{{essentialLinks}}</pre>-->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import {defineComponent, onMounted, ref} from 'vue'
import EssentialLink from 'components/EssentialLink.vue'
import {getAgencies} from "components/modules/getAgencies";
import routes from "src/router/routes";
import { useRouter } from 'vue-router';
/*
const linksList = [
  {
    title: 'Docs',
    caption: 'quasar.dev',
    icon: 'school',
    link: 'https://quasar.dev'
  },
  {
    title: 'Github',
    caption: 'github.com/quasarframework',
    icon: 'code',
    link: 'https://github.com/quasarframework'
  },
  {
    title: 'Discord Chat Channel',
    caption: 'chat.quasar.dev',
    icon: 'chat',
    link: 'https://chat.quasar.dev'
  },
  {
    title: 'Forum',
    caption: 'forum.quasar.dev',
    icon: 'record_voice_over',
    link: 'https://forum.quasar.dev'
  },
  {
    title: 'Twitter',
    caption: '@quasarframework',
    icon: 'rss_feed',
    link: 'https://twitter.quasar.dev'
  },
  {
    title: 'Facebook',
    caption: '@QuasarFramework',
    icon: 'public',
    link: 'https://facebook.quasar.dev'
  },
  {
    title: 'Quasar Awesome',
    caption: 'Community Quasar projects',
    icon: 'favorite',
    link: 'https://awesome.quasar.dev'
  }
]
*/

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup () {
    const router = useRouter()
    const leftDrawerOpen = ref(false)
    let essentialLinks = ref({})
    onMounted(async ()=>{
      const {response} = await  getAgencies()
      essentialLinks.value = response.value
    })

    return {
      essentialLinks,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
        console.log(essentialLinks.value)
      },
      getNews(url){
        router.push({path:'/NewsPage/', query: {ag:url} })
      }
    }
  }
})
</script>
