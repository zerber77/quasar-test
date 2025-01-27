<template>
  <q-layout view="lHh Lpr lFf">
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

        <q-toolbar-title>
         МИРОВЫЕ НОВОСТИ
        </q-toolbar-title>

<!--        <q-separator dark vertical />-->
<!--        <q-item >-->
<!--            <q-btn-dropdown color="primary" label="Агентства">-->
<!--              <q-list>-->

<!--                <q-item-->
<!--                  v-for="link in essentialLinks"-->
<!--                  clickable-->
<!--                  v-close-popup-->
<!--                  @click="getNews(link.agency)">-->
<!--                  <q-item-section>-->
<!--                    <q-item-label>{{link.agency}}</q-item-label>-->
<!--                  </q-item-section>-->
<!--                </q-item>-->

<!--              </q-list>-->
<!--            </q-btn-dropdown>-->

<!--        </q-item>-->

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
          Инструменты
        </q-item-label>

        <EssentialLink
          v-for="link in linksList"
          :key="link.title"
          :title="link.title"
          :icon="link.icon"
          :link="link.link"
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

const linksList = [
  {
    title: 'СТАТИСТИКА',
    caption: 'github.com/quasarframework',
    icon: 'list',
    link: '/StatisticsPage/'
  },
  {
    title: 'НОВОСТИ',
    caption: 'quasar.dev',
    icon: 'forum',
    link: '/NewsPage/'
  },
  {
    title: 'О ПРОЕКТЕ',
    caption: '',
    icon: 'star',
    link: '/'
  },

]


export default defineComponent({
  name: 'MainLayout',
  components: {
    EssentialLink
  },

  setup () {
    const leftDrawerOpen = ref(false)
 //   let essentialLinks = ref({})
    // onMounted(async ()=>{
    //   const {response} = await  getAgencies()
    //   essentialLinks.value = response.value
    // })

    return {
 //     essentialLinks,
      leftDrawerOpen,
      linksList,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      },
    }
  }
})
</script>
