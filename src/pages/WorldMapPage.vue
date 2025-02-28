<template>
  <ErrorMessageComponent
    v-model = "error"
    :errorMessage = "errorMessage"
  />
  <HelpMessageComponent
    v-model = "help"
    :helpMessage = "helpMessage"
  />
  <q-page>
    <div class="col-12 text-center" style="">
      <WorldMapComponent
        @onCountry = "selectedCountry"
      />
      <div class="title">Карта мира
        <q-icon
          class="text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="setHelpMessage(HelpMessages[0])"
        />
      </div>
    </div>
    <div class="col-12 col-md-4 row">
      <CalendarComponent
        :range = "false"
        @rangeSet = ""
        @rangeStart = ""
        @update = "dateChanged"
      />

      <q-icon
        class="q-mt-md text-green-8"
        style="font-size: 2rem"
        name="help"
        @click.prevent="setHelpMessage(HelpMessages[1])"
      />
    </div>
  </q-page>
</template>

<script setup>
import WorldMapComponent from "components/Map/WorldMapComponent.vue";
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import {ref} from 'vue'
import useMessageVars from "components/modules/messages/getMessageVars";

const loading = ref(false)
const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()

const selectedCountry = (countryEN,countryRU) =>{
  console.log('selected',countryEN, countryRU)
}

const HelpMessages = ref([
  'В данном разделе представлена карта мира, на которой Крым наш + новые территории' +
  'Кликните по интересующей вас стране, выберите дату и нажмите кнопку "Загрузить".' +
  'В результате будут отображены новости, в которых встречается название выбранной страны.',
  'Выберите дату'
])

</script>

<style lang="sass" scoped>
.title
  font-size: 1.5em
  font-weight: bold
  color: #2669a1
</style>
