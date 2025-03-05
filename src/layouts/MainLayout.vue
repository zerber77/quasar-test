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

        <template v-if="authorised">
          <q-item-section avatar>
            <q-icon name="person" />
          </q-item-section>
          <q-btn to="/SignUpPage/" stretch flat label="ВЫЙТИ" />
        </template>

        <template v-else>
          <q-btn to="/SignUpPage/" stretch flat label="ВОЙТИ" />
        </template>

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

<script setup>
import {defineComponent, onMounted, ref} from 'vue'
import EssentialLink from 'components/EssentialLink.vue'
import {getAgencies} from "components/modules/getAgencies";
import routes from "src/router/routes";
import { useRouter } from 'vue-router';

const linksList = [
  {
    title: 'ВОЙТИ НА САЙТ',
    caption: '',
    icon: 'person',
    link: '/SignUpPage/'
  },
  {
    title: 'СЕГОДНЯ В НОВОСТЯХ',
    caption: '',
    icon: 'today',
    link: '/TagsPage/'
  },
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
    title: 'КАРТА МИРА',
    caption: '',
    icon: 'map',
    link: '/WorldMapPage/'
  },
  {
    title: 'О ПРОЕКТЕ',
    caption: '',
    icon: 'star',
    link: '/'
  },
]

    const leftDrawerOpen = ref(false)
    const authorised = ref(false)
    const toggleLeftDrawer = () => {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }
    onMounted(()=>{
      const token = localStorage.getItem('authToken')
      if (token){
        authorised.value = true;
      }
      else authorised.value = false
    })


</script>
