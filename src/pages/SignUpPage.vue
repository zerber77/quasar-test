<template>
  <div class="signup-form">
    <h2>Регистрация</h2>
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

      <!-- Имя пользователя -->
      <div class="form-group">
        <label for="name">Имя:</label>
        <input
          type="text"
          id="name"
          v-model="formData.user.name"
          required
        />
      </div>

      <!-- Email -->
      <div class="form-group">
        <label for="email">Email:</label>
        <input
          type="email"
          id="email"
          v-model="formData.user.email"
          required
        />
      </div>

      <!-- Пароль -->
      <div class="form-group">
        <label for="password">Пароль:</label>
        <input
          type="password"
          id="password"
          v-model="formData.user.password"
          required
        />
      </div>

      <!-- Кнопка отправки формы -->
      <button type="submit">Зарегистрироваться</button>

      <!-- Сообщение об ошибке -->
      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import {postSignUpData} from "components/modules/postSignUpData";

// Реактивные переменные
const formData = ref({
  login: '',
  user: {
    name: '',
    email: '',
    password: ''
  }
});
const errorMessage = ref('');

// Обработчик отправки формы
const handleSubmit = async () => {
  try {
    // Отправляем данные на сервер
    const response = await postSignUpData( {
      login: formData.value.login,
      user: formData.value.user
    });

    // Если всё успешно, перенаправляем пользователя или показываем сообщение успеха
    console.log('Регистрация успешна:', response.data);
    alert('Вы успешно зарегистрированы!');
    // Можно добавить редирект на страницу входа или другую страницу
  } catch (error) {
    // Обработка ошибок
    if (error.response && error.response.data && error.response.data.error) {
      errorMessage.value = error.response.data.error;
    } else {
      errorMessage.value = 'Произошла ошибка при регистрации. Пожалуйста, попробуйте позже.';
    }
    console.error('Ошибка регистрации:', error);
  }
};
</script>

<style scoped>
.signup-form {
  max-width: 400px;
  margin: auto;
  padding: 20px;
  border: 1px solid #ccc;
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
