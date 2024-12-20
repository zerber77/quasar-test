<template>
  <q-page class="flex flex-center">

    <ModalComponent
      v-model="modal"
      :boundary
    >
    </ModalComponent>

     <q-item>
        <q-card>
           <h1 class="justify-center">Ответ:{{}}</h1>
         </q-card>
        <q-btn label="Dialog" color="primary" @click="click()" />
    </q-item>

    <div class="q-pa-md row items-start q-gutter-md">
        <q-card
          v-for="item in apiServer"
          class="my-card text-white"
          style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"
        >
          <q-card-section>
            <div class="text-h6">{{item.head}}</div>
            <div class="text-subtitle2">by {{item.agency}}</div>
          </q-card-section>

          <q-card-section class="q-pt-none">
            {{item.text}}
          </q-card-section>
        </q-card>
    </div>
  </q-page>
</template>

<script setup>
import PostCard from 'components/PostCard.vue'
import {customRef, defineComponent, h, reactive, ref, watchEffect} from 'vue'
import render from "@quasar/app-vite/templates/ssr/default/middlewares/render";
import {useQuasar} from "quasar";
import ModalComponent from 'components/Modals/ModalComponent.vue';

import {getIndex} from "components/modules/getIndex";
import axios from "axios";
import routes from "src/router/routes";

const name = ref('1111')
const modal = ref(false)
const boundary = ref('05.12.2024')
let apiServer = ref({})

const  click = async ()=>{
  modal.value = true
  const {response} = await getIndex()//   await axios.get('http://quasar-test/api/')
  apiServer.value = response.value
  console.log(apiServer.value)

}

</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
</style>
