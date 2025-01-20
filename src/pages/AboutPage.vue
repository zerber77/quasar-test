<template>
  <div class="text-center q-pa-md flex flex-md-center">

    <q-card class="my-card q-pa-md q-ma-md"  >
      <q-img
        src="/icons/favicon-32x32.png"
        style="height: 100px; max-width: 100px"
      />
      <q-card-section>
        <div class="text-h5">Мировые новости, статистика и анализ</div>
        <div class="text-body1 text-black">
          Краткое описание проекта.
        </div>
      </q-card-section>

      <q-card-actions>
        <q-space />
        <q-btn
          color="grey"
          round
          flat
          dense
          :icon="expanded0 ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
          @click="expanded0 = !expanded0"
        />
      </q-card-actions>

      <q-slide-transition>
        <div v-show="expanded0">
          <q-separator />
          <q-card-section class="text-subtitle2">
            Проект создан с помощью javascript-фреймворка Quasar (Vue3).
            Бэкенд представлят собой набор API, разработанный на PHP.
            База данных заполняется скриптом, запускаемым на хостинге по расписанию
            5 раз в сутки с 2019 года и на данный момент (январь 2025 г.) содержит около 4,5 млн. записей.
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>


    <q-card class="my-card q-pa-md q-ma-md" >
      <q-img
        src="/images/vuechart-example.png"
      />
      <q-card-section>
        <div class="text-h5 ">СТАТИСТИКА</div>
        <div class="text-body1  text-black">
          В данном разделе можно увидеть частоту встречаемости слов в тексте новостей и заголовков.
        </div>
      </q-card-section>

      <q-card-actions>
        <q-btn :to="'/'" flat label="Перейти" class="bg-green" />

        <q-space />

        <q-btn
          color="grey"
          round
          flat
          dense
          :icon="expanded1 ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
          @click="expanded1 = !expanded1"
        />
      </q-card-actions>

      <q-slide-transition>
        <div v-show="expanded1">
          <q-separator />
          <q-card-section class="text-subtitle2">
            {{ lorem }}
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>
  </div>
</template>

<script setup>
import {ref} from "vue";

const expanded0 = ref(false)
const expanded1 = ref(false)
const lorem = ('Данный раздел позволяет задать слово, частоту встречаемости которого необходимо посчитать в новостях за определенный промежкток времени')
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 300px
</style>
