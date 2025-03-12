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
    <h1 class="text-center text-green-10" style="font-size: 3rem">Отзывы и предложения</h1>
  </q-page>
</template>

<script setup>
import {inject, onMounted, ref} from 'vue'
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import useMessageVars from "components/modules/messages/getMessageVars";

const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()
const authorised = inject('authorised');

onMounted(()=>{
  if (!authorised.isAuthenticated) {
    setErrorMessage(`Вы не авторизованы на сайте. Ля получения возможности писать отзывы, необходимо зарегистрироваться на сайте`)
  }
})
</script>

<style scoped>

</style>
