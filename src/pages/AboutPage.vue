<template>
  <div class="text-center q-pa-md flex flex-center">
    <q-card dark bordered class="bg-grey-9 my-card q-pa-md ">
      <q-card-section>
        <div class="text-h6">Мировые новости, статистика и анализ</div>
        <div class="text-subtitle2">Краткое описание проекта</div>
      </q-card-section>
      <q-separator dark inset />

      <q-card-section>
        Проект создан с помощью javascript-фреймворка Quasar (Vue3).
      </q-card-section>
      <q-card-section>
        Бэкенд представлят собой набор API, разработанный на PHP.
      </q-card-section>
      <q-card-section>
        База данных заполняется скриптом, запускаемым на хостинге по расписанию 5 раз в сутки с 2019 года и на данный момент (январь 2025 г.) содержит около 4,5 млн. записей.
      </q-card-section>
    </q-card>

    <q-card class="my-card q-pa-md" flat bordered>
      <q-img
        src="/images/vuechart-example.png"
      />

      <q-card-section>
        <div class="text-overline text-orange-9">Overline</div>
        <div class="text-h5 q-mt-sm q-mb-xs">Title</div>
        <div class="text-caption text-grey">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </div>
      </q-card-section>

      <q-card-actions>
        <q-btn :to="'/'" flat color="primary" label="Перейти" />

        <q-space />

        <q-btn
          color="grey"
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
            {{ lorem }}
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>
  </div>
</template>

<script setup>
import {ref} from "vue";

const expanded = ref(false)
const lorem = ('sklldjfcklsjdvlksdvlk')
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 250px
</style>
