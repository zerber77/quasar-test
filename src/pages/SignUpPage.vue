<template>
  <div class="col-12 q-pa-md row justify-center">
    <div class="q-gutter-y-md " style="max-width: 600px; min-width: 350px">
      <q-card>
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab name="mails" label="ВХОД" />
          <q-tab name="alarms" label="РЕГИСТРАЦИЯ" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="mails">
            <LogInComponent/>
          </q-tab-panel>

          <q-tab-panel name="alarms">
            <SignUpComponent/>
          </q-tab-panel>

        </q-tab-panels>
      </q-card>

      <q-card>
        <q-separator />
        <q-tabs
          v-model="tab"
          dense
          :class="$q.dark.isActive ? 'bg-grey-9' : 'bg-grey-3'"
          align="justify"
          narrow-indicator
        >
          <q-tab name="mails" label="ВХОД" />
          <q-tab name="alarms" label="РЕГИСТРАЦИЯ" />
        </q-tabs>
      </q-card>
    </div>
  </div>

</template>

<script setup>
import { ref } from 'vue';
import SignUpComponent from "components/SignUpComponents/SignUpComponent.vue";
import LogInComponent from "components/SignUpComponents/LogInComponent.vue";

let tab =  ref('mails')

</script>

<style scoped>

</style>
