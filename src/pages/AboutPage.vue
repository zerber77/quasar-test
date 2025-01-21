<template>
  <div class="text-center q-pa-md flex flex-md-center">
    <q-card v-for="item in cardOptions" class="my-card q-pa-md q-ma-md" >
      <q-img
        :src="item.image"
        :style="item.style"
      />
      <q-card-section>
        <div class="text-h5">{{item.title}}</div>
        <div class="text-body1 text-black">
          {{item.description}}
        </div>
      </q-card-section>

      <q-card-actions>
        <q-btn v-if="item.path.length" :to="item.path" flat label="Перейти" class="bg-green" />
        <q-space />
        <q-btn
          color="black"
          round
          flat
          dense
          :icon="expanded ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
          @click="expanded = !expanded"
        />
      </q-card-actions>

      <q-slide-transition>
        <div v-show="expanded">
          <q-separator />
          <q-card-section class="text-subtitle2">
            {{item.text}}
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>

  </div>
</template>

<script setup>
import {ref} from "vue";

const expanded = ref(false)

const cardOptions = ref([
  {
    title:'Мировые новости, статистика и анализ',
    description:'Краткое описание проекта',
    image:'/icons/favicon-32x32.png',
    style:'height: 100px; max-width: 100px',
    path:'',
    text:'Проект создан с помощью javascript-фреймворка Quasar (Vue3).\n' +
      '            Бэкенд представлят собой набор API, разработанный на PHP.\n' +
      '            База данных заполняется скриптом, запускаемым на хостинге по расписанию\n' +
      '            5 раз в сутки с 2019 года и на данный момент (январь 2025 г.) содержит около 4,5 млн. записей.'
  },
  {
    title:'СТАТИСТИКА',
    description:' В данном разделе можно увидеть частоту встречаемости слов в тексте новостей и заголовков.',
    image:'../src/assets/images/vuechart-example.png',
    style:'',
    path:'/',
    text:'Данный раздел позволяет задать слово, частоту встречаемости которого необходимо посчитать в новостях за определенный промежкток времени'
  },
  {
    title:'НОВОСТИ',
    description:' Просмотр новостей с 2019 года по текущее время.',
    image:'../src/assets/images/news.jpg',
    style:'',
    path:'/NewsPage/',
    text:''
  }
])

</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 300px
</style>
