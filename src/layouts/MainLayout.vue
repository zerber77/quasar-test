<template>
  <ErrorMessageComponent
    v-model = "error"
    :errorMessage = "errorMessage"
  />
  <HelpMessageComponent
    v-model = "help"
    :helpMessage = "helpMessage"
  />
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

        <q-toolbar-title>МИРОВЫЕ НОВОСТИ</q-toolbar-title>

<!--        <q-separator dark vertical />-->

        <template v-if="authorised.isAuthenticated">
          <q-item-section avatar>
            <q-icon name="person" />
            <q-item-label v-if="authorised.login">
                {{authorised.login}}
            </q-item-label>
          </q-item-section>
          <q-btn to="/SignUpPage/" @click.prevent="logOut" stretch flat label="ВЫЙТИ" />
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
          :key="link.id"
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
import {defineComponent, onMounted, provide, reactive, ref} from 'vue'
import EssentialLink from 'components/EssentialLink.vue'
import routes from "src/router/routes";
import { useRouter } from 'vue-router';
import {isTokenValid} from "components/modules/auth/isTokenValid";
import useMessageVars from "components/modules/messages/getMessageVars";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";

const linksList = [
  // {
  //   title: 'ВОЙТИ НА САЙТ',
  //   caption: '',
  //   icon: 'person',
  //   link: '/SignUpPage/'
  // },
  {
    id:1,
    title: 'СЕГОДНЯ В НОВОСТЯХ',
    caption: '',
    icon: 'today',
    link: '/TagsPage/'
  },
  {
    id:2,
    title: 'СТАТИСТИКА',
    caption: 'github.com/quasarframework',
    icon: 'list',
    link: '/StatisticsPage/'
  },
  {
    id:3,
    title: 'НОВОСТИ',
    caption: 'quasar.dev',
    icon: 'forum',
    link: '/NewsPage/'
  },
  {
    id:4,
    title: 'КАРТА МИРА',
    caption: '',
    icon: 'map',
    link: '/WorldMapPage/'
  },
  {
    id:5,
    title: 'ОТЗЫВЫ',
    caption: '',
    icon: 'note',
    link: '/ReviewPage/'
  },
  {
    id:6,
    title: 'О ПРОЕКТЕ',
    caption: '',
    icon: 'star',
    link: '/'
  },

]

    const leftDrawerOpen = ref(false)
    const toggleLeftDrawer = () => {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }

    const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()

    const authorised =  reactive({
      isAuthenticated: false,
      login: null,
      name:null,
      review_sended: false
    });
    provide('authorised', authorised);

    onMounted(async ()=>{
      const token = localStorage.getItem('authToken')
      authorised.isAuthenticated = true ///еали есть токен, значит мы авторизованы, проверим его валидность
      if (token){
        try {
          const {response} = await  isTokenValid()
          if(response.value.error){
            setErrorMessage(response.value.error)
            authorised.isAuthenticated = false
          }
          else {
            authorised.isAuthenticated = true
            authorised.login = response.value.token_data.decoded_token.data.login
            authorised.name = response.value.token_data.decoded_token.data.name
            authorised.review_sended = true
          }

        }catch (err){
          setErrorMessage(err)
        }
      }
      else authorised.isAuthenticated = false
    })

    const logOut =() => {
      localStorage.removeItem('authToken')
      authorised.isAuthenticated = false
      authorised.user = null
 //     authorised.review_sended = false
    }



</script>
