<template>
  <div class="text-center q-pa-md flex flex-md-center">
    <q-card 
    v-for="item in cardOptions" 
    :key="item.title"
    class="my-card q-pa-md q-ma-md" 
    >
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
    image:'./src/assets/images/favicon-32x32.png',
    style:'height: 100px; max-width: 100px',
    path:'',
    text:'Проект создан с помощью javascript-фреймворка Quasar (Vue3).\n' +
      '            Бэкенд представлят собой набор API, разработанный на PHP.\n' +
      '            База данных заполняется скриптом, запускаемым на хостинге по расписанию\n' +
      '            5 раз в сутки с 2019 года и на данный момент (январь 2025 г.) содержит около 4,5 млн. записей.'
  },
  {
    title:'СЕГОДНЯ В НОВОСТЯХ',
    description:'Наиболее часто встречающиеся слова в новостях за день.',
    image:'./src/assets/images/tags.jpg',
    style:'height: 100px; max-width: 130px',
    path:'/TagsPage/',
    text:'В данном разделе можно увидеть наиболее часто встречающиеся слова в новостях за определенный день. \n' +
      'Слова на русском и английском языках считаются отдельно. Подсчет слов ведется отдельным python-скриптом .\n' +
      'Подсчет осуществляется на сервере, результаты отправляются в базу данных.' 
  },
  {
    title:'СТАТИСТИКА',
    description:'Статистика встречаемости слов в новостях.',
    image:'./src/assets/images/vuechart-example.png',
    style:'height: 130px; max-width: 200px',
    path:'/StatisticsPage/',
    text:'В этом разделе вы можете задать слово, частоту встречаемости которого необходимо посчитать в новостях за определенный промежкток времени. \n' +
         'езультаты отображаются в виде столбиковой диаграммы. По клику на столбик отображается распределение частот по агентства'
  },
  {
    title:'НОВОСТИ',
    description:' Просмотр новостей с 2019 года по текущее время. ',
    image:'./src/assets/images/news.jpg',
    style:'',
    path:'/NewsPage/',
    text:'На данной странице отображаются все новости с сортировкой по агентствам и дате.'
  },
  {
    title:'КАРТА МИРА',
    description:'Просмотр новостей по странам мира. ',
    image:'./src/assets/images/map.jpg',
    style:'',
    path:'/WorldMapPage/',
    text:'В данном разделе представлена карта мира, на которой Крым наш + новые территории.  \n' +
         'Кликните по интересующей вас стране, выберите дату и нажмите кнопку "Загрузить". \n' +
         'В результате будут отображены новости, в которых встречается название выбранной страны.'
  }
])

</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 300px
</style>
