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
    <div v-if="authorised.isAuthenticated && !authorised.review_sended" class="q-pa-md">
      <!-- Кнопка или textarea -->
      <transition name="fade" mode="out-in">
        <q-btn
          v-if="!showTextarea"
          color="primary"
          label="Написать отзыв"
          @click="showTextarea = true"
          class=""
        />
        <div v-else class="review-container">
          <p>Имя:{{authorised.name}} Логин:{{authorised.name}}</p>
          <q-input
            v-model="reviewText"
            type="textarea"
            outlined
            :rules="[validateReview]"
            placeholder="Введите ваш отзыв..."
            class="custom-textarea"
            style="max-width: 50rem"
            ref="reviewInput"
          />
          <q-btn
            color="primary"
            label="Отправить отзыв"
            :disable="!isReviewValid"
            @click="submitReview"
            style="max-width: 15rem"
            class="q-mt-sm"
          />
        </div>
      </transition>
    </div>
<!--    <div v-else-if="authorised.review_sended">-->
<!--      <h4 class="q-ml-lg ">Ваш отзыв отправлен, спасибо за участие!</h4>-->
<!--    </div>-->
    <div v-if="reviews.length" class="q-pa-lg row q-gutter-md flex-center">
      <q-card
        v-for="item in reviews"
        :key="item.id"
        class="my-card text-white"
        style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"
      >
        <q-card-section>
          <div class="text-h6">{{item.name}}</div>
          <div class="text-subtitle2">{{item.created_at}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          {{item.review}}
        </q-card-section>
      </q-card>
    </div>

  </q-page>
</template>

<script setup>
import {computed, inject, onMounted, ref} from 'vue'
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";
import useMessageVars from "components/modules/messages/getMessageVars";
import {postLogInData} from "components/modules/auth/postLogInData";
import {postReview} from "components/modules/review/postReview";
import { getReviews } from 'src/components/modules/review/getReviews';
let showTextarea = ref(false); // Показывать ли textarea
const reviewText = ref(''); // Текст отзыва
const reviewInput = ref(null); // Референс на QInput
const reviews = ref([]); // Массив отзывов


const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()
const authorised = inject('authorised');

onMounted(()=>{
  loadReviews()
})

const validateReview = (value) => {
  const regex = /^[a-zA-Zа-яА-Я0-9\s.,!?;:()-]+$/;
  return regex.test(value) || 'Только русские/английские буквы, цифры и знаки препинания!';
}

// Вычисляемое свойство для проверки валидности
const isReviewValid = computed(() => {
  if (!reviewInput.value) return false;
  return reviewInput.value.validate() && reviewText.value.trim().length > 0;
})

// Обработчик отправки отзыва
const submitReview = async () => {
  if (isReviewValid.value) {
    try {
      // Отправляем данные на сервер
      const response = await postReview({
        login: authorised.login,
        name: authorised.name,
        review: reviewText.value
      })
      if (response.error) {
        setErrorMessage(response.error)
        return
      }
      loadReviews()
      console.log('Отзыв отправлен:', reviewText.value);
      setHelpMessage('Отзыв отправлен')
      reviewText.value = ''; // Очищаем поле
      showTextarea.value = false; // Скрываем textarea
      authorised.review_sended = true
    }catch (err){
      setErrorMessage(err)
    }

  }
}

const loadReviews = async() =>{
  try {
        // Отправляем данные на сервер
        const {response} = await getReviews()
        if (response.error) {
          setErrorMessage(response.error)
          return
        }
        console.log('Отзывы загружены:', response.value.reviews);
        console.log('A:', authorised);
        reviews.value = response.value.reviews
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        reviews.value.forEach(item =>{
          debugger
          if (item.name === authorised.name && (item.created_at.indexOf(today) !== -1)){
            authorised.review_sended = true
          }
        })
    }catch (err){
      setErrorMessage(err)
    }
}

</script>

<style scoped>
/* Анимация появления/исчезновения */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
.review-container {
  display: flex;
  flex-direction: column;
  gap: 8px; /* Расстояние между элементами */
}
.custom-textarea {
  font-size: 1.5rem; /* Увеличиваем размер шрифта */
}
</style>
