Проет Квазар с бэком
в каталоге api - php ендпоинты
В опенсервере создаем домен с проектом и запускаем
axios-запросы делаем к этому серверу, например   const res = await axios.get('http://quasar-test/'+url)

Для прода в quasar config прописан каталог для assets. Это каталог, куда будем заливать проект на хостинг
 extendViteConf (viteConf, { isClient, isServer }) {
        viteConf.base = '/testspa/spa/';
      },

Заменить для axios const res = await axios.get('/testspa/spa/'+url)

Скомпилить quasar build
в каталог spa закинуть каталог api
Архивируем, закидываем на хостинг и разархивируем в каталог  /testspa/spa/
