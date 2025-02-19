<template>
  <div class="col-12 col-md-12 text-center" style="min-height: 300px !important;">
      <div ref="chartdiv" style="width: 100%; height: 600px;"></div>
      <div class="title">Карта мира
        <q-icon
          class="text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="showHelpMessage(1)"
        />
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4maps from "@amcharts/amcharts4/maps";
import am4geodataWorldLow from "@amcharts/amcharts4-geodata/worldLow"
import {getCountryNamesRU} from "components/modules/maps/RU_names";

const chartdiv = ref(null);
let chart = null;
// Определение русской локали
const countryNamesRU = getCountryNamesRU()
// Функция для добавления русских названий в геоданные
function addRussianNamesToGeoData(geoData, countryNamesRU) {
  if (geoData && geoData.features) {
    geoData.features.forEach(feature => {
      const isoCode = feature.properties.id // Получаем ISO-код страны
      if (countryNamesRU[isoCode]) {
        feature.properties.nameRU = countryNamesRU[isoCode]; // Добавляем русское название
      } else {
        feature.properties.nameRU = feature.properties.name; // Если нет перевода, используем оригинальное имя
      }
    });
  }
}

function mergeRegions(geoData) {
  if (geoData && geoData.features) {
    geoData.features.forEach(feature => {
      // Объединяем Тайвань с Китаем
      console.log(feature)

      // Объединяем Крым с Россией
      if (feature.properties.name === "Crimea") {
        feature.properties.parent = "RU"; // Добавляем Крым как часть России
      }
      if (feature.properties.id === "TW") {
        feature.properties.parent = "CN" // Добавляем Тайвань как часть Китая
        feature.properties.id = "CN"
      }
    });
  }
}

// Функция для переноса Крыма в Россию
function transferCrimeaToRussia(geoData) {
  if (geoData && geoData.features) {
    debugger
    // Находим Украину и Россию по их ID
    const ukraine = geoData.features.find(feature => feature.properties.id === "UA");
    const russia = geoData.features.find(feature => feature.properties.id === "RU");

    if (ukraine && russia) {
      // Координаты Крыма (диапазон для поиска)
      const isCrimea = (coords) =>
        coords[0] >= 33 && coords[0] <= 36 && coords[1] >= 44 && coords[1] <= 47;

      // Ищем Крым в многоугольнике Украины
      // const crimeaPolygonIndex = ukraine.geometry.coordinates[0].findIndex((polygon) =>
      //   polygon.some(isCrimea)
      // );
      const crimeaPolygonIndex = 10
      if (crimeaPolygonIndex !== -1) {
        // Получаем координаты Крыма
        const crimeaCoordinates = ukraine.geometry.coordinates[0][crimeaPolygonIndex];

        // Удаляем Крым из Украины
        ukraine.geometry.coordinates.splice(crimeaPolygonIndex, 1);

        // Добавляем Крым в Россию
        if (!russia.geometry.coordinates) {
          russia.geometry.coordinates = [];
        }
        russia.geometry.coordinates[22] = []
        russia.geometry.coordinates[22].push(crimeaCoordinates);
        console.log(russia.geometry.coordinates)
      }
    }
  }
}
    // Инициализация карты
    const initMap = () => {
 //     mergeRegions(am4geodataWorldLow)
      addRussianNamesToGeoData(am4geodataWorldLow, countryNamesRU);
      transferCrimeaToRussia(am4geodataWorldLow);
      chart = am4core.create(chartdiv.value, am4maps.MapChart);

      // Задаем географические данные
      chart.geodata = am4geodataWorldLow;

      // Создаем полигон для отображения стран
      const polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
      polygonSeries.useGeodata = true;

      // Настройка внешнего вида полигонов
      // Настройка внешнего вида полигонов
      const polygonTemplate = polygonSeries.mapPolygons.template;
      polygonTemplate.tooltipText = "{nameRU}"; // Используем русское название
      polygonTemplate.fill = am4core.color("#67b7dc");

      ////// Добавляем обработчик события datavalidated
      // polygonSeries.events.on("datavalidated", () => {
      //   console.log("isCrimea:", polygonSeries.mapPolygons.values[0])
      //   polygonSeries.mapPolygons.each((polygon) => {
      //     const countryId = polygon.dataItem?.dataContext?.id;
      //
      //     // Обрабатываем Тайвань
      //     if (countryId === "TW") {
      //       polygon.fill = am4core.color("#FF0000"); // Цвет Китая
      //       polygon.stroke = am4core.color("#000000");
      //       polygon.strokeWidth = 0.5;
      //     }
      //
      //     // Обрабатываем Крым (по координатам)
      //     const isCrimea = polygon.dataItem?.dataContext?.multiPolygon?.some(
      //       (polygonCoords) =>
      //         polygonCoords[0].some(
      //           (coord) => coord[0] >= 33 && coord[0] <= 36 && coord[1] >= 44 && coord[1] <= 47
      //         )
      //     );
      //
      //     if (isCrimea) {
      //       polygon.fill = am4core.color("#0000FF"); // Цвет России
      //       polygon.stroke = am4core.color("#000000");
      //       polygon.strokeWidth = 0.5;
      //     }
      //
      //     // Применяем стандартные цвета для других стран
      //     if (!polygon.fill) {
      //       polygon.fill = am4core.color("#67b7dc");
      //       polygon.stroke = am4core.color("#000000");
      //       polygon.strokeWidth = 0.5;
      //     }
      //   });
      // });


      // Изменяем цвет при наведении
      polygonTemplate.propertyFields.fill = "color";
      polygonTemplate.nonScalingStroke = true;
      polygonTemplate.strokeWidth = 0.5;
      polygonTemplate.stroke = am4core.color("#FFFFFF");

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.homeZoomLevel = 1;
      chart.homeGeoPoint = { longitude: 0, latitude: 0 }

      // Настройка тултипов и надписей без использования am4core.locale
      chart.zoomControl.plusButton.tooltipText = "Увеличить";
      chart.zoomControl.minusButton.tooltipText = "Уменьшить";

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.zoomControl.plusButton.tooltipText = "Увеличить"; // Русский текст
      chart.zoomControl.minusButton.tooltipText = "Уменьшить"; // Русский текст

      /////события
      polygonTemplate.events.on("hit", (ev) => {
        console.log(ev.target.dataItem.dataContext.name)
      })
    }

    // Очищаем ресурсы при уничтожении компонента
    onBeforeUnmount(() => {
      if (chart) {
        chart.dispose()
      }
    })

    // Инициализируем карту при монтировании компонента
    onMounted(() => {
      if (chartdiv.value) {
        initMap()
      }
    })


</script>

<style lang="sass" scoped>
.title
  font-size: 1.5em
  font-weight: bold
  color: #2669a1
</style>
