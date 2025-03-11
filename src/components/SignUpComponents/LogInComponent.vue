<template>
  <ErrorMessageComponent
    v-model = "error"
    :errorMessage = "errorMessage"
  />
  <HelpMessageComponent
    v-model = "help"
    :helpMessage = "helpMessage"
  />
  <div class="signup-form">
    <h3>Войти на сайт</h3>
    <form @submit.prevent="handleSubmit">
      <!-- Логин -->
      <div class="form-group">
        <label for="login">Логин:</label>
        <input
          type="text"
          id="login"
          v-model="formData.login"
          required
        />
      </div>
      <!-- Пароль -->
      <div class="form-group">
        <label for="password">Пароль:</label>
        <input
          type="password"
          id="password"
          v-model="formData.password"
          required
        />
      </div>
      <!-- Кнопка отправки формы -->
      <button type="submit">ВОЙТИ</button>

    </form>
  </div>
</template>

<script setup>
import {inject, ref} from 'vue';

import {postLogInData} from "components/modules/auth/postLogInData";
import useMessageVars from "components/modules/messages/getMessageVars";
import ErrorMessageComponent from "components/Modals/ErrorMessageComponent.vue";
import HelpMessageComponent from "components/Modals/HelpMessageComponent.vue";

import { useRouter } from "vue-router";

const router = useRouter();
const authorised = inject('authorised');

const formData = ref({
  login: '',
  password: ''
})

const {help, error,helpMessage,errorMessage, setHelpMessage, setErrorMessage} = useMessageVars()

// Обработчик отправки формы
const handleSubmit = async () => {
  try {
    // Отправляем данные на сервер
    const response = await postLogInData( {
      login: formData.value.login,
      password: formData.value.password
    })
    debugger
    if(response.data.value.error){
      setErrorMessage(response.data.value.error)
      return
    }
    // Если всё успешно, перенаправляем пользователя или показываем сообщение успеха
    console.log('Регистрация успешна:', response.data.value.token);
    localStorage.setItem('authToken', response.data.value.token);
    authorised.isAuthenticated = true
    authorised.user =  formData.value.login
    setHelpMessage('Вы успешно авторизовались!')
//    routes.push('NewsPage/')
    setTimeout(() =>{router.push({path:'/'})},1000)
    //alert('Вы усешно автоизовались!');
    // Можно добавить редирект на страницу входа или другую страницу
  } catch (error) {
      setErrorMessage(`Ошибка на сервере, попробуйте позже.`)
      console.error('Ошибка авторизации:', error);
  }
}
</script>

<style scoped>
.signup-form {
  max-width: 400px;
  margin: auto;
  padding: 20px;
  /*border: 1px solid #ccc;*/
  border-radius: 5px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #35a674;
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
