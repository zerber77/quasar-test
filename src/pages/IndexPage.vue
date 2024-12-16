<template>
  <q-page class="flex flex-center">

    <ModalComponent
      v-model="modal"
      :boundary
    >
    </ModalComponent>

     <q-item>
        <q-card>
           <h1 class="justify-center">Ответ:{{apiServer}}</h1>
         </q-card>
        <q-btn label="Dialog" color="primary" @click="click()" />
    </q-item>



<!--    <post-card v-model="name" label="Name" >-->
<!--      <template v-slot:cardTitle>Named slots</template>-->
<!--    </post-card>-->
<!--    <pre>{{name}}</pre>-->
<!--    <post-card v-model="name" label="Name">-->
<!--      <template v-slot:cardTitle>Named slots22222</template>-->
<!--    </post-card>-->




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

const name = ref('1111')
const modal = ref(false)
const boundary = ref('05.12.2024')
let apiServer = ref({})

const  click = async ()=>{
  modal.value = true
  const {response} = await getIndex()//   await axios.get('http://quasar-test/api/')
  apiServer.value = response.value
  console.log(response.value)
}

</script>
