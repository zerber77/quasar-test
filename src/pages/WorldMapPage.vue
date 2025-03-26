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
        <template v-if="country.countryRU">выбрано:{{country.countryRU}}</template>
        <q-icon
          class="text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="setHelpMessage(HelpMessages[0])"
        />
      </div>
    </div>
    <div class="row q-pa-md">
        <div v-if="country.countryRU" class="col-12 col-md-4 row">
          <CalendarComponent
            :range = "false"
            @update = "dateChanged"
          />

          <q-icon
            class="q-mt-md text-green-8"
            style="font-size: 2rem"
            name="help"
            @click.prevent="setHelpMessage(HelpMessages[1])"
          />
        </div>

        <div class="col-12 col-md-8">
          <q-btn
            v-if = "country.countryEN && dateSelected"
            label="Загрузить новости"
            color="primary"
            @click="loadNews()"
          />
          <div v-if="news.length">
            <q-card
              v-for="item in news"
              :key="item.id"
              class="my-card text-white"
              style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"
            >
              <q-card-section>
                <div class="text-h6">{{item.head}}</div>
                <div class="text-subtitle2">{{item.agency}} - {{item.pubdate}}</div>
              </q-card-section>

              <q-card-section class="q-pt-none">
                {{item.text}}
                <div><a :href="item.link" target="_blank" style="color: yellow">Ссылка</a></div>
              </q-card-section>
            </q-card>
          </div>
        </div>
    </div>

  </q-page>
</template>

<script setup>
import WorldMapComponent from "components/Map/WorldMapComponent.vue";
import CalendarComponent from "components/IndexComponents/CalendarComponent.vue";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import {inject, onMounted, reactive, ref} from 'vue'
import useMessageVars from "components/modules/messages/getMessageVars";
import {getWordOfDay} from "components/modules/wordofday/getWordOfDay";
import {getNewsByCountry} from "components/modules/maps/getNewsByCountry";

const loading = ref(false)
let country = ref({})
let dateSelected = ref(new Date().toISOString().slice(0, 10))
const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()
const authorised = inject('authorised');

const news = ref([])

const selectedCountry = (countryEN,countryRU) => {
  if (!authorised.isAuthenticated) {
    setErrorMessage(`Вы не авторизованы на сайте. Для получения доступа ко всем функциям необходимо зарегистрироваться`)
    return
  }
  country.value = { countryRU, countryEN }
}
const  dateChanged = (date) =>  dateSelected.value = date

onMounted(()=>{
})

const loadNews = async () => {
  try {
    const {response} = await  getNewsByCountry(dateSelected.value,  country.value.countryRU, country.value.countryEN)
    news.value = response.value
    if (news.value.error){
      setErrorMessage(`Ошибка:`+ news.value.error )
      return
    }
    if (!news.value.length) {
      setErrorMessage(`Для даты ${dateSelected.value} данные отсутствуют`)
    }
    else {}
  }catch (err){   ////все ошибки токена возвращают код 401
    if (err === 'Request failed with status code 401') setErrorMessage(`Ошибка, вы не зарегистрированы в системе. Зарегистрируйтесь или войдите на сайт.`)
    else setErrorMessage(`Неполадки на сервере, попробуйте позже.`)
  }
}

const HelpMessages = ref([
  'В данном разделе представлена карта мира, на которой Крым наш + новые территории. ' +
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
.my-card
  margin: 3px
</style>
